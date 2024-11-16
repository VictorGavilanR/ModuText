<?php
session_start();
include '../conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Función para validar el teléfono
function validarTelefono($telefono) {
    if (substr($telefono, 0, 3) === "+56") {
        $telefono = substr($telefono, 3); // Elimina el '56'
    }
    if (strlen($telefono) == 9) {
        return array($telefono, false); // Número válido
    } else {
        $_SESSION['errores'][] = "El número de teléfono debe tener 9 dígitos.";
        return array(null, true); // Número inválido
    }
}

// Función para verificar campos vacíos
function verificarCamposVacios(...$campos) {
    foreach ($campos as $campo) {
        if (empty($campo)) {
            $_SESSION['errores'][] = "Complete los campos vacíos.";
            return true;
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $html_errores = '';
    $errores = false;
    $_SESSION['errores'] = []; // Inicializar el array de errores

    // Verificar si los campos están completos
    if (!empty($_POST["nombre_dir"]) && !empty($_POST["calle_dir"]) && !empty($_POST["num_calle_dir"]) && !empty($_POST["fono_dir"])) {
        $nombre_dir = htmlspecialchars($_POST["nombre_dir"]);
        $calle_dir = htmlspecialchars($_POST["calle_dir"]);
        $num_calle_dir = (int) $_POST["num_calle_dir"];
        $fono_dir = filter_input(INPUT_POST, 'fono_dir', FILTER_SANITIZE_NUMBER_INT);
        $comuna_dir = htmlspecialchars($_POST["comuna_dir"]);

        // Validar el teléfono
        list($fono_validado, $fonoOK) = validarTelefono($fono_dir);
        
        if ($fonoOK || verificarCamposVacios($nombre_dir, $calle_dir, $num_calle_dir, $fono_dir, $comuna_dir)) {
            $errores = true;
            // Convertir los errores de sesión a HTML
            foreach ($_SESSION['errores'] as $error) {
                $html_errores .= "<p class='error' style='color: red;'>$error</p>";
            }
            $_SESSION['html_errores'] = $html_errores; // Guardar los errores en la sesión
            // Mostrar los errores en la misma página
            header("Location: ../sucursales.php"); // Volver a la página
            exit();
        }

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
            $stmt->bind_param("issiis", $id_per, $nombre_dir, $calle_dir, $num_calle_dir, $fono_validado, $comuna_dir); // Corregir el tipo de los parámetros
        } elseif ($id_emp && !$id_per) {
            // Insertar los datos en la tabla direccion_retiro con id_emp
            $stmt = $conexion->prepare("INSERT INTO direccion_retiro (id_emp, nom_dir, calle_dir, num_calle_dir, fono_dir, comuna_dir) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issiis", $id_emp, $nombre_dir, $calle_dir, $num_calle_dir, $fono_validado, $comuna_dir); // Corregir el tipo de los parámetros
        } else {
            echo "Error: No se pudo identificar al usuario.";
            exit();
        }

        // Ejecutar la consulta y verificar el resultado
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            // Redirige a la página de sucursales después de insertar la dirección
            $_SESSION['success'] = "Dirección registrada correctamente.";
            header("Location: ../sucursales.php"); // Redirigir a la página de sucursales
            exit();
        } else {
            $_SESSION['error'] = "Error al añadir la dirección.";
            header("Location: ../sucursales.php"); // Redirigir en caso de error
            exit();
        }

        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>
