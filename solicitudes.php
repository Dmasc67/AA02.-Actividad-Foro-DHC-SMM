<?php
$data = require 'php/BackSolicitudes.php';
$solicitudes = $data['solicitudes'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes - ForoPro</title>
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <h1>Solicitudes de Amistad</h1>
        <a href="perfil.php" class="btn btn-secondary mt-4">Volver al Perfil</a>
        <br><br>

        <!-- Mostrar mensajes informativos -->
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'aceptada'): ?>
            <div class="alert alert-success">¡Solicitud de amistad aceptada!</div>
        <?php elseif (isset($_GET['mensaje']) && $_GET['mensaje'] === 'rechazada'): ?>
            <div class="alert alert-danger">Solicitud de amistad rechazada.</div>
        <?php elseif (isset($_GET['mensaje']) && $_GET['mensaje'] === 'error'): ?>
            <div class="alert alert-warning">Ocurrió un error al procesar la solicitud.</div>
        <?php endif; ?>

        <!-- Listar solicitudes pendientes -->
        <?php if (!empty($solicitudes)): ?>
            <ul class="list-group">
                <?php foreach ($solicitudes as $solicitud): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($solicitud['user']); ?></strong>
                            (<?php echo htmlspecialchars($solicitud['nombre']); ?>)
                        </div>
                        <form action="php/BackSolicitudes.php" method="POST" class="d-inline">
                            <input type="hidden" name="id_amigo" value="<?php echo $solicitud['id_user']; ?>">
                            <button type="submit" name="accion" value="aceptar" class="btn btn-success btn-sm">Aceptar</button>
                            <button type="submit" name="accion" value="rechazar" class="btn btn-danger btn-sm">Rechazar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-center">No tienes solicitudes de amistad pendientes.</p>
        <?php endif; ?>
    </div>
</body>
</html>