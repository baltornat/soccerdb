<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'partner') {
    header("location: ../index.php?1=accessed");
  }
  require_once('config.php');
  require_once('../conn.php');
  $dbconn = pg_connect($connection_partner) or die($error_string);
  $username = $_SESSION['username'];
  $op = $_POST['op'];
  $h_quote = $_POST['h_quote'];
  $a_quote = $_POST['a_quote'];
  $d_quote = $_POST['d_quote'];
  $match_id = $_POST['match_id'];
  if ($op == 'Crea') {
    if ($h_quote == NULL || $a_quote == NULL || $d_quote == NULL) {
      header("location: ../match.php");
    }
    $result = quotes_insert($dbconn, $match_id, $h_quote, $a_quote, $d_quote, $username);
    header("location: ../match.php");
  } else if($op == 'Elimina') {
    $result = quotes_delete($dbconn, $match_id, $username);
    header("location: ../match.php");
  } else if($op == 'Aggiorna') {
    if ($h_quote == NULL || $a_quote == NULL || $d_quote == NULL) {
      header("location: ../match.php");
    }
    $result = quotes_update($dbconn, $match_id, $h_quote, $a_quote, $d_quote, $username);
    header("location: ../match.php");
  }
?>
