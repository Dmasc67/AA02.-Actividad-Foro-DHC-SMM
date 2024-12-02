<?php
    session_start();
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
            <label id="userlogin">Nombre de Usuario:</label>
            <input type="text" id="userlogin" name="userlogin" value="<?php if (isset($_SESSION['userlogin'])) echo $_SESSION['userlogin'];?>">
            <br>
            <br>
            <label for="pwdlogin">Contraseña:</label>
            <input type="password" id="pwdlogin" name="pwdlogin" minlength="8"/><br><br>
            <?php if (isset($_GET['loginError'])) {echo "<p style='text-align: center;'>Usuario o contraseña incorrecto.</p><br>"; } ?>
            <?php if (isset($_GET['userloginVacio'])) {echo "<p style='text-align: center;'>No has ingresado el usuario.</p><br>"; } ?>
            <?php if (isset($_GET['pwdloginVacio'])) {echo "<p style='text-align: center;'>No has ingresado la contrasña.</p><br>"; } ?>
            <input type="submit" name="inicio" value="Iniciar sesión">
            <a class="link" href="registro.php">
                <p>¡Registrate Gratis!</p>
            </a>
        </form>
    </div>
    <?php
        session_unset();
        session_destroy();
    ?>
</body>
</html>