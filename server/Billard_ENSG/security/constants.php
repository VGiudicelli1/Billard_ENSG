<?php

  /*****************************      ERRORS      *****************************/
  const ERROR_NOT_FOUND     = 1;
  const ERROR_INTERN        = 2;
  const ERROR_WRONG_VALUE   = 4;
  const ERROR_NOT_DEVELOPED = 8;
  const ERROR_WRONG_MODE    = 16;


  /*****************************  TO JAVASCRIPT   *****************************/

  function make_script_constants() { ?>
    <script type="text/javascript">
      const ERROR_NOT_FOUND     = <?=ERROR_NOT_FOUND    ?>;
      const ERROR_INTERN        = <?=ERROR_INTERN       ?>;
      const ERROR_WRONG_VALUE   = <?=ERROR_WRONG_VALUE  ?>;
      const ERROR_NOT_DEVELOPED = <?=ERROR_NOT_DEVELOPED?>;
      const ERROR_WRONG_MODE    = <?=ERROR_WRONG_VALUE  ?>;
    </script>
  <?php }
 ?>
