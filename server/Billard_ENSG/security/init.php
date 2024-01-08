<?php
  ob_start(); // include file without displaying characters (like auto \n at the end of file)
  include_once("credentials.php");
  include_once("page_contents.php");
  include_once("constants.php");
  ob_end_clean();

  if (!file_exists(dirname(__FILE__)."/credentials.php")) {
    echo "CRITICAL ERROR: FAIL TO ACCESS TO CREDENTIALS.<br><br>PLEASE RETRY IN A FEW MINUTES.";
    die;
  }

  session_start();

  function send_error($key) {
    switch ($key) {
      case 404: {
        header('HTTP/1.0 404 Not Found');
        exit;
      }
      default: {
        header("HTTP/1.0 {$key}");
        exit;
      }
    }
  }

  function is_in_API() {
    return in_array("API", explode("/", realpath(".")));
  }

  function openPage($path) {
    $abs_path = dirname(__FILE__) . "/../pages/$path";
    $file = fopen($abs_path, "r");
    $replace = [
      "{@curURI}" => base64_encode(substr($_SERVER["REQUEST_URI"], 1)),
    ];
    echo str_replace(
      array_keys($replace),
      array_values($replace),
      fread($file, filesize($abs_path)));
    fclose($file);
    die;
  }

  function connect_db() {
    if (DB_HOST == "localhost") {
      // display full errors only if local/test execution (not in prod site)
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    }
    if (isset($GLOBALS["database"])) {
      return;
    }

    $GLOBALS["database"] = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($GLOBALS["database"]->connect_error) {
      if (is_in_API()) {
        API_send_result_error("database connection error");
      } else {
        echo "CRITICAL ERROR: FAIL TO CONNECT TO DATABASE.<br><br>PLEASE RETRY IN A FEW MINUTES.";
        die;
      }
    }
    mysqli_set_charset($GLOBALS["database"], "utf8");
  }

  function query($sql_query, $types = "", $data = []) {
    $requete = mysqli_prepare($GLOBALS["database"], $sql_query);
    if (gettype($requete) == "boolean") {
      return [[], "unvalid query"];
    }
    if ($types != "") {
      mysqli_stmt_bind_param($requete, $types, ...$data);
    }
    mysqli_stmt_execute($requete);
    $results = [];
    $requete_result = mysqli_stmt_get_result($requete);
    if (gettype($requete_result) != "boolean") {
      foreach ($requete_result as $line) {
        $results[] = $line;
      }
    }
    $error = mysqli_stmt_error($requete);
    return [$results, $error];
  }

  function show_query($query, $types="", $data=[]) {
    echo "<style>
    table { border-collapse: collapse;  border: 2px solid;  margin: 2px; }
    td, th { border: 1px solid;  padding: 10px; }
    table.desc td, table.desc th { text-align: center; }
    </style>";

    echo $query."<br>";
    $resQuery = query($query, $types, $data);
    $err = $resQuery[1]; $res = $resQuery[0];
    if ($err) {
      echo "Query error: ".$err."<br>";
    } elseif (!count($res)) {
      echo "Empty result<br>";
    } else {
      echo "<table class='desc'><tr>";
      // head (keys)
      foreach ($res[0] as $key => $value) { echo "<th>$key</th>"; }
      echo "</tr>";
      // content (values)
      foreach ($res as $line) {
        echo "<tr>";
        foreach ($line as $key => $value) { echo "<td>$value</td>"; }
        echo "</tr>";
      }
      echo "</table>";
    }
    return $resQuery;
  }

  function API_get_data_in($keys_needed = [], $keys_default = []) {
    // get data from php://input, $_GET and $_POST. Send error and die if one of keys from $keys_needed is missing
    $data = [];
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input != NULL) {
      $data += $input;
    } elseif($_GET != NULL) {
      $data += $_GET;
    } elseif($_POST != NULL) {
      $data += $_POST;
    }
    $data += $keys_default;
    $keys_missing = array_diff($keys_needed, array_keys($data));
    if (count($keys_missing)) {
      API_send_result_error("missing needed arguments: " . implode(", ", $keys_missing));
    }
    return $data;
  }

  function format_list($value, $type="intval") {
    if (gettype($value) == "string") {
      $value = explode(",", $value);
    }
    return array_map($type, $value);
  }

  function API_send_result_done($result = []) {
    echo json_encode(["result" => "done"] + $result);
    die;
  }
  function API_send_result_error($reason) {
    echo json_encode(["result" => "undone"] + ["reason" => $reason]);
    die;
  }

  connect_db();
 ?>
