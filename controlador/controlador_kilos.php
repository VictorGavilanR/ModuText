<?php
session_start();
include "./conexion.php"; // Asegúrate de que la ruta sea correcta

if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

// Consultar el total de kilos sumados en la columna 'cant_res' de la tabla 'solicitud'
$query = "SELECT SUM(cant_res) AS total_kilos FROM solicitud";
$result = $conexion->query($query);

if ($result) {
    $data = $result->fetch_assoc();
    $totalKilos = $data['total_kilos'] ? $data['total_kilos'] : 0; // Si no hay registros, asignamos 0

    // Factor de emisión en kg CO₂ por kg de tela
    $factorEmision = 0.015;

    // Calcular la huella de carbono
    $huellaCarbono = $totalKilos * $factorEmision;

    // Guardar en sesión
    $_SESSION["total_kilos"] = $totalKilos;
    $_SESSION["huella_carbono"] = $huellaCarbono;
} else {
    echo "Error al obtener los datos de los kilos: " . $conexion->error;
}

// Opcional: Si deseas ver los datos para depuración
//echo "Total Kilos: " . $totalKilos . "<br>";
//echo "Huella de Carbono: " . $huellaCarbono . " kg CO₂";


?>