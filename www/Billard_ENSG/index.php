<?php
  include_once("../../server/Billard_ENSG/security/init.php");
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Billard ENSG</title>
    <link rel="stylesheet" href="/Billard_ENSG/css/master.css">
    <link rel="stylesheet" href="./style.css">
    <script src="/Billard_ENSG/js/script.js" charset="utf-8"></script>
    <script src="./script.js" charset="utf-8" defer></script>
  </head>
  <body>
    <?php make_header('Acceuil'); ?>
    <div class="content">
      <h1>Statistiques du jour</h1>
      <table name="stat_day">
        <tr>
          <th>Joueur</th>
          <th>Classe</th>
          <th>MJ</th>
          <th>V</th>
          <th>D</th>
          <th>Ratio</th>
          <th>delta elo</th>
          <th>Elo</th>
        </tr>
      </table>
    </div>
  </body>
</html>
