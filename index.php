<!DOCTYPE html>
<?php
  session_start();
?>
<html>
  <head>
    <title>Soccer DB - Homepage</title>
    <link rel="stylesheet" href="css/css_pagine.css" type="text/css">
    <link rel="shortcut icon" href="images/decorations/icona.ico">
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/default.css" type="text/css" media="screen" />
    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.nivo.slider.js"></script>
    <script type="text/javascript">
      $(window).load(function() {
          $('#slider').nivoSlider();
      });
    </script>
  </head>

  <body>
    <script type = "text/javascript">
      $(document).ready(function() {
        $(".container_home").delay(500).fadeIn(2000);
      });
    </script>

    <?php
      /* SE ARRIVA "accessed" ALLORA NON FA LA TRANSIZIONE QUANDO CARICA IL CONTAINER */
      if (isset($_GET['1'])) {
        echo "<div id=\"container\">";
      } else {
        echo "<div class=\"container_home\">";
      }
    ?>
    	<div id="header">
           <h1 align="center">Soccer DB - Online Bet UNIMI<img src="images/decorations/logo.png" height=30 alt="" align=right /></h1>
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

      <div class="slider-wrapper theme-default">
        <div id="slider" class="nivoSlider">
          <img src="images/slider/slider1.jpg" alt="" />
          <img src="images/slider/slider2.jpg" alt="" />
          <img src="images/slider/slider3.jpg" alt="" />
        </div>
      </div>

      <div class="item-evi">
      	<h2>Leaderboard</h2>
      	<p>You can view all the leaderboards from all the leagues of the entire world. Discover which team has won the championship in the season you choose.</p>
      </div>

      <div class="item-evi">
      	<h2>Matches</h2>
      	 <p>You can view all the matches played in every league of the world. Discover the quotes and the best player for every match in the list.</p>
      </div>

      <div id="sidebar">
        <h2>Social</h2>
        <ul>
          <li><a href="#">Follow us on Facebook <img src="images/social/facebook.png" width="15" height="15" alt="" /></a></li>
          <li><a href="#">Follow us on Instagram <img src="images/social/instagram.png" width="15" height="15" alt="" /></a></li>
          <li><a href="#">Follow us on Twitter <img src="images/social/twitter.png" width="15" height="15" alt="" /></a></li>
        </ul>
      </div>

      <div id="mes-full">
      	<p>Features</p>
      </div>

      <div id="content">
        <div class="articolo">
          <img src="images/decorations/fish.png" height="150" width="150"/>
            <h2>Are you a bookmaker?</h2>
            <p>You can register the quotes of a match for your society only by registering an account.</p>
          </div>

          <div class="articolo last">
          	<img src="images/decorations/chiave.png" height="150" width="150"/>
              <h2>Are you an operator?</h2>
              <p>You can register every match of every league on our website.</p>
          </div>
      </div>

      <div class="clear"></div>

      <div id="footer"><p>Soccer DB&copy; Italy ---------- All rights reserved<img src="images/decorations/logo.png" height=30 alt="" align=right /></p></div>

  </body>
</html>
