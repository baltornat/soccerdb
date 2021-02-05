<?php
  session_start();
  if (!isset($_SESSION['username']) || $_SESSION['type'] != 'administrator') {
    header("location: ../index.php?1=accessed");
  }
  require_once('config.php');
  require_once('../conn.php');
  $dbconn = pg_connect($connection_admin) or die($error_string);
  $filecsv = $_FILES["upload"]["tmp_name"];
  $name = $_FILES["upload"]["name"];

  switch($name) {
    /* SE IL FILE È "player_attribute.csv" */
    case "player_attribute.csv":
      $file = fopen($filecsv, "r");
      if (!$file) {
        header("location: admin.php?1=failed");
      } else {
        fgetcsv($file, 0, ","); // Evito la prima riga (quella degli attributi)
        while($row = fgetcsv($file, 0, ",")) {
          $stats = stats_read_row($row);
          stats_insert($dbconn, $stats);
        }
        fclose($file);
        header("location: admin.php");
      }
      break;

    /* SE IL FILE È "match.csv" */

    case "match.csv":
      $file = fopen($filecsv, "r");
      if (!$file) {
        header("location: admin.php?1=failed");
      } else {
        fgetcsv($file, 0, ","); // Evito la prima riga (quella degli attributi)
        while($row = fgetcsv($file, 0, ",")) {
          /* CARICO LA LEGA IN LEAGUE*/
          $league = league_read_row($row);
          league_insert($dbconn, $league);
          /* CARICO HOME TEAM IN TEAM*/
          $team = team_read_row($row, 7, 6);
          team_insert($dbconn, $team);
          /* CARICO AWAY TEAM IN TEAM*/
          $team = team_read_row($row, 10, 9);
          team_insert($dbconn, $team);
          /* CARICO IL MATCH IN MATCH */
          $match = match_read_row($row);
          match_insert($dbconn, $match);
          /* CARICO I 22 PLAYER IN PLAYER */
          for ($i=0; $i<11; $i++) {
            $player = player_read_row($row, $OFFSET_PLAYERS);
            if ($player['player_id'] != NULL) {
              $p_insert = player_insert($dbconn, $player);
              $squad = squad_read_row($row, 6, $player);
              squad_insert($dbconn, $squad);
            }
            $OFFSET_PLAYERS += $P_ATTRIBUTES;
          }
          for ($i=0; $i<11; $i++) {
            $player = player_read_row($row, $OFFSET_PLAYERS);
            if ($player['player_id'] != NULL) {
              $p_insert = player_insert($dbconn, $player);
              $squad = squad_read_row($row, 9, $player);
              squad_insert($dbconn, $squad);
            }
            $OFFSET_PLAYERS += $P_ATTRIBUTES;
          }
          $OFFSET_PLAYERS = 14; // Resetto OFFSET_PLAYERS a 14
        }
        fclose($file);
        header("location: admin.php");
      }
      break;

    /* SE IL FILE È "bet.csv" */

    case "bet.csv":
      $file = fopen($filecsv, "r");
      if (!$file) {
        header("location: admin.php?1=failed");
      } else {
        /* FUNZIONE CHE PREPARA TUTTE LE QUERY */
        prepare_queries_bet($dbconn);
        $row = fgetcsv($file, 0, ","); // Leggo la prima riga per riempire bet_provider
        $dim = count($row);
        $dim = $dim-1;
        /* CREO UN ARRAY CHE CONTERRA' TUTTI GLI user_id DEI PARTNER DEL CSV */
        $user_id = array();
        $pos = 0;	// indice dell'array user_id
        for ($i=1; $i<$dim; $i+=3) {
          $betp = betp_read_row($row, $i);
          $name = betp_insert($dbconn, $betp);
          /* SCARICO LO user_id ASSOCIATO AL NOME PASSATO COME PARAMETRO */
          $result = pg_execute($dbconn, 'Partner_verify', array($name));
          $user_id[$pos] = pg_fetch_result($result, 0, 0);
          $pos++;
        }
        while($row = fgetcsv($file, 0, ",")) {
          /* INSERISCO LE QUOTE IN QUOTES */
          $pos = 0;
          for ($i=1; $i<count($row); $i+=3) {
            $quote = quote_read_row($row, $i, $user_id[$pos]);
            quote_insert($dbconn, $quote);
            $pos++;
          }
        }
        fclose($file);
        header("location: admin.php");
      }
      break;
  }
?>
