<?php
  /* AVVIO LA SESSIONE CHE VERRA' AGGIORNATA CON I VALORI PASSATI VIA POST A FINE PAGINA */
  session_start();
  require_once('../conn.php');
  $dbconn = pg_connect($connection_guest) or die($error_string);

  /* PRELEVO I PARAMETRI PASSATI DALLA FORM IN login.html */
  $username = $_POST['username'];
  $password = $_POST['password'];
  $type = $_POST['type'];

  $hash = password_hash($password, PASSWORD_DEFAULT);

	/* PREPARAZIONE QUERY LOGIN OPERATORE */
	pg_prepare($dbconn,
    'Operator_login',
    'SELECT password FROM soccerscheme.operator WHERE name = $1'
  );
  /* PREPARAZIONE QUERY LOGIN PARTNER */
  pg_prepare($dbconn,
    'Partner_login',
    'SELECT password FROM soccerscheme.partner WHERE name = $1'
  );
  /* PREPARAZIONE QUERY LOGIN AMMINISTRATORE */
  pg_prepare($dbconn,
    'Admin_login',
    'SELECT password FROM soccerscheme.administrator WHERE name = $1'
  );

  /* DIFFERENTI QUERY A SECONDA DEL TIPO DI UTENTE */
  switch ($type) {
    case 'operator':
      $result = pg_execute($dbconn, 'Operator_login', array($username));
      break;
    case 'partner':
      $result = pg_execute($dbconn, 'Partner_login', array($username));
      $bookmaker = pg_query($dbconn, "SELECT b.name FROM soccerscheme.partner AS p
                            JOIN soccerscheme.bookmaker AS b ON p.bookmaker = b.bookmaker_id
                            WHERE p.name = '$username'      ");
      break;
    case 'administrator':
      $result = pg_execute($dbconn, 'Admin_login', array($username));
      break;
  }

  /* ANALIZZA IL RISULTATO DELLA QUERY */
  if (pg_num_rows($result) == 0) {
    header("location: login.php?1=rejected&2=incorrect");
  } else {
    $row = pg_fetch_row($result);
    if (password_verify($password, $row[0])) {
      $_SESSION['username']   = $username;
      $_SESSION['password']   = $password;
      $_SESSION['type']       = $type;
      if ($type == 'partner') {
        $row = pg_fetch_row($bookmaker);
        $_SESSION['bookmaker'] = $row[0];
      }
      $_SESSION['start_time'] = time();
      header("location: ../index.php?1=accessed");
    } else {
      header("location: login.php?1=rejected&2=incorrect");
    }
  }
?>
