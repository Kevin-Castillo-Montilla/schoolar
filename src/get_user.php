<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$conn = new PDO("pgsql:host=localhost;dbname=schoolar", "usuario", "contraseña");
$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
  'username' => $user['username'],
  'profile_pic' => $user['profile_pic']
]);
?>
