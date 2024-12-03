<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario inició sesión
if (!isset($_SESSION['iduserFinal'])) {
    header('Location: ../login.php');
    exit();
}

// Obtener el ID del amigo
$idAmigo = $_POST['id_amigo'] ?? null;
$idUsuario = $_SESSION['iduserFinal'];

// Verificar si se envió el ID del amigo
if (!$idAmigo || $idAmigo == $idUsuario) {
    header('Location: ../buscar.php?error=1'); // Evitar solicitudes inválidas
    exit();
}

try {
    // Verificar si ya existe una solicitud de amistad
    $sql_verificar = "SELECT estado_peticion FROM amistades 
                      WHERE (id_user = :idUsuario AND id_amigo = :idAmigo) 
                      OR (id_user = :idAmigo AND id_amigo = :idUsuario)";
    $stmt = $conn->prepare($sql_verificar);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':idAmigo', $idAmigo, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Obtener el estado actual de la solicitud
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
        $estado = $solicitud['estado_peticion'];

        // Redirigir con mensaje según el estado de la solicitud
        if ($estado === 'pendiente') {
            header('Location: ../buscar.php?solicitud_pendiente=1');
        } elseif ($estado === 'aceptada') {
            header('Location: ../buscar.php?amistad_aceptada=1');
        } elseif ($estado === 'rechazada') {
            header('Location: ../buscar.php?amistad_rechazada=1');
        }
        exit();
    }

    // Insertar nueva solicitud de amistad
    $sql_insertar = "INSERT INTO amistades (id_user, id_amigo, estado_peticion) 
                     VALUES (:idUsuario, :idAmigo, 'pendiente')";
    $stmt = $conn->prepare($sql_insertar);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':idAmigo', $idAmigo, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: ../buscar.php?solicitud_enviada=1');
} catch (Exception $e) {
    echo "Error al enviar la solicitud de amistad: " . $e->getMessage();
}
?>
