<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'administrator') {
    header("location: ../index.php?1=accessed");
  }
  require_once('config.php');
  require_once('../conn.php');
  $dbconn = pg_connect($connection_admin) or die($error_string);
  $sub = $_POST['operation'];
  $book = $_POST['book'];
  if($sub == 'Crea') {
    $sql = 'SELECT soccerscheme.insert_bookmaker($1)';
    $res = pg_prepare($dbconn, "insert_bookmaker", $sql);
    $res = pg_execute($dbconn, "insert_bookmaker", array($book));
    header("location: admin.php");
  } else if($sub == 'Elimina') {
    $sql = 'SELECT soccerscheme.delete_bookmaker($1)';
    $res = pg_prepare($dbconn, "delete_bookmaker", $sql);
    $res = pg_execute($dbconn, "delete_bookmaker", array($book));
    header("location: admin.php");
  }
?>
