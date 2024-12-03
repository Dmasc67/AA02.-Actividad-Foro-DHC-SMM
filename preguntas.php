<?php
// Incluir lógica del backend
$data = require 'php/backPreguntas.php';

// Extraer datos procesados
$pregunta = $data['pregunta'];
$respuestas = $data['respuestas'];
$error = $data['error'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pregunta['titulo_pregunta'])." - ForoPro";?></title>
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <!-- Mostrar la pregunta -->
        <h2><?php echo htmlspecialchars($pregunta['titulo_pregunta']); ?></h2>
        <p>Publicado por: <strong><?php echo htmlspecialchars($pregunta['autor']); ?></strong> el <?php echo $pregunta['creado_en']; ?></p>
        <p><?php echo nl2br(htmlspecialchars($pregunta['contenido_pregunta'])); ?></p>

        <!-- Mostrar errores -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Respuestas -->
        <h4 class="mt-4">Respuestas</h4>
        <?php if (!empty($respuestas)): ?>
            <div class="list-group">
                <?php foreach ($respuestas as $respuesta): ?>
                    <div class="list-group-item">
                        <p>
                            <strong><?php echo htmlspecialchars($respuesta['autor']); ?></strong> <?php echo $respuesta['creado_en']; ?>
                        </p>
                        <p><?php echo nl2br(htmlspecialchars($respuesta['contenido_respuesta'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">No hay respuestas aún. ¡Sé el primero en responder!</p>
        <?php endif; ?>

        <!-- Formulario para responder -->
        <h4 class="mt-4">Responder a la pregunta</h4>
        <form action="" method="POST">
            <div class="mb-3">
                <textarea name="respuesta" class="form-control" rows="3" maxlength="500" required></textarea>
                <small class="form-text text-muted">Máximo 500 caracteres.</small>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Respuesta</button>
        </form>

        <!-- Botón para volver al perfil -->
        <a href="perfil.php" class="btn btn-secondary mt-4">Volver a Preguntas</a>
    </div>
</body>
</html>