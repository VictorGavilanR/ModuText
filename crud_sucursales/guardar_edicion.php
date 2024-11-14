<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_suc = $_POST['id_suc'];
    $nom_suc = $_POST['nom_suc'];

    $stmt = $conexion->prepare("UPDATE sucursales SET nom_suc = ? WHERE id_suc = ?");
    $stmt->bind_param("si", $nom_suc, $id_suc);

    if ($stmt->execute()) {
        header("Location: ruta_deseada.php"); // Cambia esto por la ruta correcta
        exit;
    } else {
        echo "Error al guardar los cambios: " . $stmt->error; // Agrega esta línea para depurar
    }

    $stmt->close();
}

$conexion->close();
?>
