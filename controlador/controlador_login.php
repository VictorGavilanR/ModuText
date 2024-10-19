<?php
session_start();

// Incluir la conexión a la base de datos
include "conexion.php";

// Verificar datos con la base de datos
if (isset($_POST["btningresar"])) {
    if (!empty($_POST["rut"]) && !empty($_POST["password"])) {
        $rut = $_POST["rut"];
        $password = $_POST["password"];

        // Preparar la consulta para evitar la inyección SQL
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE rut = ?");
        $stmt->bind_param("s", $rut);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $datos = $result->fetch_object()) {
            // Verificar la contraseña cifrada
            if (password_verify($password, $datos->password)) {
                // Establecer las variables de sesión con los datos del usuario
                $_SESSION["id"] = $datos->id;
                $_SESSION["rut"] = $datos->rut;
                $_SESSION["nombre"] = $datos->nombre; // Asegúrate de que 'nombre' es el campo correcto en tu tabla

                // Redirigir a la página de retiro
                header("Location: retiro.php");
                exit(); // Detener el script después de la redirección
            } else {
                echo "<div>Contraseña incorrecta.</div>";
            }
        } else {
            echo "<div>Usuario no registrado.</div>";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Campos vacíos.";
    }
}
?>
