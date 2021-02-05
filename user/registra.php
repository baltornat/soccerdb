<?php
  /* AVVIO LA SESSIONE CHE VERRA' AGGIORNATA CON I VALORI PASSATI VIA POST A FINE PAGINA */
  session_start();
  require_once('../conn.php');
  $dbconn = pg_connect($connection_guest) or die($error_string);

  /* PRELEVO I PARAMETRI PASSATI DALLA FORM IN login.html */
  $username = $_POST['username'];
  $password = $_POST['password'];
  $type = $_POST['type'];
  $bookmaker = $_POST['bookmaker'];


	/* PREPARAZIONE QUERY REGISTRAZIONE OPERATORE */
	pg_prepare($dbconn,
    'Register_operator',
    'INSERT INTO soccerscheme.operator VALUES (DEFAULT, $1, $2);'
  );
  /* PREPARAZIONE QUERY REGISTRAZIONE PARTNER */
  pg_prepare($dbconn,
    'Register_partner',
    'INSERT INTO soccerscheme.partner VALUES (DEFAULT, $1, $2, $3);'
  );
  /* PREPARAZIONE QUERY REGISTRAZIONE AMMINISTRATORE */
  pg_prepare($dbconn,
    'Register_admin',
    'INSERT INTO soccerscheme.administrator VALUES (DEFAULT, $1, $2);'
  );

  /* DIFFERENTI QUERY A SECONDA DEL TIPO DI UTENTE */
  switch ($type) {
    case 'operator':
      $operator = pg_query($dbconn, "SELECT * FROM soccerscheme.operator WHERE name = '$username'");
      $partner = pg_query($dbconn, "SELECT * FROM soccerscheme.partner WHERE name = '$username'");
      $admin = pg_query($dbconn, "SELECT * FROM soccerscheme.administrator WHERE name = '$username'");
      if (pg_num_rows($operator) == 0 && pg_num_rows($partner) == 0 && pg_num_rows($admin) == 0) {
        pg_execute($dbconn, 'Register_operator', array($username, password_hash($password, PASSWORD_DEFAULT)));
        header("location: login.php?1=success");
      } else {
        header("location: login.php?1=rejected&2=not_new");
      }
      break;
    case 'partner':
      $partner = pg_query($dbconn, "SELECT * FROM soccerscheme.partner WHERE name = '$username'");
      $book = pg_query($dbconn, "SELECT * FROM soccerscheme.bookmaker WHERE name = '$bookmaker'");
      $operator = pg_query($dbconn, "SELECT * FROM soccerscheme.operator WHERE name = '$username'");
      $admin = pg_query($dbconn, "SELECT * FROM soccerscheme.administrator WHERE name = '$username'");
      if (pg_num_rows($partner) != 0 || pg_num_rows($operator) != 0 || pg_num_rows($admin) != 0) { // Se utente già esistente
        header("location: login.php?1=rejected&2=not_new");
      } else if (pg_num_rows($book) == 0) { // Se bookmaker inesistente
        header("location: login.php?1=rejected&2=bookmaker");
      } else {
        $row = pg_fetch_row($book);
        $result = pg_execute($dbconn, 'Register_partner', array($username, password_hash($password, PASSWORD_DEFAULT), $row[0]));
        header("location: login.php?1=success");
      }
      break;
    case 'administrator':
      $admin = pg_query($dbconn, "SELECT * FROM soccerscheme.administrator WHERE name = '$username'");
      $operator = pg_query($dbconn, "SELECT * FROM soccerscheme.operator WHERE name = '$username'");
      $partner = pg_query($dbconn, "SELECT * FROM soccerscheme.partner WHERE name = '$username'");
      if (pg_num_rows($admin) == 0 && pg_num_rows($operator) == 0 && pg_num_rows($partner) == 0) {
        pg_execute($dbconn, 'Register_admin', array($username, password_hash($password, PASSWORD_DEFAULT)));
        header("location: login.php?1=success");
      } else {
        header("location: login.php?1=rejected&2=not_new");
      }
      break;
  }
?>
