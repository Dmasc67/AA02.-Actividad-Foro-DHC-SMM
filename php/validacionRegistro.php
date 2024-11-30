<?php
session_start();
require_once 'php/conexion.php'; // Asegúrate de que este archivo establezca una conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $pwd = $_POST['pwd'] ?? '';
    $pwdRepeat = $_POST['pwdRepeat'] ?? '';

    // Validar campos vacíos
    if (empty($user) || empty($nombre) || empty($email) || empty($pwd) || empty($pwdRepeat)) {
        header('Location: registro.php?userVacio=1');
        exit();
    }

    // Validar que las contraseñas coincidan
    if ($pwd !== $pwdRepeat) {
        header('Location: registro.php?pwdNoMatch=1');
        exit();
    }

    // Verificar si el usuario o el email ya existen
    $sql_check = "SELECT * FROM usuarios WHERE user = :user OR email = :email";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':user', $user);
    $stmt_check->bindParam(':email', $email);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        header('Location: registro.php?existe=1');
        exit();
    }

    // Insertar nuevo usuario
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT); // Hash de la contraseña
    $sql_insert = "INSERT INTO usuarios (user, nombre, email, password) VALUES (:user, :nombre, :email, :password)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':user', $user);
    $stmt_insert->bindParam(':nombre', $nombre);
    $stmt_insert->bindParam(':email', $email);
    $stmt_insert->bindParam(':password', $hashedPwd);

    if ($stmt_insert->execute()) {
        header('Location: index.php?registroExitoso=1');
        exit();
    } else {
        header('Location: registro.php?error=1');
        exit();
    }
}
?>