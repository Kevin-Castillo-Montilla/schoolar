<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('../config/database.php');

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT
        firstname,
        lastname,
        email,
        profile_picture,
        age,
        description,
        career,
        university
    FROM users
    WHERE id = $1
";

$res = pg_query_params($conn, $sql, array($user_id));

if (!$res) {
    echo "Error en la consulta: " . pg_last_error();
    exit;
}

$user_data = pg_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Perfil - Schoolar</title>
  <link rel="stylesheet" href="../Myuser.css" />
  <link rel="icon" type="image/png" href="../src/icons/bolsa-para-la-escuela.Shool.png" />
</head>
<body class="home-body">
  
  <header class="home-header">
    <img src="../src/icons/klipartz.com (1).png" alt="Logo Schoolar" class="logo" />
    <nav>
      <ul class="nav-links">
                   <button id="themeToggle" class="btn toggle-theme" title="Modo oscuro">
  🌙
</button>
        <li><a href="home.php">Menú</a></li>
        <li><a href="#perfil">Perfil</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>

      </ul>
    </nav>
  </header>

  <main class="dashboard">
    <section class="profile-card" id="perfil">
      <div class="profile-top">
        <div class="profile-img-container">
          <img src="../uploads/<?php echo $user_data['profile_picture'] ?: 'default.png'; ?>" alt="Foto de perfil" class="profile-img">
          
          <!-- Botón para cambiar foto debajo de la foto -->
          <label for="upload" class="upload-btn">Cambiar foto</label>
          <input type="file" id="upload" hidden>
        </div>
        
        <!-- Botón de editar en la esquina derecha -->
        <a href="editar_perfil.php" class="btn edit-btn">✏️ Editar</a>
      </div>

      <div class="profile-info">
        <h2><?php echo $user_data['firstname'] . ' ' . $user_data['lastname']; ?></h2>
        <p><strong>Email:</strong> <?php echo $user_data['email']; ?></p>
        <p><strong>Edad:</strong> <?php echo $user_data['age'] ?: 'No especificado'; ?></p>
        <p><strong>Descripción:</strong> <?php echo $user_data['description'] ?: 'Sin descripción'; ?></p>
        <p><strong>Carrera:</strong> <?php echo $user_data['career'] ?: 'No definida'; ?></p>
        <p><strong>Universidad:</strong> <?php echo $user_data['university'] ?: 'No definida'; ?></p>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Schoolar. Todos los derechos reservados.</p>
  </footer>
<script>
  const toggleBtn = document.getElementById('themeToggle');
  
  // Detectar el cambio de tema
  toggleBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    
    // Cambiar el ícono dependiendo del tema
    if (document.body.classList.contains('dark-mode')) {
      toggleBtn.classList.remove('light-mode-icon');
      toggleBtn.classList.add('dark-mode-icon');
    } else {
      toggleBtn.classList.remove('dark-mode-icon');
      toggleBtn.classList.add('light-mode-icon');
    }
  });

  // Establecer el icono por defecto según el tema actual
  if (document.body.classList.contains('dark-mode')) {
    toggleBtn.classList.add('dark-mode-icon');
  } else {
    toggleBtn.classList.add('light-mode-icon');
  }
</script>

</body>
</html>
