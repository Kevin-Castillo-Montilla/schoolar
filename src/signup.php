<?php
// Incluir archivo de configuración de base de datos
include('../config/database.php');

// Inicialización de variables
$fname = $_POST['f_name'];
$lname = $_POST['l_name'];
$email = $_POST['e_mail'];
$passwd = $_POST['passw'];
$error = "";

// Validar si los campos no están vacíos
if (empty($fname) || empty($lname) || empty($email) || empty($passwd)) {
    $error = "Todos los campos son obligatorios.";
} else {
    // Validar formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } else {
        // Prevenir inyecciones SQL con consultas preparadas
        $stmt = pg_prepare($conn, "check_email", "SELECT COUNT(email) as total FROM users WHERE email = $1 LIMIT 1");
        $result = pg_execute($conn, "check_email", array($email));

        if ($result) {
            $row = pg_fetch_assoc($result);
            if ($row['total'] > 0) {
                $error = "El correo electrónico ya está registrado.";
            } else {
                // Encriptar la contraseña con password_hash
                $enc_pass = password_hash($passwd, PASSWORD_BCRYPT);

                // Preparar la consulta de inserción
                $stmt = pg_prepare($conn, "insert_user", "INSERT INTO users (firstname, lastname, email, password) VALUES ($1, $2, $3, $4)");
                $res = pg_execute($conn, "insert_user", array($fname, $lname, $email, $enc_pass));

                if ($res) {
                    // Redirigir a la página de inicio de sesión
                    echo "<script>alert('Usuario creado exitosamente. Redirigiendo al inicio de sesión.'); window.location.href='signin.html';</script>";
                } else {
                    $error = "Error al registrar el usuario. Intenta más tarde.";
                }
            }
        } else {
            $error = "Error en la consulta de base de datos.";
        }
    }
}

// Si hay un error, mostrarlo
if ($error) {
    echo "<script>alert('$error'); window.location.href='signup.html';</script>";
}
?>
