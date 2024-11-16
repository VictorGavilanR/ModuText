<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

session_start();
include '../conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Función para verificar campos vacíos
function verificarCamposVacios(...$campos) {
    foreach ($campos as $campo) {
        if (empty($campo)) {
            $_SESSION['errores'][] = "Complete los campos vacíos.";
            return true;
        }
    }
    return false;
}

// Función para validar si las contraseñas coinciden
function validarContraseñasCoinciden($password, $confirm_password) {
    if ($password !== $confirm_password) {
        $_SESSION['errores'][] = "Las contraseñas no coinciden.";
        return true;
    }
    return false;
}

// Función para validar el formato de la contraseña (ejemplo: al menos 8 caracteres, incluyendo letras y números)
function validarFormatoContraseña($password) {
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
        $_SESSION['errores'][] = "La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra y un número.";
        return false;
    }
    return true;
}

// Función para validar si el RUT ya está registrado
function validarRutRegistrado($rut, $conexion) {
    $rut_check = $conexion->prepare("SELECT rut_usuario FROM usuario WHERE rut_usuario = ?");
    if ($rut_check) {
        $rut_check->bind_param("s", $rut);
        $rut_check->execute();
        $rut_check->store_result();
        
        if ($rut_check->num_rows > 0) {
            $rut_check->close();
            $_SESSION['errores'][] = "El rut ya está registrado.";
            return true; // El rut ya está registrado
        }
        $rut_check->close();
    } else {
        $_SESSION['errores'][] = "Error en la preparación de la consulta: " . $conexion->error;
    }
    return false; // El rut no está registrado
}

// Función para validar el formato de RUT
function validarRUT($rut) {
    $rut = preg_replace('/[.\-]/', '', $rut);

    $cuerpo = substr($rut, 0, -1);
    $dv = strtoupper(substr($rut, -1));

    $suma = 0;
    $multiplo = 2;

    for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
        $suma += $multiplo * intval($cuerpo[$i]);
        $multiplo = ($multiplo === 7) ? 2 : $multiplo + 1;
    }

    $dvEsperado = 11 - ($suma % 11);
    $dvEsperado = ($dvEsperado == 11) ? '0' : ($dvEsperado == 10 ? 'K' : (string)$dvEsperado);
    if ($dv !== $dvEsperado || strlen($rut) < 8 || strlen($rut) > 9) {
        $_SESSION['errores'][] = "El Rut ingresado no es válido.";
        return true;
    }

    return false;
}


// Función para validar el telefono
function validarTelefono($telefono) {
    if (substr($telefono, 0, 3) === "+56") {
        $telefono = substr($telefono, 3); // Elimina el '56'
    }
    if (strlen($telefono) == 9) {
        return array($telefono,false); // Número válido
    } else {
        $_SESSION['errores'][] = "El número de teléfono debe tener 9 dígitos.";
        return array(null, true); // Número inválido
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $html_errores = '';
    $tipo_usuario = htmlspecialchars($_POST['tipo_usuario']);
    $errores = false;
    $_SESSION['errores'] = []; // Inicializar el array de errores

    if ($tipo_usuario === 'PARTICULAR') {

        // Datos del usuario "PARTICULAR"
        $rut = htmlspecialchars($_POST['rut']);
        $nombre = htmlspecialchars($_POST['nombre']);
        $apellido_paterno = htmlspecialchars($_POST['app_paterno']);
        $apellido_materno = htmlspecialchars($_POST['app_materno']);
        $correo = htmlspecialchars($_POST['correo']);
        $fono_per = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);
        $password = htmlspecialchars($_POST['passwordP']);
        $confirm_password = htmlspecialchars($_POST['confirmPasswordP']);

        list($fono_validado, $fonoOK) = validarTelefono($fono_per);
        // Validación de campos
        if (verificarCamposVacios($rut, $nombre, $apellido_paterno, $apellido_materno, $correo, $fono_per, $password, $confirm_password) ||
            validarContraseñasCoinciden($password, $confirm_password) ||
            !validarFormatoContraseña($password) || validarRut($rut) ||
            validarRutRegistrado($rut, $conexion) || $fonoOK) {
            $errores = true;
            // Convertir los errores de sesión a HTML
            foreach ($_SESSION['errores'] as $error) {
                $html_errores .= "<p class='error' style='color: red;'>$error</p>";
            }
            echo $html_errores; // Devolver HTML con errores
            exit();
        }

        if (!$errores) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $conexion->begin_transaction();
            try {
                // Insertar en tabla `usuario`
                $stmt_usuario = $conexion->prepare("INSERT INTO usuario (rut_usuario, password_usuario) VALUES (?, ?)");
                $stmt_usuario->bind_param("ss", $rut, $password_hashed);
                if (!$stmt_usuario->execute()) {
                    throw new Exception("Error al registrar en la tabla usuario: " . $stmt_usuario->error);
                }

                // Insertar en tabla `persona_natural`
                if ($fono_validado !== null) {
                    $stmt_particular = $conexion->prepare("INSERT INTO persona_natural (rut_usuario, nombres_per, ap_pat_per, ap_mat_per, fono_per, correo_per) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt_particular->bind_param("ssssis", $rut, $nombre, $apellido_paterno, $apellido_materno, $fono_validado, $correo);
                    if (!$stmt_particular->execute()) {
                        throw new Exception("Error al registrar en la tabla persona_natural: " . $stmt_particular->error);
                    }
                }

                $conexion->commit();
                echo "<p class='success'>Registro exitoso. Redirigiendo...</p>"; // Respuesta HTML en caso de éxito
            } catch (Exception $e) {
                $conexion->rollback();
                echo "<p class='error'>Error: " . $e->getMessage() . "</p>";               
            }
            exit();
        } else {
            header("Location: ../registro.php"); // Redirigir de vuelta al formulario
            exit();
        }
    } elseif ($tipo_usuario === 'EMPRESA') {
        // Datos del usuario "EMPRESA"
        $rut_usuario = htmlspecialchars($_POST['rut_emp']);
        $razon_social = htmlspecialchars($_POST['razon_social']);
        $correo_emp = htmlspecialchars($_POST['correoE']);
        $fono_emp = filter_input(INPUT_POST, 'fono_emp', FILTER_SANITIZE_NUMBER_INT);
        $password = htmlspecialchars($_POST['passwordE']);
        $confirm_password = htmlspecialchars($_POST['confirmPasswordE']);

        list($fono_validado, $fonoOK) = validarTelefono($fono_emp); 

        // Validación de campos
        if (verificarCamposVacios($rut_usuario, $razon_social, $correo_emp, $fono_emp, $password, $confirm_password) ||
            validarContraseñasCoinciden($password, $confirm_password) ||
            !validarFormatoContraseña($password) || validarRut($rut_usuario) ||
            validarRutRegistrado($rut_usuario, $conexion) || $fonoOK) {
            $errores = true;
            // Convertir los errores de sesión a HTML
            foreach ($_SESSION['errores'] as $error) {
                $html_errores .= "<p class='error' style='color: red;'>$error</p>";
            }
            echo $html_errores; // Devolver HTML con errores
            exit();
        }
        }

        if (!$errores) {
            
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $conexion->begin_transaction();
            try {
                // Insertar en tabla `usuario`
                $stmt_usuario = $conexion->prepare("INSERT INTO usuario (rut_usuario, password_usuario) VALUES (?, ?)");
                $stmt_usuario->bind_param("ss", $rut_usuario, $password_hashed);
                if (!$stmt_usuario->execute()) {
                    throw new Exception("Error al registrar en la tabla usuario: " . $stmt_usuario->error);
                }

                // Insertar en tabla `empresa`
                if ($fono_validado !== null) {
                    $stmt_empresa = $conexion->prepare("INSERT INTO empresa (rut_usuario, razon_social, fono_emp, correo_emp) VALUES (?, ?, ?, ?)");
                    $stmt_empresa->bind_param("ssis", $rut_usuario, $razon_social, $fono_validado, $correo_emp);
                    if (!$stmt_empresa->execute()) {
                        throw new Exception("Error al registrar en la tabla empresa: " . $stmt_empresa->error);
                    }
                }
                
                $conexion->commit();
                echo "<p class='success'>Registro exitoso. Redirigiendo...</p>"; // Respuesta HTML en caso de éxito
            } catch (Exception $e) {
                $conexion->rollback();
                echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
            }
            exit();
        } else {
            header("Location: ../login.php"); // Redirigir de vuelta al formulario
            exit();
        }
    }

?>