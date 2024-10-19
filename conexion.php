<?php

$conexion = new mysqli("localhost:3307", "root", "", "modutex");

// Comprobar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

//echo "Conexión exitosa a la base de datos";

$conexion->set_charset("utf8");

?>
