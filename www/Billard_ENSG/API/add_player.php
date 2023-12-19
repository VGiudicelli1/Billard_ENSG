<?php include_once("../../../server/Billard_ENSG/security/init.php"); ?>
<?php

  /*****************************  VERIFY DATA IN  *****************************/
  $data_in = API_get_data_in(["name", "class_id"]);

  $name = $data_in["name"];
  $class_id = $data_in["class_id"];

  // minimal accepted name: 3 char
  if (strlen($name) < 3) {
    API_send_result_error(ERROR_WRONG_VALUE | VALUE_NAME);
  }

  // class_id integer
  $class_id = intval($class_id);

  /*****************************  DATABASE QUERY  *****************************/
  list($res, $err) = query("INSERT INTO `player` (`name`, `class`) VALUES (?, ?)", "sd", [$name, $class_id]);
  if ($err) {
    if (str_starts_with($err, "Duplicate entry")) {
      // case: name is already used (non case sensitiv test)
      API_send_result_error(ERROR_WRONG_VALUE | VALUE_NAME);
    } else if (str_starts_with($err, "Cannot add or update a child row: a foreign key constraint fails")) {
      // case: class_id reference fails
      API_send_result_error(ERROR_WRONG_VALUE | VALUE_CLASS);
    } else {
      API_send_result_error(ERROR_INTERN);
    }
  }

  list($res, $err) = query('SELECT LAST_INSERT_ID() AS `id`');
  if ($err || count($res) != 1) {
    API_send_result_error(ERROR_INTERN);
  }

  /*****************************    SEND RESULT   *****************************/
  API_send_result_done(["id_player" => $res[0]["id"]]);
 ?>
