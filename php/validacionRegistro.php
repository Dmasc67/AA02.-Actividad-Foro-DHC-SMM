<?php
session_start();

if (isset($_POST['index'])) {
    header('Location: ../index.php');
    exit();
}

if (!isset($_POST['registro'])) {
    header('Location: ../registro.php');
    exit();
}

require_once("conexion.php");

$user = isset($_POST['user']) ? trim($_POST['user']) : '';
$nombreCompleto = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['pwd']) ? trim($_POST['pwd']) : '';
$passwordRepeat = isset($_POST['pwdRepeat']) ? trim($_POST['pwdRepeat']) : '';

$user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
$nombreCompleto = htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
$passwordRepeat = htmlspecialchars($passwordRepeat, ENT_QUOTES, 'UTF-8');

$_SESSION['user'] = $user;
$_SESSION['nombre'] = $nombreCompleto;
$_SESSION['email'] = $email;

if (empty($user)) {
    header("Location: ../registro.php?userVacio");
    exit();
} elseif (!preg_match('/^[a-zA-Z0-9]+$/', $user)) {
    header("Location: ../registro.php?userError");
    exit();
} elseif (empty($nombreCompleto)) {
    header("Location: ../registro.php?nombreVacio");
    exit();
} elseif (!preg_match('/^[\p{L}\s]+$/u', $nombreCompleto)) {
    header("Location: ../registro.php?nombreError");
    exit();
} elseif (empty($email)) {
    header("Location: ../registro.php?emailVacio");
    exit();
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../registro.php?emailError");
    exit();
} elseif (empty($password)) {
    header("Location: ../registro.php?pwdVacio");
    exit();
} elseif ($password !== $passwordRepeat) {
    header("Location: ../registro.php?pwdNoMatch");
    exit();
}

try {
    $sql_existe = "SELECT * FROM usuarios WHERE user = :user OR email = :email";
    $stmt = $conn->prepare($sql_existe);
    $stmt->bindParam(':user', $user, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("Location: ../registro.php?existe");
        exit();
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql_insert = "INSERT INTO usuarios (user, nombre, email, password) VALUES (:user, :nombre, :email, :pwd)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombreCompleto, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':pwd', $hashedPassword, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: ../index.php");
            exit();
        } else {
            // Error al insertar
            header("Location: ../registro.php?insertFail");
            exit();
        }
    }
} catch (PDOException $e) {
    // Capturar cualquier otro error en la base de datos
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        error_log("Error de duplicado: " . $e->getMessage());
        header("Location: ../registro.php?existe");
        exit();
    } else {
        error_log("Error en la base de datos: " . $e->getMessage());
        echo "Error en la base de datos: " . $e->getMessage(); // Solo para pruebas
        header("Location: ../registro.php?dbError");
        exit();
    }
}