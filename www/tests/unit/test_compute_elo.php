<?php
  include_once("../../../server/Billard_ENSG/compute/compute_elo.php");

  $tests = [];

  $tests["test_for_new_players"] = function () {
    $data = [
      1 => ["elo" => 470, "nb_games"=>0],
      2 => ["elo" => 470, "nb_games"=>0],
      3 => ["elo" => 470, "nb_games"=>0],
      4 => ["elo" => 470, "nb_games"=>0]
    ];
    $data_compute = compute_elo(1, 2, 3, 4, $data);
    // test winner +10 if 0 games
    if ($data_compute[1]["delta_elo"] != 10 || $data_compute[2]["delta_elo"] != 10) {
      return false;
    }
    // test losers -10 if 0 games
    if ($data_compute[3]["delta_elo"] != -10 || $data_compute[4]["delta_elo"] != -10) {
      return false;
    }
    return true;
  };

  $tests["test_for_new_players_other_order"] = function () {
    $data = [
      3 => ["elo" => 470, "nb_games"=>0],
      2 => ["elo" => 470, "nb_games"=>0],
      1 => ["elo" => 470, "nb_games"=>0],
      4 => ["elo" => 470, "nb_games"=>0]
    ];
    $data_compute = compute_elo(3, 2, 1, 4, $data);
    // test winner +10 if 0 games
    if ($data_compute[3]["delta_elo"] != 10 || $data_compute[2]["delta_elo"] != 10) {
      return false;
    }
    // test losers -10 if 0 games
    if ($data_compute[1]["delta_elo"] != -10 || $data_compute[4]["delta_elo"] != -10) {
      return false;
    }
    return true;
  };

  /******************************   RUN TESTS    ******************************/
  echo "<table>";
  foreach ($tests as $test_name => $test) {
    $res = "no result";
    $style = "";
    try {
      if (@$test()) {
        $res = "valid";
        $style = "color:green";
      } else {
        $res = "unvalid";
        $style = "color:red";
      }
    } catch (\Exception $e) {
      $res = "error";
      $style = "background-color:red";
    }
    echo "<tr><td>$test_name</td><td style=\"$style\">$res</td></tr>";
  }
  echo "</table>";
 ?>
