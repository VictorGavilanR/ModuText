<?php
session_start();
include "../conexion.php";

// Validar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Validar datos recibidos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_suc = htmlspecialchars($_POST["nom_suc"]);
    $fono_suc = filter_input(INPUT_POST, "fono_suc", FILTER_SANITIZE_NUMBER_INT);
    $cod_post_suc = filter_input(INPUT_POST, "cod_post_suc", FILTER_SANITIZE_NUMBER_INT);
    $calle_suc = htmlspecialchars($_POST["calle_suc"]);
    $num_calle_suc = filter_input(INPUT_POST, "num_calle_suc", FILTER_SANITIZE_NUMBER_INT);

    // Se rescata el id del usuario actual para recuperar el rut de cliente
    $id_us = $_SESSION["id_usuario"];
    $stmt = $conexion->prepare("SELECT rut_usuario FROM usuario WHERE id_usuario = ?");
    
    if (!$stmt) {
        die("Error en la preparación de la consulta SELECT: " . $conexion->error);
    }

    $stmt->bind_param("i", $id_us);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $rut_cli = $result->fetch_assoc()["rut_usuario"]; // Se recupera el rut para la inserción
    } else {
        die("No se encontró ningún registro en la tabla usuario con id_usuario = " . $id_us);
    }

    // Control de errores
    $errores = false;

    // Campos vacíos
    if (empty($nom_suc) || empty($fono_suc) || empty($cod_post_suc) || empty($calle_suc) || empty($num_calle_suc)) {
        echo "<p class='calc-error'>Complete todos los campos.</p>";
        $errores = true;
    }

    // No números
    if (!is_numeric($fono_suc) || !is_numeric($cod_post_suc) || !is_numeric($num_calle_suc)) {
        echo "<p class='calc-error'>Ingrese solo números.</p>";
        $errores = true;
    }

    // Registro en BD
    if (!$errores) {
        // Preparación de consulta
        $stmt = $conexion->prepare("INSERT INTO sucursal (rut_cliente, nom_suc, cod_post_suc, fono_suc, calle_suc, num_calle_suc) VALUES (?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            die("Error en la preparación de la consulta INSERT: " . $conexion->error);
        }

        $stmt->bind_param("ssiisi", $rut_cli, $nom_suc, $cod_post_suc, $fono_suc, $calle_suc, $num_calle_suc);

        if ($stmt->execute()) {
            echo "Sucursal registrada exitosamente.";
            header("Location: ../sucursales.php");
            exit();
        } else {
            echo "Error en la inserción: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Errores detectados.";
    }

    $conexion->close(); // Cierre de conexión
}
?>
