<?php
session_start();
require_once './conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userlogin = isset($_POST['userlogin']) ? trim($_POST['userlogin']) : '';
    $pwdlogin = isset($_POST['pwdlogin']) ? trim($_POST['pwdlogin']) : '';

    $userlogin = htmlspecialchars($userlogin, ENT_QUOTES, 'UTF-8');
    $pwdlogin = htmlspecialchars($pwdlogin, ENT_QUOTES, 'UTF-8');

    if (empty($userlogin)) {
        header('Location: ../index.php?userloginVacio');
        exit();
    }
    if (empty($pwdlogin)) {
        header('Location: ../index.php?pwdloginVacio');
        exit();
    }

    $sql = "SELECT * FROM usuarios WHERE user = :userlogin";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userlogin', $userlogin);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($pwdlogin, $user['password'])) {
            $_SESSION['inicio'] = true;
            $_SESSION['iduserFinal'] = $user['id_user'];
            $_SESSION['userlogin'] = $userlogin;
            header('Location: ../perfil.php');
            exit();
        } else {
            header('Location: ../index.php?loginError');
            exit();
        }
    } else {
        header('Location: ../index.php?loginError');
        exit();
    }
}
?>