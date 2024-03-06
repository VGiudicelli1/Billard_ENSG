<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php

  /*****************************  VERIFY DATA IN  *****************************/
  $data_in = API_get_data_in([], ["delta"=>0]);
  try {
    $delta = intval($data_in["delta"]);
  } catch (\Exception $e) {
    $delta = 0;
  }

  /*****************************  DATABASE QUERY  *****************************/
  $query_yesterday = "SELECT
      	`p`.`name` AS `player`,
        `c`.`name` AS `class`,
        COUNT(`pg`.`game`) AS `games`,
        COUNT(case `pg`.`delta_elo` > 0 when 1 then 1 else null end) AS `W`,
      	COUNT(case `pg`.`delta_elo` <= 0 when 1 then 1 else null end) AS `L`,
        SUM(`pg`.`delta_elo`) AS `delta_elo`,
        `p`.`elo` AS `last_elo`
      FROM `player` AS `p`
      JOIN `class` AS `c` ON `c`.`id` = `p`.`class`
      JOIN `player_game` AS `pg` ON `pg`.`player` = `p`.`id`
      JOIN `game` AS `g` ON `pg`.`game` = `g`.`id`
      WHERE DATE(`g`.`date`) = CURRENT_DATE()-$delta
      GROUP BY `p`.`name`
      ORDER BY `elo` DESC
    ;";

  $query_week = "SELECT
    	`p`.`name` AS `player`,
      `c`.`name` AS `class`,
      COUNT(`pg`.`game`) AS `games`,
      COUNT(case `pg`.`delta_elo` > 0 when 1 then 1 else null end) AS `W`,
    	COUNT(case `pg`.`delta_elo` <= 0 when 1 then 1 else null end) AS `L`,
      SUM(`pg`.`delta_elo`) AS `delta_elo`,
      `p`.`elo` AS `last_elo`
    FROM `player` AS `p`
    JOIN `class` AS `c` ON `c`.`id` = `p`.`class`
    JOIN `player_game` AS `pg` ON `pg`.`player` = `p`.`id`
    JOIN `game` AS `g` ON `pg`.`game` = `g`.`id`
    WHERE WEEK(`g`.`date`) = WEEK(CURRENT_DATE() - $delta )
    GROUP BY `p`.`name`
    ORDER BY `elo` DESC
  ;";

  if ($delta!=0) {
    list($result_day, $err) = query($query_yesterday);
  } else {
    list($result_day, $err) = query("SELECT `player`, `class`, `games`, `W`, `L`, `delta_elo`, `last_elo` FROM `view_statistics_day`");
  }
  if ($err) API_send_result_error(ERROR_INTERN);

  if ($delta!=0) {
    list($result_all, $err) = query($query_week);
  } else {
    list($result_all, $err) = query("SELECT `player`, `class`, `games`, `W`, `L`, `delta_elo`, `last_elo` FROM `view_statistics_all`");
  }
  if ($err) API_send_result_error(ERROR_INTERN);

  list($result_week, $err) = query("SELECT `player`, `class`, `games`, `W`, `L`, `delta_elo`, `last_elo` FROM `view_statistics_week`");
  if ($err) API_send_result_error(ERROR_INTERN);

  /*****************************    SEND RESULT   *****************************/
  API_send_result_done([
    "day_stats" => $result_day,
    "all_stats" => $result_all,
    "week_stats" => $result_week,
  ]);
 ?>
