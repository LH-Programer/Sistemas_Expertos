<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'localhost';
$db_port = '5432'; // Puerto por defecto de PostgreSQL
$db_name = 'prueba_tecnica';  
$db_user = 'postgres';
$db_pass = 'admin';

$conn = pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass");

pg_set_client_encoding($conn, "UTF8");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
} 

?>