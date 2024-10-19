<?php
session_start();

// Verificar datos con la base de datos
if (isset($_POST["btningresar"])) {
    if (!empty($_POST["rut"]) && !empty($_POST["password"])) {

<<<<<<< HEAD
        $rut=$_POST["rut"];
        $password=$_POST["password"];
        $sql = $conexion-> query(" select * from usuarios where rut='$rut' and password='$password' ");
        if ($datos= $sql->fetch_object()) {

            $_SESSION["id"]=$datos->id;
            $_SESSION["rut"]=$datos->rut;
            $_SESSION["nombre"]=$datos->nombres;
=======
        $rut = $_POST["rut"];
        $password = $_POST["password"];

        // Asegúrate de que la conexión a la base de datos esté establecida
        include "conexion.php"; // Asegúrate de que esta línea esté incluida

        $sql = $conexion->query("SELECT * FROM usuarios WHERE rut='$rut' AND password='$password'");
        if ($sql && $datos = $sql->fetch_object()) {
            // Establecer las variables de sesión con los datos del usuario
            $_SESSION["id"] = $datos->id;
            $_SESSION["rut"] = $datos->rut;
            $_SESSION["nombre"] = $datos->nombre; // Aquí asegúrate que 'nombre' es el campo correcto en tu tabla

            // Redirigir a la página de retiro
>>>>>>> 0b634754c4d833369b1fdb5be56e3f4dfa391252
            header("location: retiro.php");
            exit(); // Detener el script después de la redirección
        } else {
            echo "<div>Datos incorrectos o usuario no registrado.</div>";
        }

    } else {
        echo "Campos vacíos.";
    }
}
?>
