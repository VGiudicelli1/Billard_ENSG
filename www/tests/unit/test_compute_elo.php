<?php
  include_once("../../../server/Billard_ENSG/compute/compute_elo.php");

  $tests = [];

  $tests["test_diff_elo"] = function () {
    return (abs(diffElo(490, 450, 1) - 4.4) < 1e-1
    && abs(diffElo(500, 400, 1) - 3.6) < 1e-1
    && abs(diffElo(500, 500, 1) - 5.0) < 1e-1
    && abs(diffElo(400, 400, 1) - 5.0) < 1e-1
    && abs(diffElo(400, 500, 1) - 6.4) < 1e-1
  );
  };

  $tests["test_for_new_players"] = function () {
    $data = [
      1 => ["elo" => 470, "nb_games"=>0],
      2 => ["elo" => 470, "nb_games"=>0],
      3 => ["elo" => 470, "nb_games"=>0],
      4 => ["elo" => 470, "nb_games"=>0]
    ];
    $data_compute = compute_elo_v2(1, 2, 3, 4, $data);
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

  $tests["test_for_new_players_2v2"] = function () {
    $data = [
      1 => ["elo" => 470, "nb_games"=>0],
      3 => ["elo" => 470, "nb_games"=>0],
    ];
    $data_compute = compute_elo_v2(1, -1, 3, -1, $data);
    // test winner +10 if 0 games
    if ($data_compute[1]["delta_elo"] != 10) {
      return false;
    }
    // test losers -10 if 0 games
    if ($data_compute[3]["delta_elo"] != -10) {
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
    $data_compute = compute_elo_v2(3, 2, 1, 4, $data);
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

  /**********************   TESTS FROM PYTHON COMPUTES   **********************/
  $all_data = [
    //[[600, 3, 2.402], [null, null, null], [400, 3, -7.597], [null, null, null]],
    [[500, 3, 8.9  ], [null, null, null], [460, 3, -8.9  ], [null, null, null]],
    [[460, 3, 11.1 ], [null, null, null], [500, 3, -11.1 ], [null, null, null]],
    [[500, 3, 4.4 ], [480, 3, 4.4], [460, 3, -4.4 ], [440, 3, -4.4]],
  ];

  function testLine($line) {
    $data = [
      1 => ["elo" => $line[0][0], "nb_games"=>$line[0][1]],
      2 => ["elo" => $line[1][0], "nb_games"=>$line[1][1]],
      3 => ["elo" => $line[2][0], "nb_games"=>$line[2][1]],
      4 => ["elo" => $line[3][0], "nb_games"=>$line[3][1]],
    ];

    $data_compute = compute_elo_v2(1, $line[1][0]==null?-1:2, 3, $line[3][0]==null?-1:4, $data);

    $valid = true;
    for ($j=0; $j < 4; $j++) {
      if ($line[$j][0]!=null && abs($data_compute[$j+1]["delta_elo"] - $line[$j][2]) > 1e-1) {
        // debug
        echo "Erreur: joueur $j, elo {$data[$j+1]["elo"]} delta {$data_compute[$j+1]["delta_elo"]} attendu {$line[$j][2]}<br>";
        $valid = false;
      }
    }
    if (!$valid) {
      echo "<br>";
    }
    return $valid;
  }

  for ($i=0; $i < count($all_data); $i++) {
    $line = $all_data[$i];
    $tests["test_data_python_$i"] = function() use($line) {
      return testLine($line);
    };
  }



  /******************************   RUN TESTS    ******************************/
  echo "<table>";
  foreach ($tests as $test_name => $test) {
    $res = "no result";
    $style = "";
    try {
      if ($test()) {
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
