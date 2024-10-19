<?php
include '../conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa.<br>";
}

if (isset($_POST['rut'], $_POST['nombre'], $_POST['app_paterno'], $_POST['app_materno'], $_POST['correo'], $_POST['telefono'], $_POST['tipo_usuario'], $_POST['password'])) {
    
    // Recibir los datos del formulario
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['app_paterno'];
    $apellido_materno = $_POST['app_materno'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $password = $_POST['password'];

    echo "Datos recibidos: <br>";
    echo "Rut: $rut<br>";
    echo "Nombre: $nombre<br>";
    echo "Apellido Paterno: $apellido_paterno<br>";
    echo "Apellido Materno: $apellido_materno<br>";
    echo "Correo: $correo<br>";
    echo "Teléfono: $telefono<br>";
    echo "Tipo de Usuario: $tipo_usuario<br>";
    echo "Password: $password<br>";

    // Cifrar la contraseña antes de almacenarla
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    echo "Password Hashed: $password_hashed<br>";

    // Preparar la consulta para evitar inyección SQL
    $stmt = $conexion->prepare("INSERT INTO usuarios (rut, nombre, apellido_paterno, apellido_materno, email, telefono, tipo_usuario, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt) {
        echo "Consulta preparada correctamente.<br>";
        $stmt->bind_param("ssssssss", $rut, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $tipo_usuario, $password_hashed);

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
?>
