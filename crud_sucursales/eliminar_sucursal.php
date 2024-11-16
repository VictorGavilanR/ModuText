<?php
session_start();
include "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    die("Método no permitido.");
}

if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

$id_dir = isset($_GET["id_dir"]) ? (int) $_GET["id_dir"] : 0;

if (!$id_dir) {
    die("ID de dirección no válido.");
}

$id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
$id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

// Verificar si el id_dir está en la tabla sucursales
$stmt_check = $conexion->prepare("SELECT id_dir FROM solicitud WHERE id_dir = ?");
$stmt_check->bind_param("i", $id_dir);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();
$stmt_check->close();

if ($row_check) {
    die("No se puede eliminar esta dirección porque ya tiene solicitudes registradas.");
}

// Proceder a eliminar si no está en sucursales
$stmt_delete = $conexion->prepare("DELETE FROM direccion_retiro WHERE id_dir = ? AND (id_per = ? OR id_emp = ?)");
$stmt_delete->bind_param("iii", $id_dir, $id_per, $id_emp);
$stmt_delete->execute();

if ($stmt_delete->affected_rows > 0) {
    echo "Dirección eliminada correctamente.";
} else {
    echo "No se pudo eliminar la dirección. Verifica que tienes los permisos necesarios.";
}

$stmt_delete->close();
$conexion->close();
?>
