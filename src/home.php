<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

// Configuración de conexión a la base de datos
$host = "aws-0-us-east-1.pooler.supabase.com";
$port = "6543";
$dbname = "postgres";
$user = "postgres.fyaevelnvphpaymvcpiv";
$password = "1080040202";

// Crear conexión con la base de datos
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error en la conexión: " . pg_last_error());
}

// Verificar si la tabla "projects" existe
$table_check_query = "SELECT to_regclass('public.projects');";
$table_check_result = pg_query($conn, $table_check_query);
$table_exists = pg_fetch_result($table_check_result, 0, 0);

if ($table_exists == 'public.projects') {
    // Obtener los proyectos públicos
    $query = "SELECT p.id, p.title, p.description, p.file_path, p.created_at, u.firstname, u.lastname 
              FROM projects p
              JOIN users u ON p.user_id = u.id
              WHERE p.is_public = true
              ORDER BY p.created_at DESC";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Error en la consulta: " . pg_last_error());
    }
} else {
    // Si la tabla no existe, podemos mostrar un mensaje personalizado
    $result = null;
    $error_message = "La tabla de proyectos no existe en la base de datos.";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Proyectos - Schoolar</title>
    <link rel="stylesheet" href="../stylehome.css">
    <link rel="icon" type="image/png" href="../src/icons/bolsa-para-la-escuela.Shool.png">
</head>
<body class="home-body">
    <header class="home-header">
        <img src="../src/icons/klipartz.com (1).png" alt="Logo Schoolar" class="logo" />
        <nav>
            <ul class="nav-links">
                <li><a href="Myperfil.php">Mi Perfil</a></li>
                <li><a href="#configuracion">Configuración</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
                <li><a href="list_users.php">Lista de usuarios</a></li>
            </ul>
            <button id="themeToggle" class="btn toggle-theme">Modo Oscuro</button>
        </nav>
    </header>

    <main class="dashboard">
        <section class="profile-card" id="perfil">
            <img src="../uploads/default.png" alt="Foto de perfil" class="profile-img" id="profileImage">
            <input type="file" id="upload" hidden>
            <label for="upload" class="upload-btn">Cambiar foto</label>

            <h2 id="userName">Nombre Usuario</h2>
            <p id="userEmail">correo@ejemplo.com</p>
        </section>

        <section class="user-projects">
            <h3>Proyectos Públicos</h3>

            <?php
            if ($result) {
                if (pg_num_rows($result) > 0) {
                    while ($project = pg_fetch_assoc($result)) {
                        echo "<div class='project-card'>";
                        echo "<h4>" . htmlspecialchars($project['title']) . "</h4>";
                        echo "<p><strong>Descripción:</strong> " . htmlspecialchars($project['description']) . "</p>";
                        echo "<p><strong>Publicado por:</strong> " . htmlspecialchars($project['firstname']) . " " . htmlspecialchars($project['lastname']) . "</p>";
                        echo "<p><strong>Fecha de creación:</strong> " . htmlspecialchars($project['created_at']) . "</p>";
                        echo "<a href='" . htmlspecialchars($project['file_path']) . "' target='_blank'>Ver Proyecto</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay proyectos públicos disponibles.</p>";
                }
            } else {
                echo "<p>" . (isset($error_message) ? $error_message : "Hubo un problema al cargar los proyectos.") . "</p>";
            }
            ?>
        </section>

        <section class="password-change">
            <h3>Cambiar Contraseña</h3>
            <form id="passwordForm" method="post" action="../php/change_password.php">
                <label for="newPassword">Nueva Contraseña</label>
                <input type="password" id="newPassword" name="new_password" required>

                <label for="confirmPassword">Confirmar Contraseña</label>
                <input type="password" id="confirmPassword" name="confirm_password" required>

                <button type="submit" class="btn pass-btn">Actualizar Contraseña</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Schoolar. Todos los derechos reservados.</p>
    </footer>

    <script>
        const toggleBtn = document.getElementById('themeToggle');
        toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            toggleBtn.textContent = document.body.classList.contains('dark-mode') ? '☀️' : '🌙';
        });
    </script>
</body>
</html>

<?php
// Cerrar la conexión con la base de datos
pg_close($conn);
?>
