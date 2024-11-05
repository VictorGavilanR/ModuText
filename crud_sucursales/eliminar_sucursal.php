<?php
session_start();
include "../conexion.php"; // Conexión a la base de datos

if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

$id_dir = isset($_GET["id_dir"]) ? (int) $_GET["id_dir"] : 0;

// Verificar que el usuario tenga permisos para eliminar
$id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
$id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

if ($id_dir && ($id_per || $id_emp)) {
    // Verificar que la sucursal pertenece al usuario actual
    $stmt = $conexion->prepare("DELETE FROM direccion_retiro WHERE id_dir = ? AND (id_per = ? OR id_emp = ?)");
    $stmt->bind_param("iii", $id_dir, $id_per, $id_emp);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Sucursal eliminada correctamente.";
    } else {
        echo "No se pudo eliminar la sucursal.";
    }
    $stmt->close();
} else {
    echo "ID de sucursal inválido o sin permisos.";
}

// Redirigir de vuelta a la lista de sucursales
header("Location: ../sucursales.php");
exit();
?>
