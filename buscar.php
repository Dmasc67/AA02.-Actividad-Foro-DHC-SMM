<?php
$data = require 'php/backBuscar.php';

$preguntas = $data['preguntas'];
$usuarios = $data['usuarios'];
$terminoPreguntas = $data['terminoPreguntas'];
$terminoUsuarios = $data['terminoUsuarios'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar - ForoPro</title>
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <a href="perfil.php" class="btn btn-secondary mt-4">Volver a Preguntas</a>
        <br><br>

        <!-- Mensajes informativos -->
        <?php if (isset($_GET['solicitud_enviada'])): ?>
            <div class="alert alert-success">¡Solicitud de amistad enviada con éxito!</div>
        <?php elseif (isset($_GET['solicitud_pendiente'])): ?>
            <div class="alert alert-warning">Ya existe una solicitud de amistad pendiente con este usuario.</div>
        <?php elseif (isset($_GET['amistad_aceptada'])): ?>
            <div class="alert alert-info">¡Ya eres amigo de este usuario!</div>
        <?php elseif (isset($_GET['amistad_rechazada'])): ?>
            <div class="alert alert-danger">El usuario rechazó tu solicitud de amistad previamente.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Hubo un error al procesar la solicitud.</div>
        <?php endif; ?>

        <form method="POST" action="" class="mb-5">
            <h2>Buscar Preguntas</h2>
            <div class="mb-3">
                <input type="text" name="buscar_preguntas" class="form-control" placeholder="Escribe para buscar preguntas por título..." value="<?php echo $terminoPreguntas; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Preguntas</button>
        </form>

        <?php if (!empty($terminoPreguntas)): ?>
            <h3>Resultados de Preguntas</h3>
            <?php if (!empty($preguntas)): ?>
                <ul class="list-group">
                    <?php foreach ($preguntas as $pregunta): ?>
                        <li class="list-group-item">
                            <a href="preguntas.php?id=<?php echo $pregunta['id_pregunta']; ?>">
                                <?php echo htmlspecialchars($pregunta['titulo_pregunta']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No se encontraron preguntas con ese título.</p>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" action="" class="mt-5">
            <h2>Buscar Usuarios</h2>
            <div class="mb-3">
                <input type="text" name="buscar_usuarios" class="form-control" placeholder="Escribe para buscar usuarios por nombre o username..." value="<?php echo $terminoUsuarios; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Usuarios</button>
        </form>

        <?php if (!empty($terminoUsuarios)): ?>
            <h3>Resultados de Usuarios</h3>
            <?php if (!empty($usuarios)): ?>
                <ul class="list-group">
                    <?php foreach ($usuarios as $usuario): ?>
                        <li class="list-group-item">
                            <?php echo htmlspecialchars($usuario['user']); ?> (<?php echo htmlspecialchars($usuario['nombre']); ?>)
                            <form action="php/solicitudAmistad.php" method="POST" class="d-inline float-end">
                                <input type="hidden" name="id_amigo" value="<?php echo $usuario['id_user']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Enviar Solicitud de Amistad</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No se encontraron usuarios con ese nombre.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>