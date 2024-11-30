<?php
    $host = '127.0.0.1';
    $dbname = 'db_foro';
    $dbuser = 'root';
    $pwd = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $pwd);
    } catch (PDOException $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
?>