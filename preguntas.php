<?php 
session_start();
require_once 'php/conexion.php'; // Asegúrate de que este archivo establezca una conexión PDO

// Obtener el ID de la pregunta de la URL
$id_pregunta = $_GET['id'] ?? null;

if ($id_pregunta) {
    // Obtener la pregunta específica
    $sql = "SELECT preguntas.*, usuarios.user AS autor FROM preguntas JOIN usuarios ON preguntas.id_user = usuarios.id_user WHERE id_pregunta = :id_pregunta";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
    $stmt->execute();
    $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Error: ID de pregunta no especificado.";
    exit();
}

// Manejo de la lógica para responder a la pregunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta'])) {
    $respuesta = $_POST['respuesta'] ?? '';

    if (!empty($respuesta)) {
        $sql = "INSERT INTO respuestas (id_pregunta, id_user, contenido_respuesta) VALUES (:id_pregunta, :id_user, :contenido)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $_SESSION['iduserFinal'], PDO::PARAM_INT);
        $stmt->bindParam(':contenido', $respuesta);
        $stmt->execute();
    } else {
        echo "Error: La respuesta no puede estar vacía.";
    }
}

// Obtener todas las respuestas para la pregunta
$sql_respuestas = "SELECT respuestas.*, usuarios.user AS autor FROM respuestas JOIN usuarios ON respuestas.id_user = usuarios.id_user WHERE id_pregunta = :id_pregunta ORDER BY creado_en DESC";
$stmt = $conn->prepare($sql_respuestas);
$stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
$stmt->execute();
$respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pregunta['titulo_pregunta']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <h2><?php echo htmlspecialchars($pregunta['titulo_pregunta']); ?></h2>
        <p>Publicado por: <?php echo htmlspecialchars($pregunta['autor']); ?> el <?php echo $pregunta['creado_en']; ?></p>
        <p><?php echo nl2br(htmlspecialchars($pregunta['contenido_pregunta'])); ?></p>

        <h4>Respuestas</h4>
        <div class="list-group">
            <?php foreach ($respuestas as $respuesta) : ?>
                <div class="list-group-item">
                    <strong><?php echo htmlspecialchars($respuesta['autor']); ?></strong>
                    <p><?php echo nl2br(htmlspecialchars($respuesta['contenido_respuesta'])); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <h4>Responder a la pregunta</h4>
        <form action="" method="POST">
            <div class="mb-3">
                <textarea name="respuesta" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Respuesta</button>
        </form>

        <!-- Botón de volver a perfil -->
        <a href="perfil.php" class="btn btn-secondary mt-4">Volver a Preguntas</a>
    </div>
</body>
</html>
