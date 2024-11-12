<?php
session_start();
include "./conexion.php"; // Asegúrate de que la ruta sea correcta

if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["rut_usuario"]) && !empty($_POST["password_usuario"])) {
        $rut_usuario = htmlspecialchars($_POST["rut_usuario"]);
        $password_usuario = htmlspecialchars($_POST["password_usuario"]);

        // Preparar consulta para obtener el rut_usuario y password_usuario
        $stmt = $conexion->prepare("SELECT rut_usuario, password_usuario FROM usuario WHERE rut_usuario = ?");
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }
        
        $stmt->bind_param("s", $rut_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $datos = $result->fetch_object()) {
            if (password_verify($password_usuario, $datos->password_usuario)) {
                $_SESSION["rut_usuario"] = $datos->rut_usuario;

                // Obtener correo de persona natural
                $stmt_per = $conexion->prepare("SELECT correo_per, id_per FROM persona_natural WHERE rut_usuario = ?");
                $stmt_per->bind_param("s", $rut_usuario);
                $stmt_per->execute();
                $result_per = $stmt_per->get_result();

                if ($result_per && $datos_per = $result_per->fetch_object()) {
                    // Guardar correo de persona natural en la sesión
                    $_SESSION["email_usuario"] = $datos_per->correo_per; 
                    $_SESSION["id_per"] = $datos_per->id_per;
                    $_SESSION["id_emp"] = null;  // Null si es persona natural
                } else {
                    // Obtener correo de empresa
                    $stmt_emp = $conexion->prepare("SELECT correo_emp, id_emp FROM empresa WHERE rut_usuario = ?");
                    $stmt_emp->bind_param("s", $rut_usuario);
                    $stmt_emp->execute();
                    $result_emp = $stmt_emp->get_result();

                    if ($result_emp && $datos_emp = $result_emp->fetch_object()) {
                        // Guardar correo de empresa en la sesión
                        $_SESSION["email_usuario"] = $datos_emp->correo_emp; 
                        $_SESSION["id_emp"] = $datos_emp->id_emp;
                        $_SESSION["id_per"] = null;  // Null si es empresa
                    }
                }

                // Redirigir al usuario a la página de retiro
                header("Location: retiro.php");
                exit();
            } else {
                echo "<div style='color: red;'>Contraseña incorrecta.</div><br>";
            }
        } else {
            echo "<div style='color: red;'>Usuario no registrado.</div><br>";
        }

        $stmt->close();
    } else {
        echo "<div style='color: red;'>Campos vacíos.</div><br>";
    }
}
?>