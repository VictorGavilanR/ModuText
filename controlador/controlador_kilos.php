<?php
session_start();
include "./conexion.php"; // Verifica que la ruta sea correcta

if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

// Consultar el total de kilos sumados en la columna 'cant_res'
$query = "SELECT SUM(cant_res) AS total_kilos FROM solicitud";
$result = $conexion->query($query);

if ($result) {
    $data = $result->fetch_assoc();
    $totalKilos = $data['total_kilos'] ? intval($data['total_kilos']) : 0; // Convertir a entero
    $_SESSION["total_kilos"] = $totalKilos;
} else {
    $_SESSION["total_kilos"] = 0;
    echo "Error al obtener los datos de los kilos: " . $conexion->error;
}

// Calcular huella de carbono (ejemplo: 1 kg = 2 kg de CO₂)
$huellaCarbono = $totalKilos * 2; // Ajusta según tu fórmula
$_SESSION["huella_carbono"] = $huellaCarbono;

// Opcional: Verifica que los datos estén disponibles
//echo "Total Kilos: " . $totalKilos . " KG - Huella de Carbono: " . $huellaCarbono . " CO₂";
?>