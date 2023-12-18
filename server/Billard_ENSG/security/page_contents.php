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
    </div>
  </footer>
<?php } ?>
