<?php
  include_once("../../../server/Billard_ENSG/security/init.php");

  list($classes, $err) = query("SELECT `id`, `name` FROM `class`");
  if ($err) { $classes = []; }
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Ajouter un joueur</title>
    <link rel="stylesheet" href="/Billard_ENSG/css/master.css">
    <link rel="stylesheet" href="./style.css">
    <script src="/Billard_ENSG/js/script.js" charset="utf-8"></script>
    <script src="./script.js" charset="utf-8" defer></script>
  </head>
  <body>
    <?php make_header('Ajouter un joueur'); ?>
    <div class="content">
      <h1>Ajouter un joueur</h1>
      <form>
        <label for="name">Nom du joueur</label>
        <input type="text" name="name" value="" title="Minimum 3 caractères">
        <label for="classe">Classe</label>
        <select name="classe">
          <?php foreach ($classes as ["id" => $id, "name" => $name]): ?>
            <option value="<?=$id?>"><?=$name?></option>
          <?php endforeach; ?>
        </select>
        <div class="button" onclick="submit()">
          valider
        </div>
      </form>
    </div>
  </body>
</html>
