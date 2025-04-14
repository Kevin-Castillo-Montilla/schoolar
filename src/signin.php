<?php
include('../config/database.php'); // Conexión a la base de datos

// Capturar datos del formulario
$email = $_POST['e_mail'];
$passw = $_POST['passw']; // Corregido el nombre

// Encriptar la contraseña (debe coincidir con el método usado en el registro)
$enc_pass = sha1($passw); // Este es el método que usaste en el registro, pero recuerda que no es seguro a largo plazo

// Consulta SQL para verificar si el correo existe en la base de datos
$sql_check_email = "SELECT COUNT(email) as total FROM users WHERE email = $1";

$stmt_check_email = pg_prepare($conn, "check_email", $sql_check_email);
$res_check_email = pg_execute($conn, "check_email", array($email));

if ($res_check_email && pg_num_rows($res_check_email) > 0) {
    // Verificar si el correo existe
    $row = pg_fetch_assoc($res_check_email);
    if ($row['total'] == 0) {
        // Si el correo no existe, redirigir a la página de registro
        echo "<script>alert('El correo no existe. Por favor, regístrate.'); window.location.href='signup.html';</script>";
    } else {
        // Si el correo existe, proceder a verificar la contraseña
        $sql = "SELECT id, email FROM users WHERE email = $1 AND password = $2 AND status = true";

        $stmt = pg_prepare($conn, "validate_user", $sql);
        $res = pg_execute($conn, "validate_user", array($email, $enc_pass));

        if ($res && pg_num_rows($res) > 0) {
            // Usuario autenticado
            // Redirigir a la página de inicio
            header('Location: home.html');
            exit();
        } else {
            // Credenciales incorrectas
            echo "<script>alert('Correo o contraseña incorrectos'); window.location.href='signin.html';</script>";
        }
    }
} else {
    // Error en la consulta
    echo "<script>alert('Hubo un problema al verificar el correo. Inténtalo de nuevo.'); window.location.href='signin.html';</script>";
}
?>


c