<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel de Usuario - Schoolar</title>
  <link rel="stylesheet" href="../Myuser.css" />
  <link rel="icon" type="image/png" href="../src/icons/bolsa-para-la-escuela.Shool.png" />
</head>
<body class="home-body">
  
  <header class="home-header">
    <img src="../src/icons/klipartz.com (1).png" alt="Logo Schoolar" class="logo" />
    <nav>
      <ul class="nav-links">
        <li><a href="#perfil">Mi Perfil</a></li>
        <li><a href="#configuracion">Configuración</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
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

    <section class="user-data" id="configuracion">
      <h3>Datos Personales</h3>
      <form id="updateForm" method="post" action="../php/update_user.php">
        <label for="firstName">Nombre</label>
        <input type="text" id="firstName" name="firstname" required>

        <label for="lastName">Apellido</label>
        <input type="text" id="lastName" name="lastname" required>

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" required>

        <button type="submit" class="btn save-btn">Guardar Cambios</button>
      </form>
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
