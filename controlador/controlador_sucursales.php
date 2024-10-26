<?php
session_start();
include "../conexion.php";

//Validar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa.<br>";
}

//Validar datos recibidos
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $nom_suc=htmlspecialchars($_POST["nombre"]);
    $fono_suc=filter_input(INPUT_POST,"fono",FILTER_SANITIZE_NUMBER_INT);
    $codpost_suc=filter_input(INPUT_POST,"cod_post",FILTER_SANITIZE_NUMBER_INT);
    $calle_suc=htmlspecialchars($_POST["calle"]);
    $num_calle_suc=filter_input(INPUT_POST,"num_calle",FILTER_SANITIZE_NUMBER_INT);
    $rut_cli="20.042.429-8";  //DE PRUEBA

    //Control de errores
    $errores=false;

    //Campos vacíos:
    if (empty($nom_suc) || empty($fono_suc) || empty($codpost_suc)){
        echo "<p class='cal-error'>Complete todos los campos.</p>";
        $errores=true;
    }

    //No numeros
    if (!is_numeric($fono_suc) || !is_numeric($codpost_suc)){
        echo "<p class='calc-error'>Ingrese solo números.</p>";
        $errores=true;
    }

    //Registro en BD
    if (!$errores){
        echo "Nombre sucursal: " . $nom_suc . 
        "Fono: " . $fono_suc .
        "Código postal: " . $codpost_suc;

        //Preparación de consulta:
        $stmt = $conexion->prepare("INSERT INTO sucursales (rut_cli, nom_suc, cod_post_suc, fono_suc, calle_suc, num_calle_suc) VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt){
            echo "Consulta preparada correctamente.<br>";
            $stmt->bind_param("ssissi",$rut_cli, $nom_suc, $codpost_suc, $fono_suc, $calle_suc, $num_calle_suc);

            if ($stmt->execute()){
                echo "Sucursal registrada exitosamente.";
            } else {
                echo "Error en la inserción: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " - $conexion->error;
        }

        //Cierre de conexión
        $conexion->close();

    } else{
        echo "Otro error";
    }
}