<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php
  include_once("../../../server/Billard_ENSG/compute/compute_elo.php");

  /*****************************  VERIFY DATA IN  *****************************/
  $date_min = date('Y-m-d h:i:s', strtotime(API_get_data_in([], ["from_date"=>0])["from_date"]));
  
  list($ids, $err) = query("SELECT `id` FROM `game` WHERE `date` >= ? ORDER BY `date` ASC", "d", [$date_min]);
  if ($err) {
    API_send_result_error(ERROR_INTERN);
  }

  foreach ($ids as ["id" => $id_game]) {
    $id_game = intval($id_game);
    echo "recompute for game $id_game...";

    # get data from database about curent game (and elo before this game)
    list($res, $err) = query("SELECT
      `player`.`name` AS `name`,
      `player`.`id` AS `id`,
      COUNT(`pg2`.`game`) AS `nb_games`,
      `pg`.`delta_elo`>0 AS `winner`,
      COALESCE(MAX(`pg2`.`game` * 1e6 + `pg2`.`new_elo`) - MAX(`pg2`.`game` * 1e6), 470) AS `preview_elo`
      FROM `player_game_date` AS `pg`
      JOIN `player` ON `pg`.`player` = `player`.`id`
      LEFT JOIN `player_game_date` AS `pg2` ON `pg2`.`player` = `player`.`id` AND `pg2`.`date` < `pg`.`date`
      WHERE `pg`.`game` = $id_game
      GROUP BY `player`.`id`"
    );
    if ($err) {
      echo $err;
      API_send_result_error(ERROR_INTERN);
    }
    if (count($res) < 2) {
      echo "pas assez de joueurs";
      API_send_result_error(ERROR_INTERN);
    }

    # format data into php table
    $players = [];
    foreach ($res as ["id" => $id, "name" => $name, "preview_elo" => $elo, "nb_games" => $nb_games, "winner" => $winner]) {
      $players[$id] = [
        "name" => $name,
        "elo" => $elo,
        "nb_games" => $nb_games,
        "winner" => boolval($winner),
      ];
    }

    # get teams
    $w = [];
    $l = [];
    foreach ($players as $id => $player) {
      if ($player["winner"]) {
        $w[] = $id;
      } else {
        $l[] = $id;
      }
    }
    $j1 = $w[0];
    $j2 = count($w)==1 ? -1 : $w[1];
    $j3 = $l[0];
    $j4 = count($l)==1 ? -1 : $l[1];

    # compute new elo
    $players = compute_elo_v1($j1, $j2, $j3, $j4, $players);

    # control sign ∆Elo
    if (
      $players[$j1]["delta_elo"] < 0 || $players[$j3]["delta_elo"] > 0
      || ($j2>0 && $players[$j2]["delta_elo"] < 0)
      || ($j4>0 && $players[$j4]["delta_elo"] > 0)
    ) {
      echo "error sign delta elo";
      API_send_result_error(ERROR_INTERN);
    }

    # update database
    foreach ([$j1,$j2,$j3,$j4] as $id) {
      if ($id > 0) {
        list($res, $err) = query(
          "UPDATE `player_game` SET `delta_elo`=?,`new_elo`=? WHERE `player`=? AND `game`=?",
          "dddd",
          [$players[$id]["delta_elo"], $players[$id]["elo"]+$players[$id]["delta_elo"], $id, $id_game]
        );
      }
    }
    echo " done<br>";

  }
  die;



  /*****************************  ELO BEFORE GAME *****************************/
  list($res, $err) = query("SELECT
      `player`.`id` AS `id`,
      `player`.`name` AS `name`,
      `player`.`elo` AS `elo`,  -- donnee variable
      COUNT(`player_game`.`player`) AS `nb_games`
    FROM `player`
    LEFT JOIN `player_game` ON `player`.`id` = `player_game`.`player` AND `player_game`.`game` < ?
    WHERE `player`.`id` IN (?, ?, ?, ?)
    GROUP BY `player`.`id`",
    "ddddd", [$id_game, $j1, $j2, $j3, $j4]
  );
  if ($err) {
    API_send_result_error(ERROR_INTERN);
  }
  $players = [];
  foreach ($res as ["id" => $id, "name" => $name, "elo" => $elo, "nb_games" => $nb_games]) {
    $players[$id] = [
      "elo" => $elo,
      "nb_games" => $nb_games,
    ];
  }

  /*****************************  VERIFY PLAYERS  *****************************/
  if (!array_key_exists($j1, $players)
    || !array_key_exists($j3, $players)
    || ($j2 > 0 && !array_key_exists($j2, $players))
    || ($j4 > 0 && !array_key_exists($j4, $players))) {
    API_send_result_error(ERROR_WRONG_VALUE);
  }

  /*****************************    DELTA ELO     *****************************/

  foreach (compute_elo_v2($j1, $j2, $j3, $j4, $players) as $id => $delta_elo) {
    $players[$id]["delta_elo"] = $delta_elo;
  }

  /***************************** UPDATE DATABASE  *****************************/

  // Add game
  list($res, $err) = query("INSERT INTO `game` (`date`) VALUES (?)", "s", [$date]);
  if ($err) {
    API_send_result_error(ERROR_INTERN);
  }
  // Get game id
  list($res, $err) = query("SELECT LAST_INSERT_ID() AS `id`");
  if ($err || count($res) != 1) {
    API_send_result_error(ERROR_INTERN);
  }
  $game_id = $res[0]["id"];

  foreach ([$j1, $j2, $j3, $j4] as $player_id) {
    if ($player_id <= 0) {
      continue;
    }

    $delta_elo = $players[$player_id]["delta_elo"];
    $new_elo = $players[$player_id]["elo"] + $delta_elo;
    // add lines in player_game
    list($res, $err) = query("INSERT INTO `player_game` (`player`, `game`, `delta_elo`, `new_elo`) VALUES (?, ?, ?, ?)",
      "dddd", [$player_id, $game_id, $players[$player_id]["delta_elo"], $new_elo]);
    if ($err) {
      API_send_result_error(ERROR_INTERN);
    }
    // update player elo
    list($res, $err) = query("UPDATE `player` SET `elo` = ? WHERE `id` = ?",
      "dd", [$new_elo, $player_id]);
    if ($err) {
      API_send_result_error(ERROR_INTERN);
    }
  }

  /*****************************    SEND RESULT   *****************************/
  API_send_result_error(ERROR_NOT_DEVELOPED);
 ?>
