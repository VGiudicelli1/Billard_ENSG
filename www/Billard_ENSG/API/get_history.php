<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php

  /*****************************  VERIFY DATA IN  *****************************/
  $data_in = API_get_data_in([], ["player"=>"*"]);
  $player = str_replace("*", "%", $data_in["player"]);

  /*****************************  DATABASE QUERY  *****************************/
  list($res, $err) = query(
    "SELECT
      `p`.`name`,
      `g`.`date`,
      `new_elo` - `delta_elo` AS `last_elo`,
      `delta_elo`,
      `new_elo`
      FROM `player_game`
      JOIN `player` AS `p` ON `p`.`id` = `player`
      JOIN `game` AS `g` ON `game` = `g`.`id`
      WHERE `p`.`name` LIKE ?
      ORDER BY `g`.`date`;",
      "s", [$player]
  );

  /*****************************    SEND RESULT   *****************************/
  API_send_result_done([
    "history" => $res
  ]);
 ?>
