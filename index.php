<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'php/conexion.php'; // Asegúrate de que este archivo establezca una conexión PDO

// Manejo de la lógica de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userlogin = $_POST['userlogin'] ?? '';
    $pwdlogin = $_POST['pwdlogin'] ?? '';

    // Validar campos vacíos
    if (empty($userlogin)) {
        header('Location: index.php?userloginVacio=1');
        exit();
    }
    if (empty($pwdlogin)) {
        header('Location: index.php?pwdloginVacio=1');
        exit();
    }

    // Consulta para validar el usuario
    $sql = "SELECT * FROM usuarios WHERE user = :userlogin"; // Solo buscamos el usuario
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userlogin', $userlogin);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($pwdlogin, $user['password'])) {
            $_SESSION['inicio'] = true;
            $_SESSION['userlogin'] = $userlogin;
            header('Location: perfil.php');
            exit();
        } else {
            header('Location: index.php?loginError=1');
            exit();
        }
    } else {
        header('Location: index.php?loginError=1');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ForoPro</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
</head>
<body>
    <div class="form-container">
        <form action="php/validacionLogin.php" method="post">
            <div class="logo-container">
                <img src="img/Imagotipo.png" alt="Logo del Sitio" class="logo">
            </div>
            <label for="userlogin">Nombre de Usuario:</label>
            <input type="text" id="userlogin" name="userlogin" required>
            <br><br>
            <label for="pwdlogin">Contraseña:</label>
            <input type="password" id="pwdlogin" name="pwdlogin" required minlength="8"/><br><br>
            <?php if (isset($_GET['loginError'])) { echo "<p style='text-align: center;'>Usuario o contraseña incorrecto.</p><br>"; } ?>
            <?php if (isset($_GET['userloginVacio'])) { echo "<p style='text-align: center;'>No has ingresado el usuario.</p><br>"; } ?>
            <?php if (isset($_GET['pwdloginVacio'])) { echo "<p style='text-align: center;'>No has ingresado la contraseña.</p><br>"; } ?>
            <input type="submit" name="inicio" value="Iniciar sesión">
            <a class="link" href="registro.php">
                <p>¡Regístrate Gratis!</p>
            </a>
        </form>
    </div>
    <?php
        // session_unset();
        // session_destroy();
    ?>
</body>
</html>