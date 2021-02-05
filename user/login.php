<!DOCTYPE html>
<html>
  <head>
    <title>Soccer DB - Login</title>
    <link rel="stylesheet" href="../css/css_pagine.css" type="text/css">
    <link rel="shortcut icon" href="../images/decorations/icona.ico">
  </head>

  <body>
    <div id="container">
    	<div id="header">
           <h1 align="center">Soccer DB - Login or create user<img src="../images/decorations/logo.png" height=30 alt="" align=right /></h1>
      </div>

    	<div id="nav">
    			<a href="../index.php?1=accessed">Home</a>
          <a href="../classifica.php">Leaderboard</a>
          <a href="../bookmaker_leaderboard.php">Bookmaker leaderboard</a>
          <a href="../match.php">Matches</a>
    	</div>

      <div class="item-evi">
        <br>
        <form action='autentica.php' method='post'>
          <fieldset>
            <legend>Personal informations:</legend>
            Username:<br>
            <input type='text' name='username' maxlength='30'><br>
            Password:<br>
            <input type='password' name='password'><br><br>

            <input type='radio' name='type' value='operator' checked> Operator<br>
            <input type='radio' name='type' value='partner'> Partner<br>
            <input type='radio' name='type' value='administrator'> Administrator<br><br>

            <input type='submit' value='Login' />
          </fieldset>
        </form>
      </div>

      <div class="item-evi">
        <br><br><h1 align="center">New user?</h1><br>
        <img src="../images/decorations/freccia.png" width=100% height=100% alt="" /><br><br>
        <?php
          if ($_GET['1'] == 'success') {
            echo '<h2 align="center">Account registered successfully</h2>';
          } else {
            if ($_GET['2'] == 'not_new') {
              echo '<h2 align="center">Username already taken</h2>';
            } else {
              if ($_GET['2'] == 'bookmaker') {
                echo '<h2 align="center">Bookmaker not registered</h2>';
              } else {
                if ($_GET['2'] == 'incorrect')
                  echo '<h2 align="center">Wrong username or password</h2>';
              }
            }
          }
        ?>
      </div>

      <div class="item-evi">
        <form action='registra.php' method='post'>
          <fieldset>
            <legend>Registration:</legend>
            Username:<br>
            <input type='text' name='username' placeholder="30 Characters" maxlength='30'><br>
            Password:<br>
            <input type='password' name='password' placeholder="Use special characters"><br>
            Bookmaker:<br>
            <input type='text' name='bookmaker' id='bookmaker' placeholder="Only for partners"><br><br>

            <input type='radio' name='type' value='partner' onclick='disabilita(1)' checked> Partner<br>
            <input type='radio' name='type' value='operator' onclick='disabilita(0)'> Operator<br>
            <input type='radio' name='type' value='administrator' onclick='disabilita(0)'> Administrator<br><br>

            <script>
              function disabilita(x) {
                if (x == 1){
                  document.getElementById("bookmaker").disabled = false;
                } else {
                  document.getElementById("bookmaker").disabled = true;
                }
              }
            </script>

            <input type='submit' value='Register'/>
          </fieldset>
        </form>
      </div>

      <div id="footer"><p>Soccer DB&copy; Italy ---------- All rights reserved<img src="../images/decorations/logo.png" height=30 alt="" align=right /></p></div>


    </div>
  </body>
</html>
