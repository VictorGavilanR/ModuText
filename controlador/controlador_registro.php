<?php
session_start(); // Iniciar la sesión
include '../conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa.<br>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recibir los datos del formulario
    $rut = htmlspecialchars($_POST['rut']);
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido_paterno = htmlspecialchars($_POST['app_paterno']);
    $apellido_materno = htmlspecialchars($_POST['app_materno']);
    $correo = htmlspecialchars($_POST['correo']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $password = htmlspecialchars($_POST['password']);
    $tipo_usuario = htmlspecialchars($_POST['tipo_usuario']);

    // Inicializar variables adicionales para EMPRESA
    $rut_cliente = null;
    $razon_social = null;
    $fono_cli = null;

    // Si el usuario es empresa, obtenemos datos adicionales
    if ($tipo_usuario == 'EMPRESA') {
        $rut_cliente = htmlspecialchars($_POST['rut_cliente']);
        $razon_social = htmlspecialchars($_POST['razon_social']);
        $fono_cli = htmlspecialchars($_POST['fono_cli']);
    }

    // Cifrar la contraseña antes de almacenarla
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insertar datos en la tabla usuarios
    $stmt = $conexion->prepare("INSERT INTO usuario (rut_usuario, password_usuario, tipo_usuario) VALUES (?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sss", $rut, $password_hashed, $tipo_usuario);
        
        if ($stmt->execute()) {
            echo "Usuario registrado con éxito<br>";

            // Obtener el ID del usuario insertado
            $id_usuario = $conexion->insert_id;

            // Preparar la consulta para insertar en empresa si el usuario es de tipo EMPRESA
            if ($tipo_usuario == 'EMPRESA') {
                $stmt_empresa = $conexion->prepare("INSERT INTO empresa (id_usuario, razon_social, fono_emp, correo_emp) VALUES (?, ?, ?, ?)");

                if ($stmt_empresa) {
                    $stmt_empresa->bind_param("isss", $id_usuario, $razon_social, $fono_cli, $correo); // Puedes ajustar el binding de acuerdo a tus necesidades

                    if ($stmt_empresa->execute()) {
                        echo "Cliente registrado con éxito en la tabla empresa.<br>";
                    } else {
                        echo "Error al registrar la empresa: " . $stmt_empresa->error;
                    }
                    $stmt_empresa->close();
                } else {
                    echo "Error en la preparación de la consulta para empresa: " . $conexion->error;
                }
            }

            // Establecer un mensaje de éxito y redirigir al login
            $_SESSION['registro_exitoso'] = "Registro exitoso. Por favor, inicie sesión.";
            header("Location: ../login.php"); // Asegúrate de que esta ruta sea correcta
            exit();
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta para usuarios: " . $conexion->error;
    }

    $conexion->close();
} else {
    echo "Todos los campos son obligatorios.";
}
?>