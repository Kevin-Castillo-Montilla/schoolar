<?php
session_start();
require 'conexion.php'; // Asegúrate de que este archivo conecta correctamente con tu base de datos

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Recibir datos del formulario
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$photo = $_FILES['photo'] ?? null;

// Validar datos mínimos
if (empty($username) || empty($email)) {
    echo "Por favor completa todos los campos obligatorios.";
    exit;
}

// Subir foto de perfil si existe
$photo_path = null;
if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $filename = uniqid('profile_', true) . "." . $ext;
    $destination = "uploads/" . $filename;

    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }

    if (move_uploaded_file($photo['tmp_name'], $destination)) {
        $photo_path = $destination;
    }
}

// Preparar query base
$sql = "UPDATE users SET username = $1, email = $2";
$params = [$username, $email];
$param_index = 3;

// Si se cambió la contraseña
if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql .= ", password = $$param_index";
    $params[] = $hashed;
    $param_index++;
}

// Si se subió foto
if ($photo_path) {
    $sql .= ", photo = $$param_index";
    $params[] = $photo_path;
    $param_index++;
}

$sql .= " WHERE id = $$param_index";
$params[] = $user_id;

// Ejecutar query
$stmt = pg_prepare($conn, "update_user", $sql);
$result = pg_execute($conn, "update_user", $params);

if ($result) {
    // Actualizar sesión si cambió el nombre de usuario
    $_SESSION['user_name'] = $username;
    header("Location: user.html"); // Vuelve al perfil
    exit;
} else {
    echo "Error al actualizar los datos.";
}
?>
