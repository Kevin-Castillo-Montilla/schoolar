<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

require 'conexion.php'; // tu archivo de conexión a PostgreSQL

$user_id = $_SESSION['user_id'];

// Obtener los datos del usuario
$sql = "SELECT username, email, profile_picture FROM users WHERE id = $1";
$result = pg_query_params($conn, $sql, array($user_id));

if ($row = pg_fetch_assoc($result)) {
    $username = $row['username'];
    $email = $row['email'];
    $profile_picture = $row['profile_picture'];
} else {
    echo "Usuario no encontrado.";
    exit;
}
?>
