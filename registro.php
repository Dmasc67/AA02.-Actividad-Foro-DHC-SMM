<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
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
            <form action="php/validacionRegistro.php" method="POST">
            <div class="logo-container">
                <img src="img/Imagotipo.png" alt="Logo del Sitio" class="logo">
            </div>
                <h2>Registro de Usuario</h2>

                <div class="input-group">
                    <label for="user">Nombre de Usuario:</label>
                    <input type="text" id="user" name="user" value="<?php if (isset($_SESSION['user'])) echo $_SESSION['user'];?>">
                    <?php if (isset($_GET['userVacio'])) {
                        echo '<p class="error-message">Usuario vacío. Introduce un nombre de usuario válido, por favor.</p>';
                    } ?>
                    <?php if (isset($_GET['userError'])) {
                        echo '<p class="error-message">Usuario no válido. Introduce un nombre de usuario válido, por favor.</p>';
                    } ?>
                </div>

                <div class="input-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php if (isset($_SESSION['nombre'])) echo $_SESSION['nombre'];?>">
                    <?php if (isset($_GET['nombreVacio'])) {
                        echo '<p class="error-message">Nombre vacío. Introduce un nombre completo válido, por favor.</p>';
                    } ?>
                    <?php if (isset($_GET['nombreError'])) {
                        echo '<p class="error-message">Nombre no válido. Introduce un nombre completo válido, por favor.</p>';
                    } ?>
                </div>

                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php if (isset($_SESSION['email'])) echo $_SESSION['email'];?>">
                    <?php if (isset($_GET['emailVacio'])) {
                        echo '<p class="error-message">Email vacío. Introduce un Email válido, por favor.</p>';
                    } ?>
                    <?php if (isset($_GET['emailError'])) {
                        echo '<p class="error-message">Email no válido. Introduce un Email válido, por favor.</p>';
                    } ?>
                </div>

                <div class="input-group">
                    <label for="pwd">Contraseña:</label>
                    <input type="password" id="pwd" name="pwd" minlength="8">
                    <?php if (isset($_GET['pwdVacio'])) {
                        echo '<p class="error-message">Contraseña vacía. Introduce una contraseña válida, por favor.</p>';
                    } ?>
                    <?php if (isset($_GET['pwdError'])) {
                        echo '<p class="error-message">Contraseña no válida. Introduce una contraseña válida, por favor.</p>';
                    } ?>
                </div>

                <div class="input-group">
                    <label for="pwdRepeat">Repetir Contraseña:</label>
                    <input type="password" id="pwdRepeat" name="pwdRepeat" minlength="8">
                    <?php if (isset($_GET['pwdNoMatch'])) {
                        echo '<p class="error-message">Las contraseñas no coinciden. Inténtalo de nuevo.</p>';
                    } ?>
                </div>

                <?php if (isset($_GET['existe'])) {
                    echo "<p class='error-message'>El usuario o el Email ya existen. Introduce otro usuario o Email.</p>";
                } ?>

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