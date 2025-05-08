<?php
session_start();
header('Content-Type: application/json');

include('../config/database.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Acceso no autorizado.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$fname = trim($_POST['firstname']);
$lname = trim($_POST['lastname']);
$email = trim($_POST['email']);

if (empty($fname) || empty($lname) || empty($email)) {
    echo json_encode(['error' => 'Todos los campos son obligatorios.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Correo electrónico no válido.']);
    exit();
}

// Verificar si el nuevo correo ya está en uso por otro usuario
$sql_check = "SELECT COUNT(*) AS total FROM users WHERE email = $1 AND id != $2";
pg_prepare($conn, "check_email_conflict", $sql_check);
$res_check = pg_execute($conn, "check_email_conflict", [$email, $user_id]);
$row = pg_fetch_assoc($res_check);

if ($row['total'] > 0) {
    echo json_encode(['error' => 'Este correo ya está en uso por otro usuario.']);
    exit();
}

// Actualizar los datos
$sql_update = "UPDATE users SET firstname = $1, lastname = $2, email = $3 WHERE id = $4";
pg_prepare($conn, "update_user_info", $sql_update);
$res_update = pg_execute($conn, "update_user_info", [$fname, $lname, $email, $user_id]);

if ($res_update) {
    echo json_encode(['success' => 'Datos actualizados correctamente.']);
} else {
    echo json_encode(['error' => 'Error al actualizar los datos.']);
}
?>
