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

    echo "Datos recibidos: <br>";
    echo "Rut: $rut<br>";
    echo "Nombre: $nombre<br>";
    echo "Apellido Paterno: $apellido_paterno<br>";
    echo "Apellido Materno: $apellido_materno<br>";
    echo "Correo: $correo<br>";
    echo "Teléfono: $telefono<br>";
    echo "Password: $password<br>";

    // Cifrar la contraseña antes de almacenarla
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    echo "Password Hashed: $password_hashed<br>";

    // Preparar la consulta para evitar inyección SQL
    $stmt = $conexion->prepare("INSERT INTO usuarios (rut_usuario, nombres_usuario, ap_pat_usuario, ap_mat_usuario, correo_usuario, fono_usuario, password_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");

    //FALTA AGREGAR A LA TABLA CLIENTES

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt) {
        echo "Consulta preparada correctamente.<br>";
        $stmt->bind_param("sssssss", $rut, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $password_hashed);

        if ($stmt->execute()) {
            echo "Usuario registrado con éxito";
        } else {
            echo "Error en la ejecución de la consulta: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        // Mostrar el error de la preparación de la consulta
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    echo "Todos los campos son obligatorios.";
}

