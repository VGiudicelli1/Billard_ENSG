<?php
  include_once("../../../server/Billard_ENSG/security/init.php");

  list($players, $err) = query(
    "SELECT `player`.`id` AS `id`, `player`.`name` AS `name`,
    `class`.`name` AS `class`, `player`.`elo` AS `elo`
    FROM `player` JOIN `class` ON `player`.`class` = `class`.`id`
    ORDER BY `player`.`elo` DESC");
  if ($err) { $players = []; }

  $GLOBALS["players"] = $players;

  function makeSelectPlayer($name) { ?>
    <select name="<?=$name?>">
      <option value="NULL" selected>Selectionnez un joueur</option>
      <?php foreach ($GLOBALS["players"] as ["id" => $id, "name" => $name, "class"=>$classe, "elo"=>$elo]): ?>
        <option value="<?=$id?>"><?=$name?> (<?=$classe?>) • <?=round($elo*10)/10?></option>
      <?php endforeach; ?>
    </select>
  <?php }
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Ajouter une partie</title>
    <link rel="stylesheet" href="/Billard_ENSG/css/master.css">
    <link rel="stylesheet" href="./style.css">
    <script src="/Billard_ENSG/js/script.js" charset="utf-8"></script>
    <script src="./script.js" charset="utf-8" defer></script>
  </head>
  <body>
    <?php make_header('Ajouter une partie'); ?>
    <div class="content">
      <div class="button" onclick="document.location.href='../add_player'">
        Nouveau joueur
      </div>
      <h1>Ajouter une partie</h1>
      <form>
        <h2>Vainqueur(s)</h2>
        <label for="j1">Joueur 1</label>
        <?=makeSelectPlayer("j1")?>
        <label for="j2">(Joueur 2)</label>
        <?=makeSelectPlayer("j2")?>
        <h2>Vaincu(s)</h2>
        <label for="j3">Joueur 3</label>
        <?=makeSelectPlayer("j3")?>
        <label for="j4">(Joueur 4)</label>
        <?=makeSelectPlayer("j4")?>
        <div class="button" onclick="submit()">
          valider
        </div>
      </form>
    </div>
  </body>
</html>
