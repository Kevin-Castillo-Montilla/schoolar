<?php
session_start();

// Verifica si hay una sesión activa
session_start();

if (!isset($_SESSION['user_id'])) {
    session_destroy();
    header("Location: signin.html");
    exit;
}

$userName = $_SESSION['user_name'] ?? 'Usuario';
// Puedes usar estos datos en la interfaz:
$userName = $_SESSION['user_name'] ?? 'Usuario';
?>

