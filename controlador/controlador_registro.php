<?php
include '../conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa.<br>";
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    
    // Recibir los datos del formulario
    $rut = htmlspecialchars($_POST['rut']);
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido_paterno = htmlspecialchars($_POST['app_paterno']);
    $apellido_materno = htmlspecialchars($_POST['app_materno']);
    $correo = htmlspecialchars($_POST['correo']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $password = htmlspecialchars($_POST['password']);
    $tipo_usuario = htmlspecialchars($_POST['tipo_usuario']);

    // Si el usuario es empresa, obtenemos datos adicionales
    if ($tipo_usuario == 'EMPRESA') {
        $rut_cliente = htmlspecialchars($_POST['rut_cliente']);
        $razon_social = htmlspecialchars($_POST['razon_social']);
        $fono_cli = htmlspecialchars($_POST['fono_cli']);
        $cod_post_cli = htmlspecialchars($_POST['cod_post_cli']);
    }

    // Cifrar la contraseña antes de almacenarla
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insertar datos en la tabla usuarios
    $stmt = $conexion->prepare("INSERT INTO usuarios (rut_usuario, nombres_usuario, ap_pat_usuario, ap_mat_usuario, correo_usuario, fono_usuario, password_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssssss", $rut, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $password_hashed);
        
        if ($stmt->execute()) {
            echo "Usuario registrado con éxito<br>";

            // Obtener el ID del usuario insertado
            $id_usuario = $conexion->insert_id;

            // Preparar la consulta para insertar en clientes
            $stmt_cliente = $conexion->prepare("INSERT INTO clientes (id_usuario, tipo_cli, rut_cliente, razon_social, fono_cli, cod_post_cli) VALUES (?, ?, ?, ?, ?, ?)");

            if ($stmt_cliente) {
                $stmt_cliente->bind_param("isssss", $id_usuario, $tipo_usuario, $rut_cliente, $razon_social, $fono_cli, $cod_post_cli);

                if ($stmt_cliente->execute()) {
                    echo "Cliente registrado con éxito en la tabla clientes.<br>";
                } else {
                    echo "Error al registrar el cliente: " . $stmt_cliente->error;
                }
                $stmt_cliente->close();
            } else {
                echo "Error en la preparación de la consulta para clientes: " . $conexion->error;
            }
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
