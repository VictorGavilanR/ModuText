<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login y Registro</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="login-registro.css">
</head>
<body>
<?php
  if (isset($_GET['mensaje'])) {
      echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_GET['mensaje']) . '</div>';
  }
  ?>
  <div class="main-container">
    <div class="left-section">
      <img src="img/lateral-registro.jpg" alt="Imagen lateral" class="img-fluid">
    </div>
    <div class="right-section">
      <div class="fixed-logo">
        <img class="logo" id="logo-l" src="./img/Logo - Color.png" alt="Logo grande">
        <img class="logo" id="logo-s" src="./img/Marca - Color.png" alt="Logo pequeño">
        <a href="index.html" class="logout-button btn btn-custom">Volver</a>
      </div>
      <!-- Formulario de Login -->
      <form id="loginForm" class="form-container login-form" method="post" action="">
        <?php
          include "conexion.php";
          include "controlador/controlador_login.php";
        ?>
        <div class="mb-3">
          <label for="rutUsuario" class="form-label">Rut</label>
          <input type="text" class="form-control" id="rutUsuario" placeholder="Ingrese su rut" name="rut_usuario">
          <div id="rutError" style="display:none; color: red;">Rut inválido</div>
        </div>
        <div class="mb-3">
          <label for="loginPassword" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="loginPassword" placeholder="Ingrese su contraseña" name="password_usuario">
        </div>
        <button name="btningresar" class="btn btn-primary">Iniciar Sesión</button>

        <p class="toggle-text">¿No tienes cuenta? <a href="#" id="showRegister">Regístrate</a></p>
      </form>

      <!-- Formulario de Registro -->
      <form id="registerForm" class="form-container hidden register-form" action="controlador/controlador_registro.php" method="POST">
        <h3>Datos del Usuario</h3>
        <div class="mb-3">
          <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
          <select class="form-select" name="tipo_usuario" id="tipo_usuario" >
            <option value="" disabled selected>Seleccione un tipo de usuario</option>
            <option value="EMPRESA">Empresa</option>
            <option value="PARTICULAR">Particular</option>
          </select>
        </div>

        <!-- Datos de la Empresa -->
        <div id="empresaContainer" class="empresa-container" action="controlador/controlador_registro.php" style="display: none;">
          <div id="empresaForm" class="mb-3">
            <h3>Datos de la Empresa</h3>
            <div class="mb-3">
              <label for="rut" class="form-label">RUT de la Empresa</label>
              <input type="text" name="rut_emp" class="form-control" id="rut" placeholder="Ingrese el RUT de la empresa" >
              <div id="rutError" style="display:none; color: red;">Rut inválido</div>
            </div>
            <div class="mb-3">
              <label for="razon_social" class="form-label">Razón Social</label>
              <input type="text" name="razon_social" class="form-control" id="razon_social" placeholder="Ingrese la razón social de la empresa" >
            </div>
            <div class="row">
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Correo Electrónico</label>
              <input type="email" name="correoE" class="form-control" id="email" placeholder="Ingrese su correo electrónico" >
            </div>
            <div class="col-md-6 mb-3">
              <label for="fono_emp" class="form-label">Teléfono</label>
              <input type="number" name="fono_emp" class="form-control" id="fono_emp" placeholder="Ingrese su teléfono de contacto" >
            </div>
            </div>
              <div class="mb-3">
              <label for="passwordE" class="form-label">Contraseña</label>
              <input type="password" name="passwordE" class="form-control" id="passwordE" placeholder="Ingrese una contraseña" >
            </div>
            <div class="mb-3">
              <label for="confirmPasswordE" class="form-label">Confirmar Contraseña</label>
              <input type="password" name="confirmPasswordE" class="form-control" id="confirmPasswordE" placeholder="Confirme su contraseña" >
            </div>
          </div>
        </div>

        <!-- Datos del Particular -->
        <div id="particularContainer" action="controlador/controlador_registro.php" style="display: none; ">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombres</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese sus nombres" >
          </div>
          <div class="mb-3">
            <label for="rut" class="form-label">Rut</label>
            <input type="text" name="rut" class="form-control" id="rut" placeholder="Ingrese rut" >
            <div id="rutError" style="display:none; color: red;">Rut inválido</div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="apPat" class="form-label">Apellido Paterno</label>
              <input type="text" name="app_paterno" class="form-control" id="apPat" placeholder="Ingrese su apellido paterno" >
            </div>
            <div class="col-md-6 mb-3">
              <label for="apMat" class="form-label">Apellido Materno</label>
              <input type="text" name="app_materno" class="form-control" id="apMat" placeholder="Ingrese su apellido materno" >
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Correo Electrónico</label>
              <input type="email" name="correo" class="form-control" id="email" placeholder="Ingrese su correo electrónico" >
            </div>
            <div class="col-md-6 mb-3">
              <label for="fonoUser" class="form-label">Teléfono</label>
              <input type="number" name="telefono" class="form-control" id="fonoUser" placeholder="Ingrese su teléfono de contacto" >
            </div>
          </div>
          <div class="mb-3">
            <label for="passwordP" class="form-label">Contraseña</label>
            <input type="password" name="passwordP" class="form-control" id="passwordP" placeholder="Ingrese una contraseña" >
          </div>
          <div class="mb-3">
            <label for="confirmPasswordP" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" name="confirmPasswordP" id="confirmPasswordP" placeholder="Confirme su contraseña" >
          </div>
        </div>
        
        <!-- Contenedor fijo para el botón de registro -->
        <div class="button-container">
          <button type="submit" class="btn btn-primary" style="display: none;" id="registerButton">Registrarse</button>
          <p class="toggle-text">¿Ya tienes cuenta? <a href="#" id="showLogin">Inicia Sesión</a></p>
        </div>
      </form>
    
      <script>
        document.addEventListener('DOMContentLoaded', function() {
        const tipoUsuarioSelect = document.getElementById('tipo_usuario');
        const empresaContainer = document.getElementById('empresaContainer');
        const particularContainer = document.getElementById('particularContainer');
        const registerButton = document.querySelector('button[type="submit"]');

        tipoUsuarioSelect.addEventListener('change', function() {
          const selectedValue = this.value;

          if (selectedValue === 'EMPRESA') {
            empresaContainer.style.display = 'block';
            particularContainer.style.display = 'none';
          } else if (selectedValue === 'PARTICULAR') {
            empresaContainer.style.display = 'none';
            particularContainer.style.display = 'block';
          } else {
            empresaContainer.style.display = 'none';
            particularContainer.style.display = 'none';
          }

          // Mostrar el botón de registro cuando se selecciona un tipo de usuario válido
          if (selectedValue) {
            registerButton.style.display = 'block';
          } else {
            registerButton.style.display = 'none';
          }
          });
        });
      </script>

      <script src="formateo.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const showRegister = document.getElementById('showRegister');
        const showLogin = document.getElementById('showLogin');
        const tipoUsuario = document.getElementById('tipo_usuario');
        const empresaContainer = document.getElementById('empresaContainer');

        showRegister.addEventListener('click', (e) => {
          e.preventDefault();
          loginForm.classList.add('hidden');
          registerForm.classList.remove('hidden');
        });

        showLogin.addEventListener('click', (e) => {
          e.preventDefault();
          registerForm.classList.add('hidden');
          loginForm.classList.remove('hidden');
        });

        tipoUsuario.addEventListener('change', function() {
          if (this.value === 'EMPRESA') {
              empresaContainer.classList.add('show'); // Muestra el contenedor
          } else {
              empresaContainer.classList.remove('show'); // Oculta el contenedor
          }
        });
      </script>
    </div>
  </div>
</body>
</html>
