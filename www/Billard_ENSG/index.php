<?php
  include_once("../../server/Billard_ENSG/security/init.php");
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?=make_head("Billard ENSG")?>
  <body>
    <?php make_header('Accueil'); ?>
    <div class="content">
      <div class="button" onclick="document.location.href='./add_game'">
        Nouvelle partie
      </div>
      <div class="button" onclick="document.location.href='./history'">
        Historique
      </div>
      <h1>Aujourd'hui</h1>
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
      <h1>Cette semaine</h1>
      <table name="stat_week">
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
      <h1>Total</h1>
      <table name="stat_all">
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
