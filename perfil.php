<?php
// Incluir la lógica de backend
$data = require 'php/backPerfil.php';

// Obtener los datos procesados
$preguntas = $data['preguntas'];
$error = $data['error'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - ForoPro</title>
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['userlogin']); ?></h2>
            <form action="./php/cerrarSesion.php" method="POST" class="mb-4">
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>

        <form action="buscar.php" method="POST" class="d-inline">
            <input type="submit" name="buscar" class="btn btn-primary" value="Buscar Usuarios y Preguntas">
        </form>
        <form action="solicitudes.php" method="POST" class="d-inline">
            <input type="submit" name="solicitudes" class="btn btn-secondary" value="Ver Solicitudes">
        </form>
        <form action="amigos.php" method="POST" class="d-inline">
            <input type="submit" name="solicitudes" class="btn btn-secondary" value="Ver Amigos">
        </form>
        <br><br>

        <form action="" method="POST" class="mb-4">
            <h4>Crear Nueva Pregunta</h4>
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" id="titulo" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contenido" class="form-label">Descripción</label>
                <textarea id="contenido" name="contenido" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" name="crearPregunta" class="btn btn-primary">Publicar Pregunta</button>
        </form>

        <!-- Mensajes de error -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Lista de preguntas -->
        <h4>Lista de Preguntas</h4>
        <div class="list-group">
            <?php foreach ($preguntas as $pregunta): ?>
                <a href="preguntas.php?id=<?php echo htmlspecialchars($pregunta['id_pregunta']); ?>" class="list-group-item list-group-item-action">
                    <h5><?php echo htmlspecialchars($pregunta['titulo_pregunta']); ?></h5>
                    <p>Publicado por: <?php echo htmlspecialchars($pregunta['autor']); ?> el <?php echo $pregunta['creado_en']; ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>