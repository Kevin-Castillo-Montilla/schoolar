<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  session_destroy();
  header("Location: signin.html");
  exit;
}

$userName = $_SESSION['user_name'] ?? 'Usuario';
?>