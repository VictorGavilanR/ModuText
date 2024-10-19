<?php
session_start();
include "conexion.php"; // Asegúrate de tener un archivo con la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se hayan enviado todos los campos necesarios
    if (isset($_POST['tipoTela'], $_POST['cantidad'], $_POST['direccionRetiro'])) {
        $usuario_id = $_SESSION["id"]; // Obtenemos el ID del usuario logueado desde la sesión
        $tipoTela = $_POST['tipoTela'];
        $cantidad = $_POST['cantidad'];
        $direccionRetiro = $_POST['direccionRetiro'];

        // Insertar la solicitud en la base de datos
        $sql = "INSERT INTO donaciones (usuario_id, tipo_tela, kilos, sucursal, estado) 
                VALUES ('$usuario_id', '$tipoTela', '$cantidad', '$direccionRetiro', 'Pendiente')";

        if ($conexion->query($sql) === TRUE) {
            echo "Solicitud de retiro registrada con éxito.";
            // Redirigir a otra página si se desea
            // header("Location: confirmacion.php");
        } else {
            echo "Error al registrar la solicitud: " . $conexion->error;
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
}
?>