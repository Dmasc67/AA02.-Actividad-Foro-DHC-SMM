<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario inició sesión
if (!isset($_SESSION['iduserFinal'])) {
    header('Location: ../login.php');
    exit();
}

$idUsuario = $_SESSION['iduserFinal'];

// Manejar aceptación o rechazo de solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $idAmigo = $_POST['id_amigo'] ?? null;
    $accion = $_POST['accion'];

    if ($idAmigo) {
        try {
            if ($accion === 'aceptar') {
                // Aceptar solicitud
                $sql_aceptar = "UPDATE amistades SET estado_peticion = 'aceptada' 
                                WHERE id_user = :idAmigo AND id_amigo = :idUsuario AND estado_peticion = 'pendiente'";
                $stmt = $conn->prepare($sql_aceptar);
                $stmt->bindParam(':idAmigo', $idAmigo, PDO::PARAM_INT);
                $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $stmt->execute();
                header('Location: ../solicitudes.php?mensaje=aceptada');
            } elseif ($accion === 'rechazar') {
                // Rechazar solicitud
                $sql_rechazar = "DELETE FROM amistades 
                                 WHERE id_user = :idAmigo AND id_amigo = :idUsuario AND estado_peticion = 'pendiente'";
                $stmt = $conn->prepare($sql_rechazar);
                $stmt->bindParam(':idAmigo', $idAmigo, PDO::PARAM_INT);
                $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $stmt->execute();
                header('Location: ../solicitudes.php?mensaje=rechazada');
            }
        } catch (Exception $e) {
            header('Location: ../solicitudes.php?mensaje=error');
        }
    } else {
        header('Location: ../solicitudes.php?mensaje=error');
    }
    exit();
}

// Obtener todas las solicitudes pendientes
$sql_solicitudes = "SELECT usuarios.id_user, usuarios.user, usuarios.nombre 
                    FROM amistades 
                    JOIN usuarios ON amistades.id_user = usuarios.id_user 
                    WHERE amistades.id_amigo = :idUsuario AND amistades.estado_peticion = 'pendiente'";
$stmt = $conn->prepare($sql_solicitudes);
$stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
$stmt->execute();
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retornar datos al frontend
return [
    'solicitudes' => $solicitudes
];
?>