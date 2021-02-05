<?php
	session_start();
	if (!isset($_SESSION['username']) || $_SESSION['type'] != 'partner') {
		header("location: ../index.php?1=accessed");
	}

	function quotes_insert($dbconn, $match_id, $h_quote, $a_quote, $d_quote, $username) {
    $result = pg_query($dbconn, "SELECT user_id FROM soccerscheme.partner WHERE name = '$username'");
    $row = pg_fetch_row($result);
		$sql = 'SELECT soccerscheme.insert_quotes($1, $2, $3, $4, $5)';
		$res = pg_prepare($dbconn, "insert_quotes", $sql);
		$res = pg_execute($dbconn, "insert_quotes", array($h_quote, $a_quote, $d_quote, $match_id, $row[0]));
    return $result;
	}

  function quotes_delete($dbconn, $match_id, $username) {
    $result = pg_query($dbconn, "SELECT user_id FROM soccerscheme.partner WHERE name = '$username'");
    $row = pg_fetch_row($result);
		$sql = 'SELECT soccerscheme.delete_quotes($1, $2)';
		$res = pg_prepare($dbconn, "delete_quotes", $sql);
		$res = pg_execute($dbconn, "delete_quotes", array($match_id, $row[0]));
    return $result;
  }

  function quotes_update($dbconn, $match_id, $h_quote, $a_quote, $d_quote, $username) {
    $result = pg_query($dbconn, "SELECT user_id FROM soccerscheme.partner WHERE name = '$username'");
    $row = pg_fetch_row($result);
		$sql = 'SELECT soccerscheme.update_quotes($1, $2, $3, $4, $5)';
		$res = pg_prepare($dbconn, "update_quotes", $sql);
		$res = pg_execute($dbconn, "update_quotes", array($h_quote, $d_quote, $a_quote, $match_id, $row[0]));
    return $result;
  }
?>
