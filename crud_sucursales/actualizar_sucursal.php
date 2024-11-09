<?php
session_start();
include "../conexion.php"; // Conexión a la base de datos

if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

$id_dir = isset($_POST["id_dir"]) ? (int) $_POST["id_dir"] : 0;
$nombre_dir = htmlspecialchars($_POST["nombre_dir"]);
$calle_dir = htmlspecialchars($_POST["calle_dir"]);
$num_calle_dir = (int) $_POST["num_calle_dir"];
$fono_dir = (int) $_POST["fono_dir"];

// Verificar permisos del usuario
$id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
$id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

if ($id_dir && ($id_per || $id_emp)) {
    // Actualizar la sucursal
    $stmt = $conexion->prepare("UPDATE direccion_retiro SET nom_dir = ?, calle_dir = ?, num_calle_dir = ?, fono_dir = ? WHERE id_dir = ? AND (id_per = ? OR id_emp = ?)");
    $stmt->bind_param("ssiiiii", $nombre_dir, $calle_dir, $num_calle_dir, $fono_dir, $id_dir, $id_per, $id_emp);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: ../sucursales.php?updated=success");
    } else {
        header("Location: ../sucursales.php?updated=error");
    }
    $stmt->close();
} else {
    header("Location: ../sucursales.php?updated=error");
}

$conexion->close();
exit();
?>
