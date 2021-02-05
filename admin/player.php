<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'administrator') {
    header("location: ../index.php?1=accessed");
  }
  require_once('config.php');
  require_once('../conn.php');
  $dbconn = pg_connect($connection_admin) or die($error_string);
  $sub = $_POST['operation'];
  $player_id = $_POST['player_id'];
  $player_name = $_POST['player_name'];
  $player_birthday = $_POST['player_birthday'];
  $player_height = $_POST['player_height'];
  $player_weight = $_POST['player_weight'];
  if($sub == 'Crea') {
    $sql = 'SELECT soccerscheme.insert_player($1, $2, $3, $4, $5)';
    $res = pg_prepare($dbconn, "insert_player", $sql);
    $res = pg_execute($dbconn, "insert_player", array($player_id, $player_name, $player_birthday, $player_height, $player_weight));
    header("location: admin.php");
  } else if($sub == 'Elimina'){
    $sql = 'SELECT soccerscheme.delete_player($1)';
    $res = pg_prepare($dbconn, "delete_player", $sql);
    $res = pg_execute($dbconn, "delete_player", array($player_id));
    header("location: admin.php");
  } else if($sub == 'Aggiorna'){
    $player_to_update = $_POST['player_to_update'];
    $sql = 'SELECT soccerscheme.update_player($1, $2, $3, $4, $5, $6)';
    $res = pg_prepare($dbconn, "update_player", $sql);
    $res = pg_execute($dbconn, "update_player", array($player_id, $player_name, $player_birthday, $player_height, $player_weight, $player_to_update));
    header("location: admin.php");
  }
?>
