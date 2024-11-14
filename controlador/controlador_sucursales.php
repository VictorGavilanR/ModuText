<?php
session_start();
include "../conexion.php"; // Asegúrate de que la ruta sea correcta

// Verificar que la conexión a la base de datos esté establecida
if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

// Verificar que el método de la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["nombre_dir"]) && !empty($_POST["calle_dir"]) && !empty($_POST["num_calle_dir"]) && !empty($_POST["fono_dir"])) {
        $nombre_dir = htmlspecialchars($_POST["nombre_dir"]);
        $calle_dir = htmlspecialchars($_POST["calle_dir"]);
        $num_calle_dir = (int) $_POST["num_calle_dir"];
        $fono_dir = (int) $_POST["fono_dir"];
        $comuna_dir = htmlspecialchars($_POST["comuna_dir"]); // Nuevo campo comuna_dir


      // Obtener los IDs de la sesión
$id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
$id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

// Verificar que los IDs estén correctamente configurados
if (!$id_per && !$id_emp) {
    echo "Error: No se pudo identificar al usuario. ID de usuario no encontrado.";
    exit();
}
        // Asegurarse de que solo uno de los ID esté presente
        if ($id_per && !$id_emp) {
            // Insertar los datos en la tabla direccion_retiro con id_per
            $stmt = $conexion->prepare("INSERT INTO direccion_retiro (id_per, nom_dir, calle_dir, num_calle_dir, fono_dir, comuna_dir) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issiis", $id_per, $nombre_dir, $calle_dir, $num_calle_dir, $fono_dir, $comuna_dir); // Corregir el tipo de los parámetros
        } elseif ($id_emp && !$id_per) {
            // Insertar los datos en la tabla direccion_retiro con id_emp
            $stmt = $conexion->prepare("INSERT INTO direccion_retiro (id_emp, nom_dir, calle_dir, num_calle_dir, fono_dir, comuna_dir) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issiis", $id_emp, $nombre_dir, $calle_dir, $num_calle_dir, $fono_dir, $comuna_dir); // Corregir el tipo de los parámetros
        } else {
            echo "Error: No se pudo identificar al usuario.";
            exit();
        }
        // Ejecutar la consulta y verificar el resultado
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            // Redirige a la página de sucursales después de insertar la dirección
            header("Location: ../sucursales.php");
            exit();
        } else {
            echo "Error: No se pudo añadir la dirección.";
        }

        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>
