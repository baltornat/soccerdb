<!DOCTYPE html>
<?php
  session_start();
  require_once('conn.php');
  require_once('match_queries.php');
  $dbconn = pg_connect($connection_guest) or die($error_string);
  $leagues = pg_query($dbconn, "SELECT league_name FROM soccerscheme.league");
  $seasons = pg_query($dbconn, "SELECT season FROM soccerscheme.match GROUP BY season ORDER BY season ASC");
  pg_close($dbconn);
?>
<html>
  <head>
    <title>Soccer DB - Match</title>
    <link rel="stylesheet" href="css/css_pagine.css" type="text/css">
    <link rel="shortcut icon" href="images/decorations/icona.ico">
  </head>

  <body>
    <div id="container">
    	<div id="header">
           <h1 align="center">Soccer DB - Look at all the matches<img src="images/decorations/logo.png" height=30 alt="" align=right /></h1>
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

      <div class="leftcolumn_l1">
        <div class="card">
          <h2>League and season</h2>
          <form action='match.php' method='get'>
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
      </div>

      <div class="middlecolumn_l1">
        <div class="card">
          <div style="text-align:center">
            <h2>Match informations</h2>
            <?php
              if(!isset($_POST['match'])) {
                if(!isset($_SESSION['match'])) {
                  $match_id = 1;
                  $_SESSION['match'] = $match_id;
                }
                $match_id = $_SESSION['match'];
              } else {
                $match_id = $_POST['match'];
                $_SESSION['match'] = $match_id;
              }
              if($_POST['sub'] == 'Delete') {
                $username = $_SESSION['username'];
                $dbconn = pg_connect($connection_operator) or die($error_string);
                $result = pg_query($dbconn, "SELECT user_id FROM soccerscheme.operator WHERE name = '$username'");
                $row = pg_fetch_row($result);
                pg_prepare($dbconn, 'Delete_match', 'DELETE FROM soccerscheme.match WHERE match_id = $1 AND operator = $2 RETURNING *');
                $result = pg_execute($dbconn, 'Delete_match', array($match_id, $row[0]));
                echo pg_result_error($result);
                echo pg_last_error($dbconn);
                pg_close($dbconn);
                if(pg_num_rows($result) == 0) {
                  echo "You are not allowed!";
                } else {
                  echo "Match deleted";
                }
              } else {
                $dbconn = pg_connect($connection_guest) or die($error_string);
                $result1 = pg_query($dbconn, "SELECT * FROM soccerscheme.match WHERE match_id = $match_id");
                $row = pg_fetch_row($result1);
                echo "<b>Match ID:</b>  $row[0] <b>- Stage:</b>  $row[2]<br>";
                $result2 = pg_query($dbconn, "SELECT h.long_name, a.long_name
                  FROM soccerscheme.team AS h JOIN soccerscheme.match ON h.team_id=match.home_team
                  JOIN soccerscheme.team AS a ON match.away_team=a.team_id
                  WHERE match_id = $match_id");
                pg_close($dbconn);
                $res = pg_fetch_row($result2);
                echo "<b>Home team:</b>  $row[5] $res[0]<br>";
                echo "<b>Away team:</b>  $row[6] $res[1]<br>";
                echo "<b>Operatore:</b>  $row[8]<br>";
                echo "<b>Data:</b>  $row[9]<br>";
                $home_team = $row[5];
                $away_team = $row[6];
                echo "<br><b>Score:</b><br>";
                echo "<h1>$row[3] - $row[4]</h1>";
              }
            ?>
          </div>
        </div>
      </div>

      <div class="rightcolumn_l1">
        <div class="card">
          <?php
            if (isset($_GET['season'])) {
              $_SESSION['league'] = $_GET['league'];
              $_SESSION['season'] = $_GET['season'];
            } else {
              if (!isset($_SESSION['season'])) {
                $_SESSION['league'] = 'Belgium Jupiler League';
                $_SESSION['season'] = '2008/2009';
              }
            }
            $league = $_SESSION['league'];
            $season = $_SESSION['season'];
            echo "<br><br><h1>League:</h1> <h3>$league</h3>";
            echo "<h1>Season:</h1> <h3>$season</h3>";
          ?>
        </div>
      </div>

      <div id="classifica">
        <?php
          $dbconn = pg_connect($connection_guest) or die($error_string);
          $result = pg_query($dbconn, "SELECT h.long_name, match.home_goals, match.away_goals, a.long_name, match.match_id
            FROM soccerscheme.team AS h JOIN soccerscheme.match ON h.team_id=match.home_team
            JOIN soccerscheme.team AS a ON match.away_team=a.team_id
            WHERE match.season = '$season' AND match.league = '$league'");
          pg_close($dbconn);
          $row = pg_fetch_row($result);
          $count = 1;
          echo "
          <div style=\"width:920px; height:500px; overflow:auto;\">
            <table>
              <tr>
                <th>Match No.</th>
                <th>Home Team</th>
                <th>Score</th>
                <th>Away Team</th>
                <th>Operations</th>
              </tr>
              <form action='match.php' method='post'>
              <tr>
                <td><input type='radio' name='match' value='$row[4]'>$count</td>
                <td>$row[0]</td>
                <td>$row[1] - $row[2]</td>
                <td>$row[3]</td>
                <th><input type='submit' name='sub' value='Show'/>";
          if (isset($_SESSION['username']) && $_SESSION['type'] == 'operator') {
            echo "  <input type='submit' name='sub' value='Delete'\></th></tr>";
          }
          while ($row = pg_fetch_row($result)) {
            $count++;
            echo "
              <tr>
              <td><input type='radio' name='match' value='$row[4]'>$count</td>
              <td>$row[0]</td>
              <td>$row[1] - $row[2]</td>
              <td>$row[3]</td>
              <th><input type='submit' name='sub' value='Show'/>";
            if (isset($_SESSION['username']) && $_SESSION['type'] == 'operator') {
              echo "  <input type='submit' name='sub' value='Delete'\></th></tr>";
            }
          }
          echo "</table>
          </div>";
        ?>
        </form>
      </div>

      <div id="mes-full">
        <p>Quotes of the match</p>
      </div>


      <div id="classifica">
        <?php
          $dbconn = pg_connect($connection_guest) or die($error_string);
          $result = dump_quotes($dbconn, $match_id);
          pg_close($dbconn);
          if (pg_num_rows($result) == 0) {
            echo "Table not available, choose a match before doing other operations";
          }
          else {
            echo "
            <div style=\"width:920px;\">
              <table>
                <tr>
                  <th>Bookmaker</th>
                  <th>Home quote</th>
                  <th>Away quote</th>
                  <th>Draw quote</th>
                </tr>
            ";
            while ($quote = pg_fetch_row($result)) {
              echo "<tr>
                <td>$quote[0]</td>
                <td>$quote[1]</td>
                <td>$quote[2]</td>
                <td>$quote[3]</td>
              </tr>";
            }
            echo "</table>
            </div>";
          }
          if (isset($_SESSION['username']) && $_SESSION['type'] == 'partner') {
            echo "<form action='partner/partner.php' method='post'>
                    <div class=\"leftcolumn_l1\">
                      <div class=\"card\">
            ";
          ?>
            <h3 align="center"><br><br><br>Partner of<br>
          <?php
            echo $_SESSION['bookmaker'];
          ?>
            </h3>
          <?php
            echo"      </div>
                    </div>
                    <div class=\"middlecolumn_l1\">
                      <div class=\"card\">
                        Home quote:<br>
                        <input type=\"text\" name=\"h_quote\" id=\"t1\"><br><br>
                        Away quote:<br>
                        <input type=\"text\" name=\"a_quote\" id=\"t2\"><br><br>
                        Draw quote:<br>
                        <input type=\"text\" name=\"d_quote\" id=\"t3\"><br><br>
                        <input type=\"hidden\" name=\"match_id\" value=$match_id>
                      </div>
                    </div>
                    <div class=\"rightcolumn_l1\">
                      <div class=\"card\">
                        <br><br>
                        <input type=\"radio\" name=\"op\" value=\"Aggiorna\" onclick=\"disabilitaText(1)\" checked> Update<br>
                        <input type=\"radio\" name=\"op\" value=\"Crea\" onclick=\"disabilitaText(1)\"> Create<br>
                        <input type=\"radio\" name=\"op\" value=\"Elimina\" onclick=\"disabilitaText(0)\"> Delete<br><br>
                        <input type='submit' name='sub' value='Invia'\>
                      </div>
                    </div>
                  </form>
            ";

          }
        ?>

        <script>
          function disabilitaText(x) {
            if (x == 1){
              document.getElementById("t1").disabled = false;
              document.getElementById("t2").disabled = false;
              document.getElementById("t3").disabled = false;
            } else {
              document.getElementById("t1").disabled = true;
              document.getElementById("t2").disabled = true;
              document.getElementById("t3").disabled = true;
            }
          }
        </script>

      </div>


      <div id="mes-full">
        <p>Home team squad</p>
      </div>

      <!-- BEST HOME PLAYER -->
      <div id="classifica">
        <?php
          $dbconn = pg_connect($connection_guest) or die($error_string);
          $result = best_home_player($dbconn, $match_id);
          if (pg_num_rows($result) != 1) {
            echo "Best home player not available<br><br>";
          }
          else {
            $row = pg_fetch_row($result);
            echo "<h3>Best player: $row[0]</h3>";
          }
          $result = squad_home_team($dbconn, $match_id, $home_team);
          pg_close($dbconn);
          if (pg_num_rows($result) == 0) {
            echo "Table not available, choose a match before doing other operations";
          }
          else {
            echo "
            <div style=\"width:920px; height:500px; overflow:auto;\">
              <table>
                <tr>
                  <th>Name</th>
                  <th>Rating</th>
                  <th>Potential</th>
                  <th>Preferred foot</th>
                  <th>Attacking rate</th>
                  <th>Defensive rate</th>
                  <th>Crossing</th>
                  <th>Finishing</th>
                  <th>Heading acc.</th>
                  <th>Short pass</th>
                  <th>Volleys</th>
                  <th>Dribbling</th>
                  <th>Curve</th>
                  <th>Free kick acc.</th>
                  <th>Long pass</th>
                  <th>Control</th>
                  <th>Acceleration</th>
                  <th>Sprint</th>
                  <th>Agility</th>
                  <th>Reactions</th>
                  <th>Balance</th>
                  <th>Shot power</th>
                  <th>Jump</th>
                  <th>Stamina</th>
                  <th>Strength</th>
                  <th>Long shots</th>
                  <th>Aggression</th>
                  <th>Interceptions</th>
                  <th>Positioning</th>
                  <th>Vision</th>
                  <th>Penalties</th>
                  <th>Marking</th>
                  <th>Standing tackle</th>
                  <th>Sliding tackle</th>
                  <th>GK diving</th>
                  <th>GK handling</th>
                  <th>GK kicking</th>
                  <th>GK positioning</th>
                  <th>GK reflexes</th>
                </tr>
            ";
            while ($formazione = pg_fetch_row($result)) {
              echo "<tr>
                <td>$formazione[0]</td>
                <td>$formazione[1]</td>
                <td>$formazione[2]</td>
                <td>$formazione[3]</td>
                <td>$formazione[4]</td>
                <td>$formazione[5]</td>
                <td>$formazione[6]</td>
                <td>$formazione[7]</td>
                <td>$formazione[8]</td>
                <td>$formazione[9]</td>
                <td>$formazione[10]</td>
                <td>$formazione[11]</td>
                <td>$formazione[12]</td>
                <td>$formazione[13]</td>
                <td>$formazione[14]</td>
                <td>$formazione[15]</td>
                <td>$formazione[16]</td>
                <td>$formazione[17]</td>
                <td>$formazione[18]</td>
                <td>$formazione[19]</td>
                <td>$formazione[20]</td>
                <td>$formazione[21]</td>
                <td>$formazione[22]</td>
                <td>$formazione[23]</td>
                <td>$formazione[24]</td>
                <td>$formazione[25]</td>
                <td>$formazione[26]</td>
                <td>$formazione[27]</td>
                <td>$formazione[28]</td>
                <td>$formazione[29]</td>
                <td>$formazione[30]</td>
                <td>$formazione[31]</td>
                <td>$formazione[32]</td>
                <td>$formazione[33]</td>
                <td>$formazione[34]</td>
                <td>$formazione[35]</td>
                <td>$formazione[36]</td>
                <td>$formazione[37]</td>
                <td>$formazione[38]</td>
              </tr>";
            }
            echo "</table>
            </div>";
          }
        ?>
      </div>

      <div id="mes-full">
        <p>Away team squad</p>
      </div>

      <!-- BEST AWAY PLAYER -->
      <div id="classifica">
        <?php
          $dbconn = pg_connect($connection_guest) or die($error_string);
          $result = best_away_player($dbconn, $match_id);
          if (pg_num_rows($result) != 1) {
            echo "Best away player not available<br><br>";
          }
          else {
            $row = pg_fetch_row($result);
            echo "<h3>Best player: $row[0]</h3>";
          }
          $result = squad_away_team($dbconn, $match_id, $away_team);
          pg_close($dbconn);
          if (pg_num_rows($result) == 0) {
            echo "Table not available, choose a match before doing other operations";
          }
          else {
            echo "
            <div style=\"width:920px; height:500px; overflow:auto;\">
              <table>
                <tr>
                  <th>Name</th>
                  <th>Rating</th>
                  <th>Potential</th>
                  <th>Preferred foot</th>
                  <th>Attacking rate</th>
                  <th>Defensive rate</th>
                  <th>Crossing</th>
                  <th>Finishing</th>
                  <th>Heading acc.</th>
                  <th>Short pass</th>
                  <th>Volleys</th>
                  <th>Dribbling</th>
                  <th>Curve</th>
                  <th>Free kick acc.</th>
                  <th>Long pass</th>
                  <th>Control</th>
                  <th>Acceleration</th>
                  <th>Sprint</th>
                  <th>Agility</th>
                  <th>Reactions</th>
                  <th>Balance</th>
                  <th>Shot power</th>
                  <th>Jump</th>
                  <th>Stamina</th>
                  <th>Strength</th>
                  <th>Long shots</th>
                  <th>Aggression</th>
                  <th>Interceptions</th>
                  <th>Positioning</th>
                  <th>Vision</th>
                  <th>Penalties</th>
                  <th>Marking</th>
                  <th>Standing tackle</th>
                  <th>Sliding tackle</th>
                  <th>GK diving</th>
                  <th>GK handling</th>
                  <th>GK kicking</th>
                  <th>GK positioning</th>
                  <th>GK reflexes</th>
                </tr>
            ";
            while ($formazione = pg_fetch_row($result)) {
              echo "<tr>
                <td>$formazione[0]</td>
                <td>$formazione[1]</td>
                <td>$formazione[2]</td>
                <td>$formazione[3]</td>
                <td>$formazione[4]</td>
                <td>$formazione[5]</td>
                <td>$formazione[6]</td>
                <td>$formazione[7]</td>
                <td>$formazione[8]</td>
                <td>$formazione[9]</td>
                <td>$formazione[10]</td>
                <td>$formazione[11]</td>
                <td>$formazione[12]</td>
                <td>$formazione[13]</td>
                <td>$formazione[14]</td>
                <td>$formazione[15]</td>
                <td>$formazione[16]</td>
                <td>$formazione[17]</td>
                <td>$formazione[18]</td>
                <td>$formazione[19]</td>
                <td>$formazione[20]</td>
                <td>$formazione[21]</td>
                <td>$formazione[22]</td>
                <td>$formazione[23]</td>
                <td>$formazione[24]</td>
                <td>$formazione[25]</td>
                <td>$formazione[26]</td>
                <td>$formazione[27]</td>
                <td>$formazione[28]</td>
                <td>$formazione[29]</td>
                <td>$formazione[30]</td>
                <td>$formazione[31]</td>
                <td>$formazione[32]</td>
                <td>$formazione[33]</td>
                <td>$formazione[34]</td>
                <td>$formazione[35]</td>
                <td>$formazione[36]</td>
                <td>$formazione[37]</td>
                <td>$formazione[38]</td>
              </tr>";
            }
            echo "</table>
            </div>";
          }
        ?>
      </div>

      <div id="footer"><p>Soccer DB&copy; Italy ---------- All rights reserved<img src="images/decorations/logo.png" height=30 alt="" align=right /></p></div>
  </body>
</html>
