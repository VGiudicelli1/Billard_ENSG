<?php
  function diffElo($eA, $eB, $n) {
    return $n * 10 / (1 + pow(10, ($eA-$eB)/400));
  }
  function compute_elo($j1, $j2, $j3, $j4, $data) {
    /*
     * Match classement si au moins un joueur n'est pas classé. Sinon: match elo
     *
     * Match classement:
     *    ∆Elo = ±10 pour les joueurs non classés
     *    ∆Elo = 0 pour les joueurs classés  !!!! Problème dans la vue décompte des victoires
     *
     * Match elo:
     *    ∆Elo calculé entre l'elo du joueur et l'elo moyen de l'équipe adverse,
     *    avec k = 20 / n où n est le nombre de joueurs de l'équipe
     */

    $nV = $j2 <= 0 ? 1 : 2;
    $nD = $j4 <= 0 ? 1 : 2;
    $eloV = $j2 <= 0 ? $data[$j1]["elo"] : ($data[$j1]["elo"] + $data[$j2]["elo"]) / 2;
    $eloD = $j4 <= 0 ? $data[$j3]["elo"] : ($data[$j3]["elo"] + $data[$j4]["elo"]) / 2;

    $data[$j1]["delta_elo"] = diffElo($data[$j1]["elo"], $eloD, $nV);
    if ($j2 > 0) {
      $data[$j2]["delta_elo"] = diffElo($data[$j2]["elo"], $eloD, $nV);
    }
    $data[$j3]["delta_elo"] = -diffElo($data[$j3]["elo"], $eloV, $nD);
    if ($j4 > 0) {
      $data[$j4]["delta_elo"] = -diffElo($data[$j4]["elo"], $eloV, $nD);
    }
    foreach ([
      ["p"=>$j1, "w"=>1],
      ["p"=>$j2, "w"=>1],
      ["p"=>$j3, "w"=>-1],
      ["p"=>$j4, "w"=>-1]
      ] as ["p"=>$p, "w"=>$w]) {
      if ($p <= 0) {
        continue;
      }
      if ($data[$p]["nb_games"] < 3) {
        $data[$p]["delta_elo"] = $w*10;
      }
    }
    return $data;
  }
 ?>
