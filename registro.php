<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ChatPro</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
</head>
<body>
    <form action="php/validacionRegistro.php" method="POST">
        <label for="user">Nombre de Usuario:</label>
        <input type="text" id="user" name="user" value="<?php if (isset($_SESSION['user'])) echo $_SESSION['user'];?>">
        <?php if (isset($_GET['userVacio'])) {
            echo 'Usuario vacío. Introduce un nombre de usuario válido, por favor.<br>';
        } ?>
        <?php if (isset($_GET['userError'])) {
            echo 'Usuario no válido. Introduce un nombre de usuario válido, por favor.<br>';
        } ?>
        <br>
        <label for="nombre">Nombre Completo:</label>
        <input type="input" id="nombre" name="nombre" value="<?php if (isset($_SESSION['nombre'])) echo $_SESSION['nombre'];?>"/>
        <?php if (isset($_GET['nombreVacio'])) {
            echo 'Usuario vacío. Introduce un nombre de usuario válido, por favor.<br>';
        } ?>
        <?php if (isset($_GET['nombreError'])) {
            echo 'Usuario no válido. Introduce un nombre de usuario válido, por favor.<br>';
        } ?>
        <br>
        <label for="pwd">Contraseña:</label>
        <input type="password" id="pwd" name="pwd" minlength="8">
        <?php if (isset($_GET['pwdVacio'])) {
            echo 'Contrasena vacía. Introduce una contrasena válida, por favor.<br>';
        } ?>
        <?php if (isset($_GET['pwdError'])) {
            echo 'Contraseña no válida. Introduce una contrasena válida, por favor.<br>';
        } ?>
        <br>

        <?php if (isset($_GET['existe'])) {
            echo "<p style='text-align: center;'>El usuario ya existe. Introduce otro usuario.</p>";
        } ?>
        <input type="submit" name="registro" value="Registrarse">
        <input type="submit" name="index" value="Volver a Inicio de Sesión">
    </form>
    <?php
        session_unset();
        session_destroy();
    ?>
</body>
</html>