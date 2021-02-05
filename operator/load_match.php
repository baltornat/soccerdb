<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'operator') {
    header("location: ../index.php?1=accessed");
  }
  require_once('../conn.php');
  $dbconn = pg_connect($connection_operator) or die($error_string);
  $username = $_SESSION['username'];
  $league = $_POST['league'];
  $season = $_POST['season'];
  $stage = $_POST['stage'];
  $home_goals = $_POST['home_goals'];
  $away_goals = $_POST['away_goals'];
  $home_team = $_POST['home_team'];
  $away_team = $_POST['away_team'];
  $date = $_POST['date'];
  $result = pg_query($dbconn, "SELECT user_id FROM soccerscheme.operator WHERE name = '$username'");
  $operator = pg_fetch_row($result);
  // ID ULTIMO MATCH INSERITO
  $result = pg_query($dbconn, "SELECT MAX(match_id) FROM soccerscheme.match");
  $match_id = pg_fetch_row($result);
  $match_id[0]++;

  // HOME PLAYERS
  $hp1 = $_POST['hp1'];
  $hp2 = $_POST['hp2'];
  $hp3 = $_POST['hp3'];
  $hp4 = $_POST['hp4'];
  $hp5 = $_POST['hp5'];
  $hp6 = $_POST['hp6'];
  $hp7 = $_POST['hp7'];
  $hp8 = $_POST['hp8'];
  $hp9 = $_POST['hp9'];
  $hp10 = $_POST['hp10'];
  $hp11 = $_POST['hp11'];

  // AWAY PLAYERS
  $ap1 = $_POST['ap1'];
  $ap2 = $_POST['ap2'];
  $ap3 = $_POST['ap3'];
  $ap4 = $_POST['ap4'];
  $ap5 = $_POST['ap5'];
  $ap6 = $_POST['ap6'];
  $ap7 = $_POST['ap7'];
  $ap8 = $_POST['ap8'];
  $ap9 = $_POST['ap9'];
  $ap10 = $_POST['ap10'];
  $ap11 = $_POST['ap11'];

  $sql = 'SELECT soccerscheme.insert_match($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)';
  $res = pg_prepare($dbconn, "insert_match", $sql);
  $res = pg_execute($dbconn, "insert_match", array(
    $match_id[0],
    $season,
    $stage,
    $home_goals,
    $away_goals,
    $home_team,
    $away_team,
    $league,
    $operator[0],
    $date)
  );

  $sql = 'SELECT soccerscheme.insert_squad($1, $2, $3)';
  $res = pg_prepare($dbconn, "insert_squad", $sql);

  for($i=1; $i<12; $i++) {
    $string = "hp"."$i";
    $prova = $$string;
    $result = pg_query($dbconn, "SELECT player_id FROM soccerscheme.player WHERE name = '$prova'");
    $row = pg_fetch_row($result);
    $result = pg_execute($dbconn, "insert_squad", array($home_team, $row[0], $match_id[0]));
  }

  for($i=1; $i<12; $i++) {
    $string = "ap"."$i";
    $prova = $$string;
    $result = pg_query($dbconn, "SELECT player_id FROM soccerscheme.player WHERE name = '$prova'");
    $row = pg_fetch_row($result);
    $result = pg_execute($dbconn, "insert_squad", array($away_team, $row[0], $match_id[0]));
  }
  header("location: match_insert.php");
?>
