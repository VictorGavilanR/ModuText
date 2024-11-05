<?php
session_start(); // Iniciar la sesión
include '../conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir tipo de usuario del formulario
    $tipo_usuario = htmlspecialchars($_POST['tipo_usuario']);
    
    // Variable de control de errores
    $errores = false;

    // Verificar tipo de usuario y ejecutar las acciones correspondientes
    switch ($tipo_usuario) {
        case 'PARTICULAR':                                      
            $rut = htmlspecialchars($_POST['rut']);
            $nombre = htmlspecialchars($_POST['nombre']);
            $apellido_paterno = htmlspecialchars($_POST['app_paterno']);
            $apellido_materno = htmlspecialchars($_POST['app_materno']);
            $correo = htmlspecialchars($_POST['correo']);
            $fono_per = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);
            $password = htmlspecialchars($_POST['passwordP']);
            $confirm_password = htmlspecialchars($_POST['confirmPasswordP']);
            
            // Verificar campos vacíos y formato de contraseña
            if (verificarCamposVacios($rut, $nombre, $apellido_paterno, $apellido_materno, $correo, $fono_per, $password, $confirm_password) || 
                validarContraseñasCoinciden($password, $confirm_password) ||
                !validarFormatoContraseña($password) ||
                validarRut($rut,$conexion)) {
                $errores = true;
            }

            // Proceder si no hay errores
            if (!$errores) {
                $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                $conexion->begin_transaction();
                $stmt = $conexion->prepare("INSERT INTO usuario (rut_usuario, password_usuario) VALUES (?, ?)");
                
                if ($stmt) {
                    $stmt->bind_param("ss", $rut, $password_hashed);
                    
                    if ($stmt->execute()) {
                        echo "Usuario registrado con éxito.<br>";
                        $user_id = $conexion->insert_id;
                        $stmt->close();

                        // Registrar en la tabla persona_natural
                        $stmt_particular = $conexion->prepare("INSERT INTO persona_natural (rut_usuario, nombres_per, ap_pat_per, ap_mat_per, fono_per, correo_per) VALUES (?, ?, ?, ?, ?, ?)");
                        
                        if ($stmt_particular) {
                            $stmt_particular->bind_param("ssssis", $rut, $nombre, $apellido_paterno, $apellido_materno, $fono_per, $correo);
                            
                            if ($stmt_particular->execute()) {
                                echo "Cliente registrado con éxito en la tabla persona natural.<br>";
                                $stmt_particular->close();
                                $conexion->commit();
                                $_SESSION['registro_exitoso'] = "Registro exitoso. Por favor, inicie sesión.";
                                header("Location: ../login.php");
                                exit();
                            } else {
                                echo "Error al registrar la persona natural: " . $stmt_particular->error;
                        }
                    } else {
                        echo "Error en la preparación de la consulta para persona natural: " . $conexion->error;
                        
                    }
                } else {
                    echo "Error al registrar el usuario: " . $stmt->error;
                }    
            } else {
                echo "Error en la preparación de la consulta para usuarios: " . $conexion->error;
            }
            $conexion->rollback();
        } else {
            echo "Errores detectados.";
        }
        break;

        case 'EMPRESA':
            $rut_usuario = htmlspecialchars($_POST['rut_emp']);
            $razon_social = htmlspecialchars($_POST['razon_social']);
            $correo = htmlspecialchars($_POST['correoE']);
            $fono_emp = htmlspecialchars($_POST['fono_emp']);
            $password = htmlspecialchars($_POST['passwordE']);
            $confirm_password = htmlspecialchars($_POST['confirmPasswordE']);

            // Verificar campos vacíos, formato de contraseña y rut registrado
            if (verificarCamposVacios($rut_usuario, $razon_social, $correo, $fono_emp, $password, $confirm_password) ||
                validarContraseñasCoinciden($password, $confirm_password) ||
                !validarFormatoContraseña($password) ||
                validarRut($rut_usuario,$conexion)) {
                $errores = true;
            }

            // Proceder si no hay errores
            if (!$errores) {
                $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                $conexion->begin_transaction();
                $stmt = $conexion->prepare("INSERT INTO usuario (rut_usuario, password_usuario) VALUES (?, ?)");
                
                if ($stmt) {
                    $stmt->bind_param("ss", $rut_usuario, $password_hashed);
                    
                    if ($stmt->execute()) {
                        echo "Usuario registrado con éxito.<br>";
                        $user_id = $conexion->insert_id;
                        $stmt->close();

                        // Registrar en la tabla empresa
                        $stmt_empresa = $conexion->prepare("INSERT INTO empresa (rut_usuario, razon_social, fono_emp, correo_emp) VALUES (?, ?, ?, ?)");
                        
                        if ($stmt_empresa) {
                            $stmt_empresa->bind_param("ssis", $rut_usuario, $razon_social, $fono_emp, $correo);

                            if ($stmt_empresa->execute()) {
                                echo "Cliente registrado con éxito en la tabla empresa.<br>";
                                $stmt_empresa->close();
                                $conexion->commit();
                                $_SESSION['registro_exitoso'] = "Registro exitoso. Por favor, inicie sesión.";
                                header("Location: ../login.php");
                                exit();
                            } else {
                                echo "Error al registrar la empresa: " . $stmt_empresa->error;
                            }
                        } else {
                            echo "Error en la preparación de la consulta para empresa: " . $conexion->error;
                        }
                    } else {
                        echo "Error al registrar el usuario: " . $stmt->error;
                    }
                    
                } else {
                    echo "Error en la preparación de la consulta para usuarios: " . $conexion->error;
                }
                $conexion->rollback();
            } else {
                echo "Errores detectados.";
            }
            break;

        default:
            echo "Tipo de usuario no válido.";
            break;
    }
}

// Funciones auxiliares

function verificarCamposVacios(...$campos) {
    foreach ($campos as $campo) {
        if (empty($campo)) {
            echo "Complete los campos vacíos.<br>";
            return true;
        }
    }
    return false;
}

function validarContraseñasCoinciden($password, $confirm_password) {
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden.<br>";
        return true;
    }
    return false;
}

function validarFormatoContraseña($password) {
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
        echo "La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra y un número.<br>";
        return false;
    }
    return true;
}

function validarRut($rut, $conexion) {
    $rut_check = $conexion->prepare("SELECT rut_usuario FROM usuario WHERE rut_usuario = ?");
    if ($rut_check) {
        $rut_check->bind_param("s", $rut);
        $rut_check->execute();
        $rut_check->store_result();
        
        if ($rut_check->num_rows > 0) {
            $rut_check->close();
            echo "El rut ya esta registrado.<br>";
            return true; // El rut ya está registrado
        }
        $rut_check->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
    return false; // El rut no está registrado
}