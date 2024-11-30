<?php 
session_start();
if (!isset($_SESSION['inicio'])) {
    header('Location: index.php');
    exit();
}

require_once 'php/conexion.php';

// Manejo de la lógica para crear preguntas
$result_preguntas = []; // Inicializa el array de preguntas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearPregunta'])) {
    $titulo = $_POST['titulo'] ?? '';
    $contenido = $_POST['contenido'] ?? '';

    // Verificar que el ID de usuario esté disponible
    if (!isset($_SESSION['iduserFinal'])) {
        echo "Error: ID de usuario no disponible.";
    } else {
        // Validar campos vacíos
        if (!empty($titulo) && !empty($contenido)) {
            $sql = "INSERT INTO preguntas (id_user, titulo_pregunta, contenido_pregunta) VALUES (:id_user, :titulo, :contenido)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_user', $_SESSION['iduserFinal'], PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->execute();
        } else {
            echo "Error: Título y contenido no pueden estar vacíos.";
        }
    }
}

// Obtener todas las preguntas
$sql_preguntas = "SELECT preguntas.*, usuarios.user AS autor FROM preguntas JOIN usuarios ON preguntas.id_user = usuarios.id_user ORDER BY creado_en DESC";
$stmt = $conn->prepare($sql_preguntas);
$stmt->execute();
$result_preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro - ChatPro</title>
    <link rel="icon" href="img/Ico Imagotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['userlogin']); ?></h2>

        <!-- Botón de cerrar sesión -->
        <form action="./php/cerrarSesion.php" method="POST" class="mb-4">
            <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
        </form>

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

        <h4>Lista de Preguntas</h4>
        <div class="list-group">
            <?php foreach ($result_preguntas as $pregunta) : ?>
                <a href="preguntas.php?id=<?php echo $pregunta['id_pregunta']; ?>" class="list-group-item list-group-item-action">
                    <h5><?php echo htmlspecialchars($pregunta['titulo_pregunta']); ?></h5>
                    <p>Publicado por: <?php echo htmlspecialchars($pregunta['autor']); ?> el <?php echo $pregunta['creado_en']; ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
