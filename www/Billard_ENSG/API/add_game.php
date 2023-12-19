<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php

  /*****************************  VERIFY DATA IN  *****************************/
  $data_in = API_get_data_in(["j1", "j3"], ["j2"=>-1, "j4"=>-1]);

  $j1 = intval($data_in["j1"]);
  $j2 = intval($data_in["j2"]);
  $j3 = intval($data_in["j3"]);
  $j4 = intval($data_in["j4"]);
  if ($j1 <= 0 || $j3 <= 0 || $j1 == $j2 || $j1 == $j3 || $j1 == $j4 || $j2 == $j3 || $j3 == $j4 || ($j2 > 0 && $j2 == $j4)) {
    API_send_result_error(ERROR_WRONG_VALUE);
  }


  API_send_result_error(ERROR_NOT_DEVELOPED);

  // TODO

  // get elo j1, j2, j3, j4
  // compute delta elo j1, j2, j3, j4

  // Add game
  // Get game id
  list($res, $err) = query('SELECT LAST_INSERT_ID() AS `id`');
  if ($err || count($res) != 1) {
    API_send_result_error(ERROR_INTERN);
  }
  // add lines in player_game
  // update player elo

  /***************************** SEND RESULT DONE *****************************/
  API_send_result_done();

 ?>
