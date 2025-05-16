
<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No has iniciado sesión.']);
    exit();
}

include('../config/database.php');

$user_id = $_SESSION['user_id'];

if (!isset($_FILES['profile_picture'])) {
    echo json_encode(['error' => 'No se recibió ningún archivo.']);
    exit();
}

$file = $_FILES['profile_picture'];

// Validar si hubo error al subir
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Error al subir archivo. Código: ' . $file['error']]);
    exit();
}

// Validar tipo de imagen
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['error' => 'Solo se permiten imágenes JPG, PNG o GIF.']);
    exit();
}

// Crear nombre único
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$newFileName = 'profile_' . $user_id . '_' . time() . '.' . $ext;

// Ruta de destino
$uploadDir = '../uploads/';
$uploadPath = $uploadDir . $newFileName;

// Mover archivo
if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    echo json_encode(['error' => 'No se pudo guardar el archivo en el servidor.']);
    exit();
}

// Actualizar en la base de datos
$sql = "UPDATE users SET profile_picture = $1 WHERE id = $2";
pg_prepare($conn, "update_picture", $sql);
$result = pg_execute($conn, "update_picture", [$newFileName, $user_id]);

if ($result) {
    echo json_encode(['success' => 'Foto de perfil actualizada con éxito.', 'file' => $newFileName]);
} else {
    echo json_encode(['error' => 'Error al actualizar la base de datos.']);
}
?>
