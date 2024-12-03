<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['inicio'])) {
    header('Location: ../index.php');
    exit();
}

require_once 'php/conexion.php';

// Variables para almacenar datos
$result_preguntas = [];
$error = "";

// Manejar la creación de preguntas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearPregunta'])) {
    $titulo = trim($_POST['titulo']) ?? '';
    $contenido = trim($_POST['contenido']) ?? '';

    if (!isset($_SESSION['iduserFinal'])) {
        $error = "Error: ID de usuario no disponible.";
    } elseif (!empty($titulo) && !empty($contenido)) {
        try {
            $sql = "INSERT INTO preguntas (id_user, titulo_pregunta, contenido_pregunta) VALUES (:id_user, :titulo, :contenido)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_user', $_SESSION['iduserFinal'], PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->execute();
        } catch (Exception $e) {
            $error = "Error al crear la pregunta: " . $e->getMessage();
        }
    } else {
        $error = "Error: Título y contenido no pueden estar vacíos.";
    }
}

// Consultar preguntas
try {
    $sql_preguntas = "SELECT preguntas.*, usuarios.user AS autor FROM preguntas JOIN usuarios ON preguntas.id_user = usuarios.id_user ORDER BY creado_en DESC";
    $stmt = $conn->prepare($sql_preguntas);
    $stmt->execute();
    $result_preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Error al obtener las preguntas: " . $e->getMessage();
}

// Retorna los datos y mensajes al frontend
return [
    'preguntas' => $result_preguntas,
    'error' => $error
];
?>