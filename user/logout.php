<?php
  session_start();
  /* RIMUOVE TUTTE LE VARIABILI DI SESSIONE */
  session_unset();
  /* ELIMINA LA SESSIONE */
  session_destroy();
  header("location: ../index.php?1=accessed");
?>
