<?php
    $host = '127.0.0.1';
    $dbname = 'db_chat';
    $dbuser = 'root';
    $pwd = '';

    $conn = mysqli_connect($host, $dbuser, $pwd, $dbname);

    if (!$conn) {
        die("Error de conexión: " .mysqli_connect_error());
    }
?>