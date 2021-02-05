<!DOCTYPE html>
<?php
  session_start();
  require_once('conn.php');
  $dbconn = pg_connect($connection_guest) or die($error_string);
  $leagues = pg_query($dbconn, "SELECT league_name FROM soccerscheme.league");
  $seasons = pg_query($dbconn, "SELECT season FROM soccerscheme.match GROUP BY season ORDER BY season ASC");
?>
<html>
  <head>
    <title>Soccer DB - Leaderboard</title>
    <link rel="stylesheet" href="css/css_pagine.css" type="text/css">
    <link rel="shortcut icon" href="images/decorations/icona.ico">
  </head>

  <body>
    <div id="container">
    	<div id="header">
           <h1 align="center">Soccer DB - Leaderboard<img src="images/decorations/logo.png" height=30 alt="" align=right /></h1>
      </div>
      <!-- SE SEI LOGGATO VISUALIZZA IL BOTTONE DI LOGOUT
           SE NON SEI LOGGATO VISUALIZZA IL BOTTONE DI LOGIN -->
      <?php
        if (empty($_SESSION['username'])) {
          echo "
            <div id=\"nav\">
              <a href=\"index.php?1=accessed\">Home</a>
              <a href=\"classifica.php\">Leaderboard</a>
              <a href=\"bookmaker_leaderboard.php\">Bookmaker leaderboard</a>
              <a href=\"match.php\">Matches</a>
              <a href=\"user/login.php\">Login <img src=\"images/decorations/lucchetto.png\" height=\"15\" width=\"15\"></a>
            </div>"
          ;
        } else {
          switch($_SESSION['type']) {
            /* SE LOGGATO COME ADMIN */
            case 'administrator':
              echo "
                <div id=\"nav\">
                  <a href=\"index.php?1=accessed\">Home</a>
                  <a href=\"classifica.php\">Leaderboard</a>
                  <a href=\"bookmaker_leaderboard.php\">Bookmaker leaderboard</a>
                  <a href=\"match.php\">Matches</a>
                  <a href=\"admin/admin.php\">Admin area</a>
                  <a href=\"user/logout.php\">Logout</a>
                </div>"
              ;
              break;
            /* SE LOGGATO COME OPERATORE */
            case 'operator':
              echo "
                <div id=\"nav\">
                  <a href=\"index.php?1=accessed\">Home</a>
                  <a href=\"classifica.php\">Leaderboard</a>
                  <a href=\"bookmaker_leaderboard.php\">Bookmaker leaderboard</a>
                  <a href=\"match.php\">Matches</a>
                  <a href=\"operator/match_insert.php\">Operator area</a>
                  <a href=\"user/logout.php\">Logout</a>
                </div>"
              ;
              break;
            /* SE LOGGATO COME PARTNER */
            case 'partner':
              echo "
                <div id=\"nav\">
                  <a href=\"index.php?1=accessed\">Home</a>
                  <a href=\"classifica.php\">Leaderboard</a>
                  <a href=\"bookmaker_leaderboard.php\">Bookmaker leaderboard</a>
                  <a href=\"match.php\">Matches - Partner</a>
                  <a href=\"user/logout.php\">Logout</a>
                </div>"
              ;
              break;
          }
        }
      ?>

      <div class="item-evi">
        <form action='classifica.php' method='get'>
          <fieldset>
            <legend>Choose league and season:</legend>
            League:<br>
            <?php
              while ($row = pg_fetch_row($leagues)) {
                  echo "<input type='radio' name='league' value='$row[0]'> $row[0]<br>";
              }
            ?>
            <br>Season:<br>
            <?php
              while ($row = pg_fetch_row($seasons)) {
                  echo "<input type='radio' name='season' value='$row[0]'> $row[0]<br>";
              }
            ?>
            <br><input type='submit' value='Show' />
          </fieldset>
        </form>
      </div>

      <div class="item-evi">
        <br><br><img src="images/decorations/coppa.png" width="250" height="170">
      </div>

      <div id="classifica">
        <?php
          require_once('conn.php');
          $dbconn = pg_connect($connection_guest) or die($error_string);
          if (isset($_GET['season'])) {
            $league = $_GET['league'];
            $season = $_GET['season'];
          } else {
            $league = 'Belgium Jupiler League';
            $season = '2008/2009';
          }
          pg_prepare($dbconn, 'Leaderboard',
            'SELECT * FROM soccerscheme.complete_leaderboard WHERE league = $1 AND season = $2');
          $result = pg_execute($dbconn, 'Leaderboard', array($league, $season));
          if (!$result) {
            echo "An error occurred.\n";
            exit;
          }
        ?>
        <?php
          $row = pg_fetch_row($result);
          $count = 1;
          echo "<br><br>
            <h1>League:</h1> <h3>$row[0]</h3>
            <h1>Season:</h1> <h3>$row[1]</h3>
            <table>
              <tr>
                <th>Position</th>
                <th>Team</th>
                <th>Points</th>
              </tr>
              <tr>
                <td>$count</td>
                <td>$row[3]</td>
                <td>$row[4]</td>
              </tr>
          ";
          $count++;
          while ($row = pg_fetch_row($result)) {
            echo "
              <tr>
                <td>$count</td>
                <td>$row[3]</td>
                <td>$row[4]</td>
              </tr>
            ";
            $count++;
          }
          echo "</table>";
        ?>
      </div>
      <div id="footer"><p>Soccer DB&copy; Italy ---------- All rights reserved<img src="images/decorations/logo.png" height=30 alt="" align=right /></p></div>
    </div>
  </body>
</html>
