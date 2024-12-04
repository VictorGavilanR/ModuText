
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceder a Modutex</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="login-registro.css">
</head>
<body>
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php endif; ?>
    <div class="main-container">
        <div class="left-section">
            <img src="img/lateral-registro.jpg" alt="Imagen lateral" class="img-fluid">
        </div>
        <div class="right-section">
            <div class="fixed-logo">
                <img class="logo" id="logo-l" src="./img/Logo - Color.png" alt="Logo grande">
                <img class="logo" id="logo-s" src="./img/Marca - Color.png" alt="Logo pequeño">
                <a href="index.php" class="logout-button btn btn-custom">Volver</a>
            </div>
            <!-- Formulario de Login -->
            <form id="loginForm" class="form-container login-form" method="post" action="">
                <?php
                include "conexion.php";
                include "controlador/controlador_login.php";
                ?>
                <h3>Iniciar Sesión</h3>
                <div class="mb-3">
                    <label for="rutUsuario" class="form-label">Rut</label>
                    <input type="text" class="form-control" id="rutUsuario" placeholder="Ingrese su rut" name="rut_usuario">
                    <div id="rutLoginError" style="display:none; color: red;">Rut inválido</div>
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
                <div id="messageContainer"></div>  
                <h3>Registro</h3>
                <div class="mb-3">
                    <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
                    <select class="form-select" name="tipo_usuario" id="tipo_usuario">
                        <option value="" disabled selected>Seleccione un tipo de usuario</option>
                        <option value="EMPRESA">Empresa</option>
                        <option value="PARTICULAR">Particular</option>
                    </select>
                </div>
                <!-- Datos de la Empresa -->
                <div id="empresaContainer" class="empresa-container">
                    <h3>Datos de la Empresa</h3>
                    <div class="mb-3">
                        <label for="rut_emp" class="form-label">Rut de la Empresa</label>
                        <input type="text" name="rut_emp" class="form-control" id="rut_emp" data-error="rutError" placeholder="Ingrese el RUT de la empresa">
                        <div id="rutError" style="display:none; color: red;">Rut inválido</div>
                    </div>
                    <div class="mb-3">
                        <label for="razon_social" class="form-label">Razón Social</label>
                        <input type="text" name="razon_social" class="form-control" id="razon_social" placeholder="Ingrese la razón social de la empresa">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email_emp" class="form-label">Correo Electrónico</label>
                            <input type="email" name="correoE" class="form-control" id="email_emp" placeholder="Ingrese su correo electrónico">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fono_emp" class="form-label">Teléfono</label>
                            <input type="text" name="fono_emp" class="form-control" id="fono_emp" placeholder="Ingrese su teléfono de contacto">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="passwordE" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="passwordE" class="form-control" id="passwordE" placeholder="Ingrese una contraseña">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="passwordE">
                                <i class="bi bi-eye-slash" id="eyeIconE"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPasswordE" class="form-label">Confirmar Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="confirmPasswordE" class="form-control" id="confirmPasswordE" placeholder="Confirme su contraseña">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirmPasswordE">
                                <i class="bi bi-eye-slash" id="eyeIconConfirmE"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <p>La contraseña debe cumplir con:<br>- Mínimo 8 caracteres. <br>- Combinación de letras y números.</p>
                    </div>
                </div>

                <!-- Datos del Particular -->
                <div id="particularContainer" class="particular-container">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombres</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese sus nombres">
                    </div>
                    <div class="mb-3">
                        <label for="rut_part" class="form-label">Rut</label>
                        <input type="text" name="rut" class="form-control" id="rut_part" data-error="rutError" placeholder="Ingrese su rut">
                        <div id="rutError" style="display:none; color: red;">Rut inválido</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="apPat" class="form-label">Apellido Paterno</label>
                            <input type="text" name="app_paterno" class="form-control" id="apPat" placeholder="Ingrese su apellido paterno">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apMat" class="form-label">Apellido Materno</label>
                            <input type="text" name="app_materno" class="form-control" id="apMat" placeholder="Ingrese su apellido materno">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email_part" class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" id="email_part" placeholder="Ingrese su correo electrónico">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fonoUser" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" id="fonoUser" placeholder="Ingrese su teléfono de contacto">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="passwordP" class="form-control" id="password" placeholder="Ingrese una contraseña">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                <i class="bi bi-eye-slash" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirmar Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="confirmPasswordP" class="form-control" id="confirmPassword" placeholder="Confirme su contraseña">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirmPassword">
                                <i class="bi bi-eye-slash" id="eyeIconConfirm"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <p>La contraseña debe cumplir con:<br>- Mínimo 8 caracteres. <br>- Combinación de letras y números.</p>
                    </div>
                </div>

                <button type="submit" name="btnregistrar" id="registerButton" class="btn btn-primary">Registrarse</button>
                <p class="toggle-text">¿Ya tienes una cuenta? <a href="#" id="showLogin">Inicia sesión</a></p>
            </form>
        </div>
    </div>
      <script src="./javascript/formateo.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </div>
  </div>
</body>
</html>