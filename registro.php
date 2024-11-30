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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ForoPro</title>
    <link rel="stylesheet" href="styles/registro.css">
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
</head>
<body>
    <div class="page-container">
        <div class="form-container">
            <form action="registro.php" method="POST">
                <div class="logo-container">
                    <img src="img/Imagotipo.png" alt="Logo del Sitio" class="logo">
                </div>
                <h2>Registro de Usuario</h2>

                <div class="input-group">
                    <label for="user">Nombre de Usuario:</label>
                    <input type="text" id="user" name="user" value="<?php if (isset($_SESSION['user'])) echo htmlspecialchars($_SESSION['user']); ?>">
                    <?php if (isset($_GET['userVacio'])) {
                        echo '<p class="error-message">Usuario vacío. Introduce un nombre de usuario válido, por favor.</p>';
                    } ?>
                    <?php if (isset($_GET['existe'])) {
                        echo '<p class="error-message">El usuario o el Email ya existen. Introduce otro usuario o Email.</p>';
                    } ?>
                </div>

                <div class="input-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php if (isset($_SESSION['nombre'])) echo htmlspecialchars($_SESSION['nombre']); ?>">
                </div>

                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php if (isset($_SESSION['email'])) echo htmlspecialchars($_SESSION['email']); ?>">
                    <?php if (isset($_GET['emailVacio'])) {
                        echo '<p class="error-message">Email vacío. Introduce un Email válido, por favor.</p>';
                    } ?>
                </div>

                <div class="input-group">
                    <label for="pwd">Contraseña:</label>
                    <input type="password" id="pwd" name="pwd" minlength="8">
                    <?php if (isset($_GET['pwdVacio'])) {
                        echo '<p class="error-message">Contraseña vacía. Introduce una contraseña válida, por favor.</p>';
                    } ?>
                </div>

                <div class="input-group">
                    <label for="pwdRepeat">Repetir Contraseña:</label>
                    <input type="password" id="pwdRepeat" name="pwdRepeat" minlength="8">
                    <?php if (isset($_GET['pwdNoMatch'])) {
                        echo '<p class="error-message">Las contraseñas no coinciden. Inténtalo de nuevo.</p>';
                    } ?>
                </div>

                <input type="submit" name="registro" value="Registrarse">
                <input type="submit" name="index" value="Volver a Inicio de Sesión">
            </form>
        </div>
    </div>

    <?php
        session_unset();
        session_destroy();
    ?>
</body>
</html>