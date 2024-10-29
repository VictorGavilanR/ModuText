<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (empty($_SESSION["id_usuario"])) {
    header("location: login.php");
    exit();
}

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "modutex");

// Verifica si la conexión se estableció correctamente
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Obtiene los datos del formulario
$id_usuario = $_SESSION["id_usuario"]; // ID del usuario que inicia sesión
$cantidad = $_POST["cantidad"]; // Cantidad de tela
$tipoTela = $_POST["tipoTela"]; // Tipo de tela (ajusta si es necesario)
$direccionRetiro = $_POST["direccionRetiro"]; // Dirección de retiro
$fecha_sol = date("Y-m-d H:i:s"); // Fecha y hora actual
$fecha_ret = date("Y-m-d H:i:s"); // Puedes ajustar esto según tu lógica

// Mapeo de direcciones a ID de sucursal
$mapaSucursales = [
    "almacen1" => 1, // Cambia 1 al ID correspondiente en la tabla sucursal
    "almacen2" => 2, // Cambia 2 al ID correspondiente en la tabla sucursal
    "almacen3" => 3  // Cambia 3 al ID correspondiente en la tabla sucursal
];

// Obtén el ID de sucursal correspondiente
$id_suc = isset($mapaSucursales[$direccionRetiro]) ? $mapaSucursales[$direccionRetiro] : null;

if ($id_suc === null) {
    die("Error: La dirección de retiro no es válida.");
}

// Preparar la consulta SQL
$sql = "INSERT INTO solicitud (id_usuario, fecha_sol, fecha_ret, id_suc) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

// Verifica si la preparación fue exitosa
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $mysqli->error);
}

// Bind de parámetros
$stmt->bind_param("isss", $id_usuario, $fecha_sol, $fecha_ret, $id_suc);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "Solicitud de retiro registrada exitosamente.";
} else {
    echo "Error al registrar la solicitud: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$mysqli->close();
?>