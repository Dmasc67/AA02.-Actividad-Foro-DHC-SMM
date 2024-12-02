<?php 
session_start();
if (!isset($_SESSION['inicio'])) {
    header('Location: php/cerrarSesion.php');
    exit();
}

require_once 'php/conexion.php';

$mi_id = $_SESSION['iduserFinal'];
$sql_amigos = "SELECT usuarios.iduser, usuarios.user 
               FROM solicitudes 
               JOIN usuarios ON (usuarios.iduser = solicitudes.iduser_1 OR usuarios.iduser = solicitudes.iduser_2) 
               WHERE (solicitudes.iduser_1 = '$mi_id' OR solicitudes.iduser_2 = '$mi_id') 
               AND solicitudes.solicitud_estado = 'aceptada' 
               AND usuarios.iduser != '$mi_id'";
$result_amigos = mysqli_query($conn, $sql_amigos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - ChatPro</title>
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <!-- Cabecera con cierre de sesión -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Perfil de <?php if (isset($_SESSION['userFinal'])) echo $_SESSION['userFinal']; ?> - ChatPro</h2>
            <form action="index.php" method="POST" class="d-inline">
                <input type="submit" name="logout" class="btn btn-danger" value="Cerrar Sesión">
            </form>
        </div>

        <!-- Botón para Búsqueda de Usuarios -->
        <form action="php/buscarUsuarios.php" method="POST" class="d-inline">
            <input type="submit" name="buscar" class="btn btn-primary" value="Buscar Usuarios">
        </form>

        <!-- Botón para Ver Solicitudes de Amistad -->
        <form action="solicitudes.php" method="POST" class="d-inline">
            <input type="submit" name="solicitudes" class="btn btn-secondary" value="Ver Solicitudes">
        </form>

        <!-- Listado de Amigos -->
        <div class="mt-5">
            <h4 style="text-align: center">Lista de Amigos</h4>
            <div id="amistad" name="amistad" class="list-group">
                <?php while ($amigo = mysqli_fetch_assoc($result_amigos)) : ?>
                    <a href="php/chat.php?amigo_id=<?php echo $amigo['iduser']; ?>" class="list-group-item list-group-item-action">
                        <?php echo htmlspecialchars($amigo['user']); ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>