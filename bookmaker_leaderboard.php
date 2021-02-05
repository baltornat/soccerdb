<!DOCTYPE html>
<?php
  session_start();
  require_once('conn.php');
  $dbconn = pg_connect($connection_guest) or die($error_string);
?>
<html>
  <head>
    <title>Soccer DB - Bookmaker Leaderboard</title>
    <link rel="stylesheet" href="css/css_pagine.css" type="text/css">
    <link rel="shortcut icon" href="images/decorations/icona.ico">
  </head>

  <body>
    <div id="container">
    	<div id="header">
           <h1 align="center">Soccer DB - Bookmaker Leaderboard<img src="images/decorations/logo.png" height=30 alt="" align=right /></h1>
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

      <div id="mes-full">
        <p>Bookmaker leaderboard</p>
      </div>

      <div id="classifica">
        <?php
          $result = pg_query($dbconn,
            'SELECT b.name, COUNT(result) AS points
             FROM soccerscheme.bookmaker_leaderboard AS l JOIN soccerscheme.bookmaker AS b ON l.bookmaker = b.bookmaker_id
             WHERE result = lower_quote
             GROUP BY l.bookmaker, b.name
             ORDER BY points DESC
          ');
          if (!$result) {
            echo "An error occurred.\n";
            exit;
          }
        ?>
        <?php
          $row = pg_fetch_row($result);
          $count = 1;
          echo "
            <table>
              <tr>
                <th>Position</th>
                <th>Bookmaker</th>
                <th>Points</th>
              </tr>
              <tr>
              <td>$count</td>
              <td>$row[0]</td>
              <td>$row[1]</td>
              </tr>
          ";
          $count++;
          while ($row = pg_fetch_row($result)) {
            echo "
              <tr>
              <td>$count</td>
              <td>$row[0]</td>
              <td>$row[1]</td>
              </tr>
            ";
            $count++;
          }
          echo "</table>";
        ?>
      </div>
      <div id="footer"><p>Soccer DB&copy; Italy ---------- All rights reserved<img src="images/decorations/logo.png" height=30 alt="" align=right /></p></div>
  </body>
</html>
