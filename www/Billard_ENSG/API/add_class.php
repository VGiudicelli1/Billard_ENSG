<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php

  /*****************************  VERIFY DATA IN  *****************************/
  $data_in = API_get_data_in(["name"]);

  $name = $data_in["name"];

  /*****************************  DATABASE QUERY  *****************************/
  list($res, $err) = query("INSERT INTO `class` (`name`) VALUES (?)", "s", [$name]);
  if ($err) {
    if (str_starts_with($err, "Duplicate entry")) {
      // case: name is already used (non case sensitiv test)
      API_send_result_error(ERROR_WRONG_VALUE | VALUE_NAME);
    } else {
      API_send_result_error(ERROR_INTERN);
    }
  }

  list($res, $err) = query('SELECT LAST_INSERT_ID() AS `id`');
  if ($err || count($res) != 1) {
    API_send_result_error(ERROR_INTERN);
  }

  /*****************************    SEND RESULT   *****************************/
  API_send_result_done(["id_class" => $res[0]["id"]]);
 ?>
