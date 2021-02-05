<!DOCTYPE html>
<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'administrator') {
    header("location: ../index.php?1=accessed");
  }
?>
<html>
  <head>
    <title>Soccer DB - Admin Area</title>
    <link rel="stylesheet" href="../css/css_pagine.css" type="text/css">
    <link rel="shortcut icon" href="../images/decorations/icona.ico">
  </head>

  <body>
    <div id="container">
    	<div id="header">
           <h1 align="center">Soccer DB - Admin Area<img src="../images/decorations/logo.png" height=30 alt="" align=right /></h1>
      </div>

    	<div id="nav">
    			<a href="../index.php?1=accessed">Home</a>
          <a href="../classifica.php">Leaderboard</a>
          <a href="../bookmaker_leaderboard.php">Bookmaker leaderboard</a>
          <a href="../match.php">Matches</a>
          <a href="admin.php">Admin area</a>
          <a href="../user/logout.php">Logout</a>
    	</div>

      <div class="leftcolumn_l1">
        <div class="card">
          <h2>Championship</h2>
          <form action='championship.php' method='post' enctype='multipart/form-data'>
            <fieldset>
              <legend>Create/delete/update a league</legend><br>
              Championship name:<br>
              <input type='text' name='champ_name'><br><br>
              Country:<br>
              <input type="text" name="champ_country"><br><br>
              Championship to update:<br>
              <input type='text' name='champ_to_update' id='champ' placeholder="Only if you update"><br><br>
              <input type='radio' name='operation' value='Update' onclick='disabilitaChamp(1)' checked> Update<br>
              <input type='radio' name='operation' value='Create' onclick='disabilitaChamp(0)'> Create<br>
              <input type='radio' name='operation' value='Delete' onclick='disabilitaChamp(0)'> Delete<br><br>

              <script>
                function disabilitaChamp(x) {
                  if (x == 1){
                    document.getElementById("champ").disabled = false;
                  } else {
                    document.getElementById("champ").disabled = true;
                  }
                }
              </script>

              <input type='submit' value='Send'/><br><br>
            </fieldset>
          </form>
        </div>
      </div>

      <div class="middlecolumn_l1">
        <div class="card">
          <h2>Import CSV</h2>
          <form action='load.php' method='post' enctype='multipart/form-data'>
            <fieldset>
              <legend>Load csv</legend><br>
              Choose the file:<br><br>
              <input type="file" name="upload">
              <br><br><input type='submit' value='Upload' /><br><br>
              <?php
                if ($_GET['1'] == 'success') {
                  echo '<h2 align="center">File loaded!</h2>';
                } else {
                  if($_GET['1'] == 'failed')
                    echo '<h2 align="center">Error: file not loaded</h2>';
                }
              ?>
            </fieldset>
          </form>
        </div>
        <div class="card">
          <h2>Create bookmaker</h2>
          <form action='book.php' method='post' enctype='multipart/form-data'>
            <fieldset>
              <legend>Create/delete bookmaker</legend><br>
              Bookmaker's name:<br>
              <input type="text" name="book"><br><br>
              <input type='radio' name='operation' value='Crea' checked> Create<br>
              <input type='radio' name='operation' value='Elimina'> Delete<br><br>
              <input type='submit' value='Invia' /><br><br>
            </fieldset>
          </form>
        </div>
      </div>

      <div class="rightcolumn_l1">
        <div class="card">
          <h2>Team</h2>
          <form action='team.php' method='post' enctype='multipart/form-data'>
            <fieldset>
              <legend>Create/delete/update a team</legend><br>
              Long name:<br>
              <input type="text" name="team_long_name"><br><br>
              Short name:<br>
              <input type="text" name="team_short_name" placeholder="3 characters" maxlength="3"><br><br>
              Team ID:<br>
              <input type="text" name="team_id"><br><br>
              Team ID to update:<br>
              <input type="text" name="team_to_update" id='team' placeholder="Only if you update"><br><br>
              <input type='radio' name='operation' value='Aggiorna' onclick='disabilitaTeam(1)' checked> Update<br>
              <input type='radio' name='operation' value='Crea' onclick='disabilitaTeam(0)'> Create<br>
              <input type='radio' name='operation' value='Elimina' onclick='disabilitaTeam(0)'> Delete<br><br>

              <script>
                function disabilitaTeam(x) {
                  if (x == 1){
                    document.getElementById("team").disabled = false;
                  } else {
                    document.getElementById("team").disabled = true;
                  }
                }
              </script>

              <input type='submit' value='Send'/><br><br>
            </fieldset>
          </form>
        </div>
        <br><br><br>
      </div>

      <div class="leftcolumn_l2">
        <div class="card">
          <h2>Player</h2>
          <form action='player.php' method='post' enctype='multipart/form-data'>
            <fieldset>
              <legend>Create/delete/update a player</legend><br>
              Player ID:<br>
              <input type="text" name="player_id"><br><br>
              Name and surname:<br>
              <input type="text" name="player_name"><br><br>
              Birthday:<br>
              <input type="text" name="player_birthday" placeholder="e.g. 2008-08-17"><br><br>
              Height:<br>
              <input type="text" name="player_height" placeholder="e.g. 185.0"><br><br>
              Weight:<br>
              <input type="text" name="player_weight" placeholder="e.g. 75.0"><br><br>
              Player ID to update:<br>
              <input type="text" name="player_to_update" id='player' placeholder="Only if you update"><br><br>
              <input type='radio' name='operation' value='Aggiorna' onclick='disabilitaPlayer(1)' checked> Update<br>
              <input type='radio' name='operation' value='Crea' onclick='disabilitaPlayer(0)'> Create<br>
              <input type='radio' name='operation' value='Elimina' onclick='disabilitaPlayer(0)'> Delete<br><br>

              <script>
                function disabilitaPlayer(x) {
                  if (x == 1){
                    document.getElementById("player").disabled = false;
                  } else {
                    document.getElementById("player").disabled = true;
                  }
                }
              </script>

              <input type='submit' value='Send'/><br><br>
            </fieldset>
          </form>
        </div>
      </div>

      <div class="rightcolumn_l2">
        <div class="card">
          <h2>Statistical measurement</h2>
          <form action='stats.php' method='post' enctype='multipart/form-data'>
            <fieldset>
              <legend>Create/delete/update a player's statistic</legend>
              <div style="height:630px; overflow:auto;">
                <div class="leftcolumn_l1"><br>
                  Player ID to update:<br>
                  <input type="text" name="player_to_update" id="playerstats" placeholder="Only if you update"><br><br>
                  Player ID:<br>
                  <input type="text" name="player"><br><br>
                  Overall rating:<br>
                  <input type="text" name="player_overall_rating"><br><br>
                  Preferred foot:<br>
                  <input type="text" name="player_preferred_foot"><br><br>
                  Defensive work rate:<br>
                  <input type="text" name="player_defensive_work_rate"><br><br>
                  Finishing:<br>
                  <input type="text" name="player_finishing"><br><br>
                  Short passing:<br>
                  <input type="text" name="player_short_passing"><br><br>
                  Dribbling:<br>
                  <input type="text" name="player_dribbling"><br><br>
                  Free kick accuracy:<br>
                  <input type="text" name="player_free_kick_accuracy"><br><br>
                  Ball control:<br>
                  <input type="text" name="player_ball_control"><br><br>
                  Sprint speed:<br>
                  <input type="text" name="player_sprint_speed"><br><br>
                  Reactions:<br>
                  <input type="text" name="player_reactions"><br><br>
                  Shot power:<br>
                  <input type="text" name="player_shot_power"><br><br>
                  Stamina:<br>
                  <input type="text" name="player_stamina"><br><br>
                  Long shots:<br>
                  <input type="text" name="player_long_shots"><br><br>
                  Interceptions:<br>
                  <input type="text" name="player_interceptions"><br><br>
                  Vision:<br>
                  <input type="text" name="player_vision"><br><br>
                  Marking:<br>
                  <input type="text" name="player_marking"><br><br>
                  Sliding tackle:<br>
                  <input type="text" name="player_sliding_tackle"><br><br>
                  Goal keeper handling:<br>
                  <input type="text" name="player_gk_handling"><br><br>
                  Goal keeper positioning:<br>
                  <input type="text" name="player_gk_positioning"><br><br>

                  <input type='radio' name='operation' value='Aggiorna' onclick='disabilitaPlayerStats(1)' checked> Update<br>
                  <input type='radio' name='operation' value='Crea' onclick='disabilitaPlayerStats(0)'> Create<br>
                  <input type='radio' name='operation' value='Elimina' onclick='disabilitaPlayerStats(0)'> Delete<br><br>

                  <script>
                    function disabilitaPlayerStats(x) {
                      if (x == 1){
                        document.getElementById("playerstats").disabled = false;
                      } else {
                        document.getElementById("playerstats").disabled = true;
                      }
                    }
                  </script>
                </div>

                <div class="rightcolumn_l1">
                  <div style="padding-left:120px;float:left;"><br>
                    Attribute date:<br>
                    <input type="text" name="player_attribute_date" placeholder="e.g. 2008-08-17"><br><br>
                    Potential:<br>
                    <input type="text" name="player_potential"><br><br>
                    Attacking work rate:<br>
                    <input type="text" name="player_attacking_work_rate"><br><br>
                    Crossing:<br>
                    <input type="text" name="player_crossing"><br><br>
                    Heading accuracy:<br>
                    <input type="text" name="player_heading_accuracy"><br><br>
                    Volleys:<br>
                    <input type="text" name="player_volleys"><br><br>
                    Curve:<br>
                    <input type="text" name="player_curve"><br><br>
                    Long passing:<br>
                    <input type="text" name="player_long_passing"><br><br>
                    Acceleration:<br>
                    <input type="text" name="player_acceleration"><br><br>
                    Agility:<br>
                    <input type="text" name="player_agility"><br><br>
                    Balance:<br>
                    <input type="text" name="player_balance"><br><br>
                    Jumping:<br>
                    <input type="text" name="player_jumping"><br><br>
                    Strength:<br>
                    <input type="text" name="player_strength"><br><br>
                    Aggression:<br>
                    <input type="text" name="player_aggression"><br><br>
                    Positioning:<br>
                    <input type="text" name="player_positioning"><br><br>
                    Penalties:<br>
                    <input type="text" name="player_penalties"><br><br>
                    Standing tackle:<br>
                    <input type="text" name="player_standing_tackle"><br><br>
                    Goal keeper diving:<br>
                    <input type="text" name="player_gk_diving"><br><br>
                    Goal keeper kicking:<br>
                    <input type="text" name="player_gk_kicking"><br><br>
                    Goal keeper reflexes:<br>
                    <input type="text" name="player_gk_reflexes"><br><br><br><br><br><br><br><br>
                    <input type='submit' value='Send'/><br><br>
                  </div>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>

      <div id="footer"><p>Soccer DB&copy; Italy ---------- All rights reserved<img src="../images/decorations/logo.png" height=30 alt="" align=right /></p></div>

    </div>
  </body>
</html>
