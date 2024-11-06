<?php
session_start();
include "conexion.php"; // Asegúrate de que este archivo contiene la conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["rut_usuario"]) && !empty($_POST["cantidad"]) && !empty($_POST["tipo_tela"]) && !empty($_POST["direccion_retiro"])) {
        
        $rut_cliente = trim($_SESSION["rut_usuario"]); // Elimina espacios
        echo "RUT del usuario en sesión: " . $rut_cliente; // Debug
        $tipo_tela = htmlspecialchars($_POST["tipo_tela"]);
        $cantidad = htmlspecialchars($_POST["cantidad"]);
        $direccion_retiro = htmlspecialchars($_POST["direccion_retiro"]);
        $fecha_sol = date("Y-m-d H:i:s");
        $fecha_ret = null; // Si es NULL, no lo incluyas en el bind_param

        // Verifica que el rut_cliente existe en la tabla clientes
        $stmt = $conexion->prepare("SELECT * FROM clientes WHERE rut_cliente = ?");
        $stmt->bind_param("s", $rut_cliente);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            // Rut cliente existe, continuar con la inserción
            $stmt = $conexion->prepare("INSERT INTO solicitudes (rut_cliente, cant_res, fecha_sol, fecha_ret) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("isss", $rut_cliente, $cantidad, $fecha_sol, $fecha_ret);
                if ($stmt->execute()) {
                    echo "Solicitud de retiro guardada correctamente.";
                } else {
                    echo "Error al guardar la solicitud: " . $stmt->error;
                }
            } else {
                echo "Error en la preparación de la consulta: " . $conexion->error;
            }
        } else {
            echo "El RUT del cliente no existe en la base de datos.";
        }
        $stmt->close();
    } else {
        echo "Por favor completa todos los campos.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>