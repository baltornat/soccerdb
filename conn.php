<?php
  /* DEFINIZIONE DEI PARAMETRI PER LA CONNESSIONE AL DB */
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'soccerdb');
  define('DB_USER', 'postgres');
  define('DB_ADMIN', 'administrator');
  define('DB_OPERATOR', 'operator');
  define('DB_PARTNER', 'partner');
  define('DB_GUEST', 'guest');
  define('DB_PASSWORD', 'prova');
  define('DB_PORT', 5432);
  $connection_string = 'host='.DB_HOST.' port='.DB_PORT.' dbname='.DB_NAME.' user='.DB_USER.' password='.DB_PASSWORD;
  $connection_admin = 'host='.DB_HOST.' port='.DB_PORT.' dbname='.DB_NAME.' user='.DB_ADMIN.' password='.DB_PASSWORD;
  $connection_operator = 'host='.DB_HOST.' port='.DB_PORT.' dbname='.DB_NAME.' user='.DB_OPERATOR.' password='.DB_PASSWORD;
  $connection_partner = 'host='.DB_HOST.' port='.DB_PORT.' dbname='.DB_NAME.' user='.DB_PARTNER.' password='.DB_PASSWORD;
  $connection_guest = 'host='.DB_HOST.' port='.DB_PORT.' dbname='.DB_NAME.' user='.DB_GUEST.' password='.DB_PASSWORD;
  $error_string = 'Error during connection!';
?>
