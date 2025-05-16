<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../signin.html');
    exit();
}

$host = "aws-0-us-east-1.pooler.supabase.com";
$port = "6543";
$dbname = "postgres";
$user = "postgres.fyaevelnvphpaymvcpiv";
$password = "1080040202";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Error en la conexión: " . pg_last_error());
}

$user_id = $_SESSION['user_id'];
$query = "SELECT firstname, lastname, email, profile_picture FROM users WHERE id = $1";
$result = pg_query_params($conn, $query, array($user_id));
if (!$result || pg_num_rows($result) === 0) {
    die("No se encontraron datos del usuario.");
}

$user_data = pg_fetch_assoc($result);
$profile_picture = $user_data['profile_picture'] ? htmlspecialchars($user_data['profile_picture']) : 'default.png';
$fullname = htmlspecialchars($user_data['firstname'] . ' ' . $user_data['lastname']);
$email = htmlspecialchars($user_data['email']);
pg_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Schoolar</title>
    <link rel="stylesheet" href="../stylehome.css">
    <link rel="icon" href="icons/bolsa-para-la-escuela.Shool.png" type="image/png">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .profile-img-container {
            position: relative;
            display: inline-block;
        }

        .profile-img {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 50%;
            width: 160px;
            height: 160px;
            object-fit: cover;
        }

        .profile-img:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px #00bfff;
        }

        .edit-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #007BFF;
            color: white;
            border-radius: 50%;
            padding: 6px;
            cursor: pointer;
        }

        .submit-btn {
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: none;
        }

        .btn-group {
            text-align: center;
        }
    </style>
</head>
<body class="home-body">
    <header class="home-header">
        <img src="icons/klipartz.com (1).png" alt="Logo Schoolar" class="logo" />
        <nav>
            <ul class="nav-links">
                <li><a href="Myperfil.php">Mi Perfil</a></li>
                <li><a href="#configuracion">Configuración</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
                <li><a href="list_users.php">Lista de usuarios</a></li>
                <button id="themeToggle" class="btn toggle-theme">🌙</button>
            </ul>
        </nav>
    </header>

    <main class="dashboard">
        <section class="profile-card" id="perfil">
            <form action="update_profile_picture.php" method="post" enctype="multipart/form-data" class="profile-img-container">
                <div class="profile-img-container">
                    <img src="uploads/<?php echo $profile_picture; ?>" alt="Foto de perfil" class="profile-img" id="profileImage">
                    <label for="upload" class="edit-icon" title="Cambiar foto">
                        <i class="fas fa-pencil-alt"></i>
                    </label>
                </div>
                <input type="file" id="upload" name="profile_picture" hidden accept="image/*">
                <div class="btn-group">
                    <button type="submit" class="submit-btn" id="saveBtn">Guardar imagen</button>
                </div>
            </form>

            <div class="profile-info">
                <h2><?php echo $fullname; ?></h2>
                <p><?php echo $email; ?></p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Schoolar. Todos los derechos reservados.</p>
    </footer>

    <script>
        const input = document.getElementById('upload');
        const image = document.getElementById('profileImage');
        const saveBtn = document.getElementById('saveBtn');

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    image.src = event.target.result;
                };
                reader.readAsDataURL(file);
                saveBtn.style.display = 'inline-block';
            } else {
                saveBtn.style.display = 'none';
            }
        });

        // Tema oscuro
        const toggleBtn = document.getElementById('themeToggle');
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            toggleBtn.textContent = '☀️';
        }

        toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            toggleBtn.textContent = isDark ? '☀️' : '🌙';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    </script>
</body>
</html>
