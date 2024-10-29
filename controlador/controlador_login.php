<?php
session_start();

// Incluir la conexión a la base de datos
include "conexion.php";

// Verificar datos con la base de datos
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if (!empty($_POST["rut_usuario"]) && !empty($_POST["password_usuario"])) {
        $rut_usuario = htmlspecialchars($_POST["rut_usuario"]);
        $password_usuario = htmlspecialchars($_POST["password_usuario"]);

        // Preparar la consulta para evitar la inyección SQL
        $stmt = $conexion->prepare("SELECT * FROM usuario WHERE rut_usuario = ?");
        $stmt->bind_param("s", $rut_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $datos = $result->fetch_object()) {
            // Verificar la contraseña cifrada
            if (password_verify($password_usuario, $datos->password_usuario)) {
                // Establecer las variables de sesión con los datos del usuario
                $_SESSION["id_usuario"] = $datos->id_usuario;
                $_SESSION["rut_usuario"] = $datos->rut_usuario;
                $_SESSION["nombres_usuario"] = $datos->nombres_usuario; // Usando el nombre correcto de la tabla

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

