<?php

session_start();


// Verificar datos con la base de datos
if (isset($_POST["btningresar"])) {
    if (!empty($_POST["rut"]) and !empty($_POST["password"])) {

        $rut=$_POST["rut"];
        $password=$_POST["password"];
        $sql = $conexion-> query(" select * from usuario where rut='$rut' and password='$password' ");
        if ($datos= $sql->fetch_object()) {

            $_SESSION["id"]=$datos->id;
            $_SESSION["rut"]=$datos->rut;
            header("location: retiro.php");
        } else {
            echo "<div>Datos Incorrectos y/0 usuario no registrado </div>";
        }
        

    }else{
        echo "Campos Vacios";
    }
}
?>