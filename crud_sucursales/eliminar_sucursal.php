<?php
session_start();

if (empty($_SESSION["id_usuario"])) {
    header("location: login.php");
}

include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_suc'])) {
    $id_suc = $_POST['id_suc'];

    // Prepare statement to delete the branch
    $stmt = $conexion->prepare("DELETE FROM sucursales WHERE id_suc = ?");
    $stmt->bind_param("i", $id_suc);

    if ($stmt->execute()) {
        // Redireccionar o mostrar mensaje de éxito
        header("Location: ../sucursales.php?success=Sucursal eliminada correctamente");
    } else {
        // Redireccionar o mostrar mensaje de error
        header("Location: ../sucursales.php?error=Error al eliminar la sucursal");
    }

    $stmt->close();
    $conexion->close();
}
?>