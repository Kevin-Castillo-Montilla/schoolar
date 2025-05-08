<?php
/* verifica si el usuario tiene una sesion activa*/ 
session_start();
header('Content-Type: application/json');

include('../config/database.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Acceso no autorizado.']);
    exit();
}

/*Consultar los datos del usuario desde la base de datos (firstname, lastname, email, photo). */
$user_id = $_SESSION['user_id'];

$sql = "SELECT firstname, lastname, email, photo FROM users WHERE id = $1";
$stmt = pg_prepare($conn, "load_user", $sql);
$result = pg_execute($conn, "load_user", [$user_id]);
/*Devolver los datos en formato JSON para que el frontend (con JavaScript) los pueda mostrar en home.html */
if ($result && pg_num_rows($result) > 0) {
    $user = pg_fetch_assoc($result);
    echo json_encode([
        'success' => true,
        'data' => [
            'firstname' => $user['firstname'],
            'lastname'  => $user['lastname'],
            'email'     => $user['email'],
            'photo'     => $user['photo'] ?? 'default.png' // Si no tiene foto, usar una por defecto
        ]
    ]);
} else {
    echo json_encode(['error' => 'No se encontraron datos del usuario.']);
}
?>
