<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php
  include_once("../../../server/Billard_ENSG/compute/compute_elo.php");

  /*****************************  VERIFY DATA IN  *****************************/
  $data_in = API_get_data_in(["j1", "j3"], ["j2"=>-1, "j4"=>-1, "date"=>date('Y-m-d h:i:s')]);

  $j1 = intval($data_in["j1"]);
  $j2 = intval($data_in["j2"]);
  $j3 = intval($data_in["j3"]);
  $j4 = intval($data_in["j4"]);
  if ($j1 <= 0 || $j3 <= 0 || $j1 == $j2 || $j1 == $j3 || $j1 == $j4 || $j2 == $j3 || $j3 == $j4 || ($j2 > 0 && $j2 == $j4)) {
    API_send_result_error(ERROR_WRONG_VALUE);
  }

  $date = date('Y-m-d h:i:s', strtotime($data_in["date"]));

  /*****************************      GET ELO     *****************************/
  list($res, $err) = query("SELECT
      `player`.`id` AS `id`,
      `player`.`name` AS `name`,
      `player`.`elo` AS `elo`,
      COUNT(`player_game`.`player`) AS `nb_games`
    FROM `player`
    LEFT JOIN `player_game` ON `player`.`id` = `player_game`.`player`
    WHERE `player`.`id` IN (?, ?, ?, ?)
    GROUP BY `player`.`id`",
    "dddd", [$j1, $j2, $j3, $j4]
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

  foreach (compute_elo($j1, $j2, $j3, $j4, $players) as $id => $delta_elo) {
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

  /***************************** SEND RESULT DONE *****************************/
  API_send_result_done(["id_game" => $game_id, "p" => $players]);

 ?>
