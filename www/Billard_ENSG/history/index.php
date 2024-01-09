<?php
  include_once("../../../server/Billard_ENSG/security/init.php");
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Historique</title>
    <link rel="stylesheet" href="/Billard_ENSG/css/master.css">
    <link rel="stylesheet" href="./style.css">
    <script src="/Billard_ENSG/js/script.js" charset="utf-8"></script>
    <script src="./script.js" charset="utf-8" defer></script>
  </head>
  <body>
    <?php make_header('Historique'); ?>
    <div class="content">
      <form>
        <label for="player">Joueur: </label>
        <input type="text" name="player" value="*">
        <div class="button" onclick="update();">
          Actualiser
        </div>
      </form>
      <table>
        <tr class="title_table">
          <th>Joueur</th>
          <th>Date</th>
          <th>Elo (last)</th>
          <th>Delta Elo</th>
          <th>Elo (new)</th>
        </tr>
      </table>
    </div>
  </body>
</html>
