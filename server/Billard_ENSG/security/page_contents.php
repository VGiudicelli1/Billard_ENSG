<?php function make_head($title) { ?>
  <head>
    <meta charset="utf-8">
    <title><?=$title?></title>
    <link rel="shortcut icon" href="/Billard_ENSG/image/flavicon.png">
    <link rel="stylesheet" href="/Billard_ENSG/css/master.css">
    <link rel="stylesheet" href="./style.css">
    <script src="/Billard_ENSG/js/script.js" charset="utf-8"></script>
    <script src="./script.js" charset="utf-8" defer></script>
  </head>
<?php } ?>
<?php function make_header($title) {
  make_script_constants(); ?>
  <header>
    <div class="button home" onclick="window.location.href='/Billard_ENSG/'">Home</div>
    <div class="site_name">Billard ENSG</div>
    <div class="title"><?=$title?></div>
  </header>
  <footer>
    <div class="footer-content">
      <a href="mailto:vincent.giudicelli@free.fr">contact</a>
      <a href="https://github.com/VGiudicelli1/Billard_ENSG"><img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" alt="">contribuer</a>
    </div>
  </footer>
<?php } ?>
