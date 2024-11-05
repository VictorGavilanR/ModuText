<?php
session_start();
include '../conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Función para verificar campos vacíos
function verificarCamposVacios(...$campos) {
    foreach ($campos as $campo) {
        if (empty($campo)) {
            echo "Complete los campos vacíos.<br>";
            return true;
        }
    }
    return false;
}

// Función para validar si las contraseñas coinciden
function validarContraseñasCoinciden($password, $confirm_password) {
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden.<br>";
        return true;
    }
    return false;
}

// Función para validar el formato de la contraseña (ejemplo: al menos 8 caracteres, incluyendo letras y números)
function validarFormatoContraseña($password) {
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
        echo "La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra y un número.<br>";
        return false;
    }
    return true;
}

// Función para validar si el RUT ya está registrado
function validarRut($rut, $conexion) {
    $rut_check = $conexion->prepare("SELECT rut_usuario FROM usuario WHERE rut_usuario = ?");
    if ($rut_check) {
        $rut_check->bind_param("s", $rut);
        $rut_check->execute();
        $rut_check->store_result();
        
        if ($rut_check->num_rows > 0) {
            $rut_check->close();
            echo "El rut ya está registrado.<br>";
            return true; // El rut ya está registrado
        }
        $rut_check->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
    return false; // El rut no está registrado
}

// Aquí comienza la lógica principal del registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_usuario = htmlspecialchars($_POST['tipo_usuario']);
    $errores = false;

    if ($tipo_usuario === 'PARTICULAR') {
        // Código para registrar un usuario "PARTICULAR"
        // ...
    } elseif ($tipo_usuario === 'EMPRESA') {
        // Código para registrar un usuario "EMPRESA"
        // ...
    }
}
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_usuario = htmlspecialchars($_POST['tipo_usuario']);
    $errores = false;

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

        // Validación de campos
        if (verificarCamposVacios($rut, $nombre, $apellido_paterno, $apellido_materno, $correo, $fono_per, $password, $confirm_password) ||
            validarContraseñasCoinciden($password, $confirm_password) ||
            !validarFormatoContraseña($password) ||
            validarRut($rut, $conexion)) {
            $errores = true;
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
                $stmt_particular = $conexion->prepare("INSERT INTO persona_natural (rut_usuario, nombres_per, ap_pat_per, ap_mat_per, fono_per, correo_per) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_particular->bind_param("sssssi", $rut, $nombre, $apellido_paterno, $apellido_materno, $fono_per, $correo);
                if (!$stmt_particular->execute()) {
                    throw new Exception("Error al registrar en la tabla persona_natural: " . $stmt_particular->error);
                }

                $conexion->commit();
                header("Location: ../login.php?mensaje=Registro+exitoso");
                exit();
            } catch (Exception $e) {
                $conexion->rollback();
                echo "Error: " . $e->getMessage();
            }
        }
    } elseif ($tipo_usuario === 'EMPRESA') {
        // Datos del usuario "EMPRESA"
        $rut_usuario = htmlspecialchars($_POST['rut_emp']);
        $razon_social = htmlspecialchars($_POST['razon_social']);
        $correo_emp = htmlspecialchars($_POST['correoE']);
        $fono_emp = filter_input(INPUT_POST, 'fono_emp', FILTER_SANITIZE_NUMBER_INT);
        $password = htmlspecialchars($_POST['passwordE']);
        $confirm_password = htmlspecialchars($_POST['confirmPasswordE']);

        // Validación de campos
        if (verificarCamposVacios($rut_usuario, $razon_social, $correo_emp, $fono_emp, $password, $confirm_password) ||
            validarContraseñasCoinciden($password, $confirm_password) ||
            !validarFormatoContraseña($password) ||
            validarRut($rut_usuario, $conexion)) {
            $errores = true;
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
                $stmt_empresa = $conexion->prepare("INSERT INTO empresa (rut_usuario, razon_social, fono_emp, correo_emp) VALUES (?, ?, ?, ?)");
                $stmt_empresa->bind_param("ssis", $rut_usuario, $razon_social, $fono_emp, $correo_emp);
                if (!$stmt_empresa->execute()) {
                    throw new Exception("Error al registrar en la tabla empresa: " . $stmt_empresa->error);
                }

                $conexion->commit();
                header("Location: ../login.php?mensaje=Registro+exitoso");
                exit();
            } catch (Exception $e) {
                $conexion->rollback();
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
?>
