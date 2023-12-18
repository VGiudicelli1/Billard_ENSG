<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php

  /*****************************  VERIFY DATA IN  *****************************/
  $data_in = API_get_data_in([], ["period"=>"day"]);

  /*****************************  DATABASE QUERY  *****************************/
  list($result_day, $err) = query("SELECT `player`, `class`, `games`, `W`, `L`, `delta_elo`, `last_elo` FROM `view_statistics_day`");
  if ($err) API_send_result_error(ERROR_INTERN);

  /*****************************    SEND RESULT   *****************************/
  API_send_result_done(["day_stats" => $result_day]);
 ?>
