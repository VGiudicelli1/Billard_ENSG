<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php

  /*****************************  VERIFY DATA IN  *****************************/
  $force_erase = API_get_data_in(["force_erase_database"])["force_erase_database"];

  if ($force_erase != "true") {
    echo "error: param `force_erase` will be true";
    die;
  }

  $path = "./all_matchs.csv";

  if (!file_exists($path)) {
    echo "error: fail to find file '$path'";
    die;
  }
  function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
    return mb_convert_encoding(
      $content,
      mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)
    );
  }

  $contents = file_get_contents_utf8($path);
  $contents = str_replace("?", "%", $contents);

  $data = [];
  foreach (explode("\n", $contents) as $line) {
    $data_line = [];
    foreach (explode(";", $line) as $word) {
      $data_line[] = $word;
    }
    $data[] = $data_line;
  }

  query("DELETE FROM `player_game` WHERE 1");
  query("DELETE FROM `game` WHERE 1");
  query("ALTER TABLE `player_game` AUTO_INCREMENT = 1");
  query("ALTER TABLE `game` AUTO_INCREMENT = 1");

  unset($data[0]);

  function getPlayerId($player) {
    $player = trim($player);
    if ($player == "") {
      return -1;
    }
    list($res, $err) = query("SELECT id FROM player WHERE name like ?", "s", [$player]);
    if ($err || count($res) != 1) {
      echo "fail to find player <$player>";
      return 0;
    }
    return $res[0]["id"];
  }

  foreach ($data as [0=>$date, 1=>$heure, 2=>$j1, 3=>$j2, 4=>$j3, 5=>$j4]) {
    $j1_id = getPlayerId($j1);
    $j2_id = getPlayerId($j2);
    $j3_id = getPlayerId($j3);
    $j4_id = getPlayerId($j4);
    $date = date('Y-m-d h:i:s', strtotime($date." ".$heure));

    // Add game
    list($res, $err) = query("INSERT INTO `game` (`date`) VALUES (?)", "s", [$date]);
    if ($err) {
      echo "???($date) : error adding game<br>";
      continue;
    }
    // Get game id
    list($res, $err) = query("SELECT LAST_INSERT_ID() AS `id`");
    if ($err || count($res) != 1) {
      echo "???($date) : error getting game_id<br>";
      continue;
    }
    $game_id = $res[0]["id"];

    // Add players

    list($res, $err) = query(
      "INSERT INTO `player_game`
      (`player`, `game`, `delta_elo`, `new_elo`)
      VALUES
      (?, ?, 1, 0),
      (?, ?, -1, 0)",
      "dddd", [$j1_id, $game_id, $j3_id, $game_id]
    );
    if ($err) {
      echo "$game_id($date) : error adding players<br>";
      continue;
    }

    if ($j2_id != -1) {
      list($res, $err) = query(
        "INSERT INTO `player_game`
        (`player`, `game`, `delta_elo`, `new_elo`)
        VALUES
        (?, ?, 1, 0)",
        "dd", [$j2_id, $game_id]
      );
      if ($err) {
        echo "$game_id($date) : error adding players<br>";
        continue;
      }
    }

    if ($j4_id != -1) {
      list($res, $err) = query(
        "INSERT INTO `player_game`
        (`player`, `game`, `delta_elo`, `new_elo`)
        VALUES
        (?, ?, -1, 0)",
        "dd", [$j4_id, $game_id]
      );
      if ($err) {
        echo "$game_id($date) : error adding players<br>";
        continue;
      }
    }

    echo "$game_id($date) : added<br>";
  }

  die;
 ?>
