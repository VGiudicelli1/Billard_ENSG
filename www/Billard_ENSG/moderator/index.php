<?php
  include_once("../../../server/Billard_ENSG/security/init.php");
  const ADMIN_PASS = "123ENSG456"; // oui, je sais, c'est pas le top de la sécurité, mais ça suffit
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link rel="stylesheet" href="/Billard_ENSG/css/master.css">
    <link rel="stylesheet" href="./style.css">
    <script src="/Billard_ENSG/js/script.js" charset="utf-8"></script>
    <script src="./script.js" charset="utf-8" defer></script>
  </head>
  <body>
    <?php make_header('Admin'); ?>
    <div class="content">
      <?php if (isset($_POST["password"]) && $_POST["password"] == ADMIN_PASS): ?>
        <style>
          div.content {
            display: grid;
            grid-row-gap: 0.5em;
            grid-template-columns: auto;
          }
        </style>
        <div class="button"><a href="/Billard_ENSG/API/compute_elo.php">recompute all elo</a></div>
        <div class="button"><a href="/Billard_ENSG/API/load_csv.php">load csv</a></div>
        <div class="button"><a>download csv</a></div>
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
