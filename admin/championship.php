<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'administrator') {
    header("location: ../index.php?1=accessed");
  }
  require_once('config.php');
  require_once('../conn.php');
  $dbconn = pg_connect($connection_admin) or die($error_string);
  $sub = $_POST['operation'];
  $champ_name = $_POST['champ_name'];
  $champ_country = $_POST['champ_country'];
  if($sub == 'Create') {
    $sql = 'SELECT soccerscheme.insert_league($1, $2)';
    $res = pg_prepare($dbconn, "insert_league", $sql);
    $res = pg_execute($dbconn, "insert_league", array($champ_name, $champ_country));
    header("location: admin.php");
  } else if($sub == 'Delete') {
    $sql = 'SELECT soccerscheme.delete_league($1)';
    $res = pg_prepare($dbconn, "delete_league", $sql);
    $res = pg_execute($dbconn, "delete_league", array($champ_name));
    header("location: admin.php");
  } else if($sub == 'Update') {
    $champ_to_update = $_POST['champ_to_update'];
    $sql = 'SELECT soccerscheme.update_league($1, $2, $3)';
    $res = pg_prepare($dbconn, "update_league", $sql);
    $res = pg_execute($dbconn, "update_league", array($champ_name, $champ_country, $champ_to_update));
    header("location: admin.php");
  }
?>
