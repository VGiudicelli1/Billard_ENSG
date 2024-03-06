<?php
  include_once("../../../server/Billard_ENSG/security/init.php");
  const ADMIN_PASS = "123ENSG456"; // oui, je sais, c'est pas le top de la sécurité, mais ça suffit
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?=make_head("Moderation")?>
  <body>
    <?php make_header('Moderation'); ?>
    <div class="content">
      <?php if (isset($_POST["password"]) && $_POST["password"] == ADMIN_PASS): ?>
        <style>
          div.content {
            display: grid;
            grid-row-gap: 0.5em;
            grid-template-columns: auto;
          }
        </style>
        <div class="button"><a href="/Billard_ENSG/API/compute_elo.php" target="_blank">recompute all elo</a></div>
        <div class="button"><a href="/Billard_ENSG/API/load_csv.php" target="_blank">load csv</a></div>
        <div class="button"><a target="_blank">download csv</a></div>
        <div class="button">
          <a href="https://phpmyadmin.alwaysdata.com/phpmyadmin" target="_blank">Database</a>
        </div>
      <?php else: ?>
        <form class="" method="post">
          <h2>Authentification requise</h2>
          <label for="password">password</label>
          <input type="password" name="password" value="">
        </form>
      <?php endif; ?>
    </div>
  </body>
</html>
