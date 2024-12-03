<?php
session_start();
require_once 'conexion.php';

// Variables iniciales
$id_pregunta = $_GET['id'] ?? null;
$pregunta = [];
$respuestas = [];
$error = "";

// Validar ID de pregunta
if (!$id_pregunta) {
    die("Error: ID de pregunta no especificado.");
}

// Obtener la pregunta
try {
    $sql = "SELECT preguntas.*, usuarios.user AS autor 
            FROM preguntas 
            JOIN usuarios ON preguntas.id_user = usuarios.id_user 
            WHERE id_pregunta = :id_pregunta";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
    $stmt->execute();
    $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pregunta) {
        die("Error: Pregunta no encontrada.");
    }
} catch (Exception $e) {
    die("Error al obtener la pregunta: " . $e->getMessage());
}

// Manejar envío de respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta'])) {
    $respuesta = trim($_POST['respuesta']) ?? '';

    if (!empty($respuesta)) {
        if (strlen($respuesta) > 500) {
            $error = "La respuesta no puede superar los 500 caracteres.";
        } else {
            try {
                $sql = "INSERT INTO respuestas (id_pregunta, id_user, contenido_respuesta) 
                        VALUES (:id_pregunta, :id_user, :contenido)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
                $stmt->bindParam(':id_user', $_SESSION['iduserFinal'], PDO::PARAM_INT);
                $stmt->bindParam(':contenido', $respuesta);
                $stmt->execute();
            } catch (Exception $e) {
                $error = "Error al enviar la respuesta: " . $e->getMessage();
            }
        }
    } else {
        $error = "La respuesta no puede estar vacía.";
    }
}

// Obtener respuestas ordenadas de más reciente a más antigua
try {
    $sql_respuestas = "SELECT respuestas.*, usuarios.user AS autor 
                       FROM respuestas 
                       JOIN usuarios ON respuestas.id_user = usuarios.id_user 
                       WHERE id_pregunta = :id_pregunta 
                       ORDER BY creado_en DESC";
    $stmt = $conn->prepare($sql_respuestas);
    $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
    $stmt->execute();
    $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Error al obtener las respuestas: " . $e->getMessage();
}

// Retornar datos al frontend
return [
    'pregunta' => $pregunta,
    'respuestas' => $respuestas,
    'error' => $error
];
?>