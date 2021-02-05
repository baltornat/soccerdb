<!DOCTYPE html>
<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'operator') {
    header("location: ../index.php?1=accessed");
  }
  require_once('../conn.php');
  $dbconn = pg_connect($connection_operator) or die($error_string);
  $leagues = pg_query($dbconn, "SELECT league_name FROM soccerscheme.league");
?>
<html>
  <head>
    <title>Soccer DB - Operator Area</title>
    <link rel="stylesheet" href="../css/css_pagine.css" type="text/css">
    <link rel="shortcut icon" href="../images/decorations/icona.ico">
  </head>

  <body>
    <div id="container">
    	<div id="header">
           <h1 align="center">Soccer DB - Operator Area<img src="../images/decorations/logo.png" height=30 alt="" align=right /></h1>
      </div>

    	<div id="nav">
    			<a href="../index.php?1=accessed">Home</a>
          <a href="../classifica.php">Leaderboard</a>
          <a href="../bookmaker_leaderboard.php">Bookmaker leaderboard</a>
          <a href="../match.php">Matches</a>
          <a href="match_insert.php">Operator Area</a>
          <a href="../user/logout.php">Logout</a>
    	</div>

      <div id="classifica">
        <form action='load_match.php' method='post' enctype='multipart/form-data'>
          <fieldset>
            <legend>Insert new match:</legend>
            <div class="item-clas">
              Home team:<br>
              <?php
                $teams = pg_query($dbconn, "SELECT team_id, long_name FROM soccerscheme.team");
                while ($row = pg_fetch_row($teams)) {
                    echo "<input type='radio' name='home_team' value='$row[0]'> $row[1]<br>";
                }
              ?>
              <br>Season:<br>
              <input type='text' name='season' placeholder="e.g. 2008/2009" maxlength='9'><br>
              <br>Stage:<br>
              <input type='text' name='stage'><br><br>
              League:<br>
              <?php
                while ($row = pg_fetch_row($leagues)) {
                    echo "<input type='radio' name='league' value='$row[0]'> $row[0]<br>";
                }
              ?>
            </div>

            <div class="item-clas">
              Away team:<br>
              <?php
                $teams = pg_query($dbconn, "SELECT team_id, long_name FROM soccerscheme.team");
                while ($row = pg_fetch_row($teams)) {
                    echo "<input type='radio' name='away_team' value='$row[0]'> $row[1]<br>";
                }
              ?>
              <br>Number of goals home team:<br>
              <input type='text' name='home_goals'><br>
              <br>Number of goals away team:<br>
              <input type='text' name='away_goals'><br>
              <br>Date:<br>
              <input type='text' name='date' placeholder="e.g. 2008-08-17"><br><br>
              <?php
                if ($_GET['1'] == 'success') {
                  echo '<h2 align="center">Match insert!</h2>';
                } else {
                  if($_GET['1'] == 'failed')
                    echo '<h2 align="center">Error: match not inserted</h2>';
                }
              ?>
            </div>

            <div id="messaggio">
              <p>PLAYERS</p>
            </div>

            <div class="item-clas">
              <?php
                for($i=1; $i<12; $i++) {
                  echo "Home player name $i: <br>";
                  echo "<input type='text' name=\"hp$i\"><br><br>";
                }
              ?>
              <br><input type='submit' value='Insert'/><br><br>
            </div>

            <div class="item-clas">
              <?php
                for($i=1; $i<12; $i++) {
                  echo "Away player name $i: <br>";
                  echo "<input type='text' name=\"ap$i\"><br><br>";
                }
              ?>
            </div>
          </fieldset>
        </form>
      </div>

      <div id="footer"><p>Soccer DB&copy; Italy ---------- All rights reserved<img src="../images/decorations/logo.png" height=30 alt="" align=right /></p></div>


    </div>
  </body>
</html>
