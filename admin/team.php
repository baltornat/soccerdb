<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'administrator') {
    header("location: ../index.php?1=accessed");
  }
  require_once('config.php');
  require_once('../conn.php');
  $dbconn = pg_connect($connection_admin) or die($error_string);
  $sub = $_POST['operation'];
  $team_long_name = $_POST['team_long_name'];
  $team_short_name = $_POST['team_short_name'];
  $team_id = $_POST['team_id'];
  if($sub == 'Crea') {
    $sql = 'SELECT soccerscheme.insert_team($1, $2, $3)';
    $res = pg_prepare($dbconn, "insert_team", $sql);
    $res = pg_execute($dbconn, "insert_team", array($team_id, $team_long_name, $team_short_name));
    header("location: admin.php");
  } else if($sub == 'Elimina'){
    $sql = 'SELECT soccerscheme.delete_team($1)';
    $res = pg_prepare($dbconn, "delete_team", $sql);
    $res = pg_execute($dbconn, "delete_team", array($team_id));
    header("location: admin.php");
  } else if($sub == 'Aggiorna'){
    $team_to_update = $_POST['team_to_update'];
    $sql = 'SELECT soccerscheme.update_team($1, $2, $3, $4)';
    $res = pg_prepare($dbconn, "update_team", $sql);
    $res = pg_execute($dbconn, "update_team", array($team_id, $team_long_name, $team_short_name, $team_to_update));
    header("location: admin.php");
  }
?>
