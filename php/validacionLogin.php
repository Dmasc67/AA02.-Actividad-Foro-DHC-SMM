<?php
session_start();
require_once './conexion.php'; // Asegúrate de que este archivo establezca una conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userlogin = $_POST['userlogin'] ?? '';
    $pwdlogin = $_POST['pwdlogin'] ?? '';

    // Validar campos vacíos
    if (empty($userlogin)) {
        header('Location: ../index.php?userloginVacio=1'); // Ajusta la ruta si es necesario
        exit();
    }
    if (empty($pwdlogin)) {
        header('Location: ../index.php?pwdloginVacio=1'); // Ajusta la ruta si es necesario
        exit();
    }

    // Consulta para validar el usuario
    $sql = "SELECT * FROM usuarios WHERE user = :userlogin"; // Solo buscamos el usuario
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userlogin', $userlogin);
    $stmt->execute();

    // Verificar si el usuario existe
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verificar la contraseña
        if (password_verify($pwdlogin, $user['password'])) {
            $_SESSION['inicio'] = true;
            $_SESSION['iduserFinal'] = $user['id_user']; // Almacena el ID del usuario
            $_SESSION['userlogin'] = $userlogin;
            header('Location: ../perfil.php'); // Ajusta la ruta según la ubicación real de perfil.php
            exit();
        } else {
            header('Location: ../index.php?loginError=1'); // Ajusta la ruta si es necesario
            exit();
        }
    } else {
        header('Location: ../index.php?loginError=1'); // Ajusta la ruta si es necesario
        exit();
    }
}
?>
