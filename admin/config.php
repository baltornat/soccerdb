<?php
	$OFFSET_PLAYERS = 14;
	$P_ATTRIBUTES = 5;

	function valid_values($value, $accepted) {
		if (in_array($value, $accepted)) {
	  	return $value;
	  }
	  return NULL;
	}

	function stats_read_row($row){
		$stats = array(
	  	"player"                => empty($row[0])? NULL : $row[0],
	    "attribute_date"        => empty($row[1])? NULL : $row[1],
	    "overall_rating"        => empty($row[2])? NULL : $row[2],
	    "potential"             => empty($row[3])? NULL : $row[3],
	    "preferred_foot"        => empty($row[4])? NULL : $row[4],
	    "attacking_work_rate"   => empty($row[5])? NULL : $row[5],
	    "defensive_work_rate"   => empty($row[6])? NULL : $row[6],
	    "crossing"              => empty($row[7])? NULL : $row[7],
	    "finishing"             => empty($row[8])? NULL : $row[8],
	    "heading_accuracy"      => empty($row[9])? NULL : $row[9],
	    "short_passing"         => empty($row[10])? NULL : $row[10],
	    "volleys"               => empty($row[11])? NULL : $row[11],
	    "dribbling"             => empty($row[12])? NULL : $row[12],
	    "curve"                 => empty($row[13])? NULL : $row[13],
	    "free_kick_accuracy"    => empty($row[14])? NULL : $row[14],
	    "long_passing"          => empty($row[15])? NULL : $row[15],
	    "ball_control"          => empty($row[16])? NULL : $row[16],
	    "acceleration"          => empty($row[17])? NULL : $row[17],
	    "sprint_speed"          => empty($row[18])? NULL : $row[18],
	    "agility"               => empty($row[19])? NULL : $row[19],
	    "reactions"             => empty($row[20])? NULL : $row[20],
	    "balance"               => empty($row[21])? NULL : $row[21],
	    "shot_power"            => empty($row[22])? NULL : $row[22],
	    "jumping"               => empty($row[23])? NULL : $row[23],
	    "stamina"               => empty($row[24])? NULL : $row[24],
	    "strength"              => empty($row[25])? NULL : $row[25],
	    "long_shots"            => empty($row[26])? NULL : $row[26],
	    "aggression"            => empty($row[27])? NULL : $row[27],
	    "interceptions"         => empty($row[28])? NULL : $row[28],
	    "positioning"           => empty($row[29])? NULL : $row[29],
	    "vision"                => empty($row[30])? NULL : $row[30],
	    "penalties"             => empty($row[31])? NULL : $row[31],
	    "marking"               => empty($row[32])? NULL : $row[32],
	    "standing_tackle"       => empty($row[33])? NULL : $row[33],
	    "sliding_tackle"        => empty($row[34])? NULL : $row[34],
	    "gk_diving"             => empty($row[35])? NULL : $row[35],
	    "gk_handling"           => empty($row[36])? NULL : $row[36],
	    "gk_kicking"            => empty($row[37])? NULL : $row[37],
	    "gk_positioning"        => empty($row[38])? NULL : $row[38],
	    "gk_reflexes"           => empty($row[39])? NULL : $row[39]
		);
	  $lmh = array('low', 'medium', 'high');
	  $stats['attacking_work_rate'] = valid_values($stats['attacking_work_rate'], $lmh);
	  $stats['defensive_work_rate'] = valid_values($stats['defensive_work_rate'], $lmh);
	  $stats['preferred_foot'] = valid_values($stats['preferred_foot'], array('left', 'right'));
	  return $stats;
	}

	function stats_insert($dbconn, $stats){
		$sql = 'SELECT soccerscheme.insert_stats($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12,
      $13, $14, $15, $16, $17, $18, $19, $20, $21, $22, $23,
      $24, $25, $26, $27, $28, $29, $30, $31, $32, $33, $34,
      $35, $36, $37, $38, $39, $40)';
    $res = pg_prepare($dbconn, "insert_stats", $sql);
    $res = pg_execute($dbconn, "insert_stats", array(
			$stats['player'],
			$stats['attribute_date'],
			$stats['overall_rating'],
			$stats['potential'],
			$stats['preferred_foot'],
			$stats['attacking_work_rate'],
			$stats['defensive_work_rate'],
			$stats['crossing'],
			$stats['finishing'],
			$stats['heading_accuracy'],
			$stats['short_passing'],
			$stats['volleys'],
			$stats['dribbling'],
			$stats['curve'],
			$stats['free_kick_accuracy'],
			$stats['long_passing'],
			$stats['ball_control'],
			$stats['acceleration'],
			$stats['sprint_speed'],
			$stats['agility'],
			$stats['reactions'],
			$stats['balance'],
			$stats['shot_power'],
			$stats['jumping'],
			$stats['stamina'],
			$stats['strength'],
			$stats['long_shots'],
			$stats['aggression'],
			$stats['interceptions'],
			$stats['positioning'],
			$stats['vision'],
			$stats['penalties'],
			$stats['marking'],
			$stats['standing_tackle'],
			$stats['sliding_tackle'],
			$stats['gk_diving'],
			$stats['gk_handling'],
			$stats['gk_kicking'],
			$stats['gk_positioning'],
			$stats['gk_reflexes'])
		);
    return true;
	}

	function prepare_queries_bet($dbconn) {
		pg_prepare($dbconn,
			'Book_verify',
			'SELECT bookmaker_id FROM soccerscheme.bookmaker WHERE $1 = name'
		);
		pg_prepare($dbconn,
			'Partner_verify',
			'SELECT user_id FROM soccerscheme.partner WHERE name = $1'
		);
	}

	function betp_read_row($row, $from) {
		$string = substr($row[$from], 0, -1);
		$betp = array(
			"name"	=> empty($string)? NULL : $string
		);
		return $betp;
	}

	function betp_insert($dbconn, $betp) {
		$result = pg_execute($dbconn,
			'Book_verify',
			array($betp['name'])
		);
		if (pg_num_rows($result) == 0) {
			$sql = 'SELECT soccerscheme.insert_bookmaker($1)';
			$res = pg_prepare($dbconn, "insert_bookmaker", $sql);
			$res = pg_execute($dbconn, "insert_bookmaker", array($betp['name']));
			$bookmaker_id = pg_execute($dbconn,
				'Book_verify',
				array($betp['name'])
			);
			$id = pg_fetch_row($bookmaker_id);
			$name = 'admin';
			$name = $name.$betp['name'];
			$sql = 'SELECT soccerscheme.insert_partner($1, $2, $3)';
			$res = pg_prepare($dbconn, "insert_partner", $sql);
			$res = pg_execute($dbconn, "insert_partner", array($name, password_hash('password', PASSWORD_DEFAULT), $id[0]));
			return $name;
		}
		return 'admin'.$betp['name'];
	}

	function quote_read_row($row, $from, $value) {
		$quote = array(
			"home"		=> empty($row[$from])? NULL : $row[$from],
			"away"		=> empty($row[$from+1])? NULL : $row[$from+1],
			"draw"		=> empty($row[$from+2])? NULL : $row[$from+2],
			"match"		=> empty($row[0])? NULL : $row[0],
			"partner"	=> isset($value)? $value : NULL
		);
		return $quote;
	}

	function quote_insert($dbconn, $quote) {
		$cont = 0;
		if ($quote['home'] == NULL) {
			$cont++;
		}
		if ($quote['away'] == NULL) {
			$cont++;
		}
		if ($quote['draw'] == NULL) {
			$cont++;
		}
		if ($cont > 2 || $cont == 0) {
			$sql = 'SELECT soccerscheme.insert_quotes($1, $2, $3, $4, $5)';
			$res = pg_prepare($dbconn, "insert_quotes", $sql);
			$res = pg_execute($dbconn, "insert_quotes", array(
				$quote['home'],
				$quote['away'],
				$quote['draw'],
				$quote['match'],
				$quote['partner'])
			);
		}
		return true;
	}

	function league_read_row($row) {
		$league = array(
			"league_name"	=> empty($row[2])? NULL : $row[2],
			"country"     => empty($row[1])? NULL : $row[1]
		);
		return $league;
	}

	function league_insert($dbconn, $league) {
		$sql = 'SELECT soccerscheme.insert_league($1, $2)';
		$res = pg_prepare($dbconn, "insert_league", $sql);
		$res = pg_execute($dbconn, "insert_league", array($league['league_name'], $league['country']));
		return true;
	}

	function team_read_row($row, $from, $type) {
		$team = array(
			"team_id"			=> empty($row[$type])? NULL : $row[$type],
			"long_name"		=> empty($row[$from])? NULL : $row[$from],
			"short_name"	=> empty($row[$from+1])? NULL : $row[$from+1]
		);
		return $team;
	}

	function team_insert($dbconn, $team) {
		$sql = 'SELECT soccerscheme.insert_team($1, $2, $3)';
    $res = pg_prepare($dbconn, "insert_team", $sql);
    $res = pg_execute($dbconn, "insert_team", array($team['team_id'], $team['long_name'], $team['short_name']));
		return true;
	}


	function player_read_row($row, $from) {
		if (empty($row[$from])) {
			return NULL;
		}
		$player = array(
			"player_id"	=> $row[$from],
			"name"			=> empty($row[$from+1])? NULL : $row[$from+1],
			"birthday"	=> empty($row[$from+2])? NULL : $row[$from+2],
			"height"		=> empty($row[$from+3])? NULL : $row[$from+3],
			"weight"		=> empty($row[$from+4])? NULL : $row[$from+4]
		);
		return $player;
	}

	function player_insert($dbconn, $player) {
		$sql = 'SELECT soccerscheme.insert_player($1, $2, $3, $4, $5)';
    $res = pg_prepare($dbconn, "insert_player", $sql);
    $res = pg_execute($dbconn, "insert_player", array($player['player_id'],
			$player['name'],
			$player['birthday'],
			$player['height'],
			$player['weight'])
		);
		return true;
	}


	function match_read_row($row) {
		$match = array(
			"match_id"		=> empty($row[0])? NULL : $row[0],
			"season"			=> empty($row[3])? NULL : $row[3],
			"stage"				=> empty($row[4])? NULL : $row[4],
			"home_goals"	=> isset($row[12])? $row[12] : NULL,
			"away_goals"	=> isset($row[13])? $row[13] : NULL,
			"home_team"		=> empty($row[6])? NULL : $row[6],
			"away_team"		=> empty($row[9])? NULL : $row[9],
			"league"			=> empty($row[2])? NULL : $row[2],
			"operator"		=> 1,
			"date"				=> empty($row[5])? NULL : $row[5]
		);
		return $match;
	}

	function match_insert($dbconn, $match) {
		$sql = 'SELECT soccerscheme.insert_match($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)';
    $res = pg_prepare($dbconn, "insert_match", $sql);
    $res = pg_execute($dbconn, "insert_match", array(
			$match['match_id'],
			$match['season'],
			$match['stage'],
			$match['home_goals'],
			$match['away_goals'],
			$match['home_team'],
			$match['away_team'],
			$match['league'],
			$match['operator'],
			$match['date'])
		);
		return true;
	}

	function squad_read_row($row, $type, $player) {
		$squad = array(
			"team"		=> $row[$type],
			"player"	=> $player['player_id'],
			"match"		=> $row[0]
		);
		return $squad;
	}

	function squad_insert($dbconn, $squad) {
		$sql = 'SELECT soccerscheme.insert_squad($1, $2, $3)';
    $res = pg_prepare($dbconn, "insert_squad", $sql);
    $res = pg_execute($dbconn, "insert_squad", array($squad['team'], $squad['player'], $squad['match']));
		return true;
	}
?>
