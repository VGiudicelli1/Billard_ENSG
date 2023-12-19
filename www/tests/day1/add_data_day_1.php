<?php
  include_once("../../../server/Billard_ENSG/security/init.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Tests</title>
    <link rel="stylesheet" href="/Billard_ENSG/css/master.css">
    <script src="/Billard_ENSG/js/script.js" charset="utf-8"></script>
    <script src="./script_add_data_j1.js" charset="utf-8" defer></script>
    <style media="screen">
      div.log {
        background-color: #DDD;
        margin: 2px;
        padding: 2px;
      }
      div.log.info {
        color: black;
      }
      div.log.error {
        color: red;
      }
      div.log.result {
        color: blue;
      }
      div.log.done {
        color: green;
      }
    </style>
  </head>
  <body>
    <?=make_header("Tests");?>
    <div class="content">
      <h1>Info</h1>
      Ce script ajoute les données des premiers jours, en simulant une utilisation classique du site. Il échouera si la base de données n'est pas vierge.
      <h1>Résultat du script</h1>
    </div>
  </body>
</html>
