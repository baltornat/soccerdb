<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'administrator') {
    header("location: ../index.php?1=accessed");
  }
  require_once('config.php');
  require_once('../conn.php');
  $dbconn = pg_connect($connection_admin) or die($error_string);
  $sub =                  $_POST['operator'];
  $player =               $_POST['player'];
  $attribute_date =       $_POST['player_attribute_date'];
  $overall_rating =       $_POST['player_overall_rating'];
  $potential =            $_POST['player_potential'];
  $preferred_foot =       $_POST['player_preferred_foot'];
  $attacking_work_rate =  $_POST['player_attacking_work_rate'];
  $defensive_work_rate =  $_POST['player_defensive_work_rate'];
  $crossing =             $_POST['player_crossing'];
  $finishing =            $_POST['player_finishing'];
  $heading_accuracy =     $_POST['player_heading_accuracy'];
  $short_passing =        $_POST['player_short_passing'];
  $volleys =              $_POST['player_volleys'];
  $dribbling =            $_POST['player_dribbling'];
  $curve =                $_POST['player_curve'];
  $free_kick_accuracy =   $_POST['player_free_kick_accuracy'];
  $long_passing =         $_POST['player_long_passing'];
  $ball_control =         $_POST['player_ball_control'];
  $acceleration =         $_POST['player_acceleration'];
  $sprint_speed =         $_POST['player_sprint_speed'];
  $agility =              $_POST['player_agility'];
  $reactions =            $_POST['player_reactions'];
  $balance =              $_POST['player_balance'];
  $shot_power =           $_POST['player_shot_power'];
  $jumping =              $_POST['player_jumping'];
  $stamina =              $_POST['player_stamina'];
  $strength =             $_POST['player_strength'];
  $long_shots =           $_POST['player_long_shots'];
  $aggression =           $_POST['player_aggression'];
  $interceptions =        $_POST['player_interceptions'];
  $positioning =          $_POST['player_positioning'];
  $vision =               $_POST['player_vision'];
  $penalties =            $_POST['player_penalties'];
  $marking =              $_POST['player_marking'];
  $standing_tackle =      $_POST['player_standing_tackle'];
  $sliding_tackle =       $_POST['player_sliding_tackle'];
  $gk_diving =            $_POST['player_gk_diving'];
  $gk_handling =          $_POST['player_gk_handling'];
  $gk_kicking =           $_POST['player_gk_kicking'];
  $gk_positioning =       $_POST['player_gk_positioning'];
  $gk_reflexes =          $_POST['player_gk_reflexes'];
  if($sub == 'Crea') {
    $sql = 'SELECT soccerscheme.insert_stats($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12,
      $13, $14, $15, $16, $17, $18, $19, $20, $21, $22, $23,
      $24, $25, $26, $27, $28, $29, $30, $31, $32, $33, $34,
      $35, $36, $37, $38, $39, $40)';
    $res = pg_prepare($dbconn, "insert_stats", $sql);
    $res = pg_execute($dbconn, "insert_stats", array($player, $attribute_date, $overall_rating, $potential, $preferred_foot, $attacking_work_rate,
  		$defensive_work_rate, $crossing, $finishing, $heading_accuracy, $short_passing, $volleys,
  		$dribbling, $curve, $free_kick_accuracy, $long_passing, $ball_control, $acceleration, $sprint_speed,
  		$agility, $reactions, $balance, $shot_power, $jumping, $stamina, $strength, $long_shots,
  		$aggression, $interceptions, $positioning, $vision, $penalties, $marking, $standing_tackle,
  		$sliding_tackle, $gk_diving, $gk_handling, $gk_kicking, $gk_positioning, $gk_reflexes));
    header("location: admin.php");
  } else if($sub == 'Elimina'){
    $sql = 'SELECT soccerscheme.delete_stats($1, $2)';
    $res = pg_prepare($dbconn, "delete_stats", $sql);
    $res = pg_execute($dbconn, "delete_stats", array($player, $attribute_date));
    header("location: admin.php");
  } else if($sub == 'Aggiorna'){
    $player_to_update = $_POST['player_to_update'];
    $sql = 'SELECT soccerscheme.update_stats($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12,
      $13, $14, $15, $16, $17, $18, $19, $20, $21, $22, $23,
      $24, $25, $26, $27, $28, $29, $30, $31, $32, $33, $34,
      $35, $36, $37, $38, $39, $40, $41)';
    $res = pg_prepare($dbconn, "update_stats", $sql);
    $res = pg_execute($dbconn, "update_stats", array($player, $attribute_date, $overall_rating, $potential, $preferred_foot, $attacking_work_rate,
      $defensive_work_rate, $crossing, $finishing, $heading_accuracy, $short_passing, $volleys,
      $dribbling, $curve, $free_kick_accuracy, $long_passing, $ball_control, $acceleration, $sprint_speed,
      $agility, $reactions, $balance, $shot_power, $jumping, $stamina, $strength, $long_shots,
      $aggression, $interceptions, $positioning, $vision, $penalties, $marking, $standing_tackle,
      $sliding_tackle, $gk_diving, $gk_handling, $gk_kicking, $gk_positioning, $gk_reflexes, $player_to_update));
    header("location: admin.php");
  }
?>
