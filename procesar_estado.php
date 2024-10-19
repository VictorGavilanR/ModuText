<?php
session_start();
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_solicitud = $_POST['id_solicitud'];

    // Actualizar el estado de la solicitud a "Retirada"
    $sql = "UPDATE donaciones SET estado = 'Retirada' WHERE id = '$id_solicitud'";

    if ($conexion->query($sql) === TRUE) {
        header("Location: admin.php"); // Redirigir de nuevo a la página del administrador
    } else {
        echo "Error al actualizar el estado: " . $conexion->error;
    }
}
?>