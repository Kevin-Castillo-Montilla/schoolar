<?php/* es para cambiar la contraseña */
session_start();
include('../config/database.php');

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

// Obtener datos del formulario
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$user_id = $_SESSION['user_id'];

// Validaciones básicas
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Las nuevas contraseñas no coinciden.']);
    exit();
}

// Obtener la contraseña actual de la base de datos
$sql = "SELECT password FROM users WHERE id = $1 LIMIT 1";
$stmt = pg_prepare($conn, "get_user_password", $sql);
$result = pg_execute($conn, "get_user_password", [$user_id]);

if ($result && pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    $stored_hash = $row['password'];

    // Verificar contraseña actual
    if (!password_verify($current_password, $stored_hash)) {
        echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta.']);
        exit();
    }

    // Hashear nueva contraseña
    $new_hash = password_hash($new_password, PASSWORD_BCRYPT);

    // Actualizar contraseña en la base de datos
    $update_sql = "UPDATE users SET password = $1 WHERE id = $2";
    $update_stmt = pg_prepare($conn, "update_password", $update_sql);
    $update_result = pg_execute($conn, "update_password", [$new_hash, $user_id]);

    if ($update_result) {
        echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
}
?>
