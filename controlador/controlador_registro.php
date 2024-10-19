<?php

    include '../conexion.php';

    // Recibir los datos del formulario
    $rut = $_POST['rut']; 
    $nombres = $_POST['nombres'];
    $apellido_paterno = $_POST['app_paterno'];
    $apellido_materno = $_POST['app_materno'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $tipo_usuario = $_POST['tipo_usuario']; // Asegúrate de que esto coincida con el "name" del select en el formulario
    $password = $_POST['password'];

    // Cifrar la contraseña antes de almacenarla
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta para evitar inyección SQL
    $stmt = $conexion->prepare("INSERT INTO usuarios (rut, nombres, apellido_paterno, apellido_materno, correo, telefono, tipo_usuario, password, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    // Vincular parámetros y ejecutar la consulta
    $stmt->bind_param("ssssssss", $rut, $nombres, $apellido_paterno, $apellido_materno, $correo, $telefono, $tipo_usuario, $password_hashed);

    if ($stmt->execute()) {
        echo "Usuario registrado con éxito";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();

?>
