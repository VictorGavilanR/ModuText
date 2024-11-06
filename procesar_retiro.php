<?php
session_start();
include 'conexion.php'; // Asegúrate de que tienes este archivo para conectar a tu base de datos

// Verificar si hay una sesión activa
if (empty($_SESSION["rut_usuario"])) {
    header("Location: login.php");
    exit();
}

// Validar que los datos hayan sido enviados correctamente
if (isset($_POST['tipoTela'], $_POST['cantidad'], $_POST['direccionRetiro'])) {
    // Obtener datos del formulario
    $rut_usuario = $_SESSION['rut_usuario'];
    $tipoTela = $_POST['tipoTela'];
    $cantidad = $_POST['cantidad'];
    $direccionRetiro = $_POST['direccionRetiro'];
    $fechaSolicitud = date('Y-m-d H:i:s');

    // Obtener el ID del residuo en la tabla `residuo` según el tipo de tela
    $queryRes = "SELECT id_res FROM residuo WHERE nombre_res = ?";
    $stmtRes = $conexion->prepare($queryRes);  // Usar $conexion en lugar de $conn
    $stmtRes->bind_param("s", $tipoTela);
    $stmtRes->execute();
    $resultRes = $stmtRes->get_result();

    if ($resultRes->num_rows > 0) {
        $rowRes = $resultRes->fetch_assoc();
        $id_residuo = $rowRes['id_res'];

        // Insertar la solicitud en la tabla `solicitud`
        $queryInsert = "INSERT INTO solicitud (rut_usuario, id_dir, id_res, fecha_sol) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conexion->prepare($queryInsert);  // Usar $conexion en lugar de $conn
        $stmtInsert->bind_param("siis", $rut_usuario, $direccionRetiro, $id_residuo, $fechaSolicitud);

        if ($stmtInsert->execute()) {
            echo "Solicitud enviada con éxito.";
            header("Location: retiro.php?mensaje=success"); // Redirigir con mensaje de éxito
        } else {
            echo "Error al enviar la solicitud. Inténtalo de nuevo.";
        }

        $stmtInsert->close();
    } else {
        echo "Error: Tipo de tela no encontrado.";
    }

    $stmtRes->close();
} else {
    echo "Error: Todos los campos son obligatorios.";
}

$conexion->close();  // Usar $conexion en lugar de $conn
?>