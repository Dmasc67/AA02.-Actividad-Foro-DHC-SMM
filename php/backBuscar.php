<?php
require_once 'conexion.php';

// Inicializar variables
$terminoPreguntas = $_POST['buscar_preguntas'] ?? '';
$terminoUsuarios = $_POST['buscar_usuarios'] ?? '';
$preguntas = [];
$usuarios = [];

// Buscar preguntas por título
if (!empty($terminoPreguntas)) {
    $sql_preguntas = "SELECT id_pregunta, titulo_pregunta FROM preguntas WHERE titulo_pregunta LIKE :termino ORDER BY creado_en DESC";
    $stmt = $conn->prepare($sql_preguntas);
    $busqueda_param = '%' . $terminoPreguntas . '%';
    $stmt->bindParam(':termino', $busqueda_param, PDO::PARAM_STR);
    $stmt->execute();
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar usuarios por nombre de usuario o nombre real
if (!empty($terminoUsuarios)) {
    $sql_usuarios = "SELECT id_user, user, nombre FROM usuarios WHERE user LIKE :termino OR nombre LIKE :termino ORDER BY user ASC";
    $stmt = $conn->prepare($sql_usuarios);
    $busqueda_param = '%' . $terminoUsuarios . '%';
    $stmt->bindParam(':termino', $busqueda_param, PDO::PARAM_STR);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Retornar resultados al frontend
return [
    'preguntas' => $preguntas,
    'usuarios' => $usuarios,
    'terminoPreguntas' => htmlspecialchars($terminoPreguntas), // Para evitar XSS
    'terminoUsuarios' => htmlspecialchars($terminoUsuarios)  // Para evitar XSS
];
?>