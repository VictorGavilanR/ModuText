<?php
session_start();
include "./conexion.php"; // Asegúrate de que la ruta sea correcta

// Verificar que la conexión a la base de datos esté establecida
if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

// Verificar datos con la base de datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["rut_usuario"]) && !empty($_POST["password_usuario"])) {
        $rut_usuario = htmlspecialchars($_POST["rut_usuario"]);
        $password_usuario = htmlspecialchars($_POST["password_usuario"]);

        // Preparar la consulta para evitar la inyección SQL
        $stmt = $conexion->prepare("SELECT rut_usuario, password_usuario FROM usuario WHERE rut_usuario = ?");
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }
        
        $stmt->bind_param("s", $rut_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $datos = $result->fetch_object()) {
            // Verificar la contraseña cifrada
            if (password_verify($password_usuario, $datos->password_usuario)) {
                // Establecer las variables de sesión
                $_SESSION["rut_usuario"] = $datos->rut_usuario;

                // Verificar si es una persona natural o una empresa y guardar el ID correspondiente
                $stmt_per = $conexion->prepare("SELECT id_per FROM persona_natural WHERE rut_usuario = ?");
                $stmt_per->bind_param("s", $rut_usuario);
                $stmt_per->execute();
                $result_per = $stmt_per->get_result();

                if ($result_per && $datos_per = $result_per->fetch_object()) {
                    $_SESSION["id_per"] = $datos_per->id_per;
                    $_SESSION["id_emp"] = null; // Asegurarse de que id_emp sea nulo
                } else {
                    $stmt_emp = $conexion->prepare("SELECT id_emp FROM empresa WHERE rut_usuario = ?");
                    $stmt_emp->bind_param("s", $rut_usuario);
                    $stmt_emp->execute();
                    $result_emp = $stmt_emp->get_result();

                    if ($result_emp && $datos_emp = $result_emp->fetch_object()) {
                        $_SESSION["id_emp"] = $datos_emp->id_emp;
                        $_SESSION["id_per"] = null; // Asegurarse de que id_per sea nulo
                    }
                }

                // Redirigir a la página de retiro
                header("Location: retiro.php");
                exit(); // Detener el script después de la redirección
            } else {
                echo "<div>Contraseña incorrecta.</div>";
            }
        } else {
            echo "<div>Usuario no registrado.</div>";
        }

        $stmt->close();
    } else {
        echo "<div>Campos vacíos.</div>";
    }
}
?>
