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

        // Obtener los IDs de la sesión
        $id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : NULL;
        $id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : NULL;

        // Asegurarse de que solo uno de los ID esté presente
        if ($id_per && $id_emp) {
            echo "Error: Se detectaron múltiples IDs de usuario.";
        } elseif ($id_per) {
            // Insertar los datos en la tabla direccion_retiro con id_per
            $stmt = $conexion->prepare("INSERT INTO direccion_retiro (id_per, nom_dir, calle_dir, num_calle_dir, fono_dir) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                die("Error en la preparación de la consulta: " . $conexion->error);
            }
            // Vincular parámetros para id_per
            $stmt->bind_param("issii", $id_per, $nombre_dir, $calle_dir, $num_calle_dir, $fono_dir);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Sucursal ingresada correctamente.";
            } else {
                echo "Error al ingresar la sucursal.";
            }

            $stmt->close();
        } elseif ($id_emp) {
            // Insertar los datos en la tabla direccion_retiro con id_emp
            $stmt = $conexion->prepare("INSERT INTO direccion_retiro (id_emp, nom_dir, calle_dir, num_calle_dir, fono_dir) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                die("Error en la preparación de la consulta: " . $conexion->error);
            }
            // Vincular parámetros para id_emp
            $stmt->bind_param("issii", $id_emp, $nombre_dir, $calle_dir, $num_calle_dir, $fono_dir);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Sucursal ingresada correctamente.";
            } else {
                echo "Error al ingresar la sucursal.";
            }

            $stmt->close();
        } else {
            echo "Error: No se pudo identificar al usuario.";
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}
?>
