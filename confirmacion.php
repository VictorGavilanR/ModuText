<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION["rut_usuario"])) {
    // Si no hay sesión activa, redirigir al login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Solicitud</title>
    <!-- Incluir estilo para un diseño atractivo (opcional) -->
    <link rel="stylesheet" href="estilos.css">  <!-- O puedes agregar el estilo que prefieras -->
</head>
<body>

    <div class="container">
        <h1>¡Gracias por tu Solicitud!</h1>
        <p>Tu solicitud de retiro de telas ha sido enviada con éxito. Un miembro del equipo se pondrá en contacto contigo pronto.</p>
        
        <h2>Detalles de la Solicitud:</h2>
        <ul>
            <li><strong>Rut Usuario:</strong> <?php echo $_SESSION['rut_usuario']; ?></li>
            <li><strong>Email:</strong> <?php echo $_SESSION['email_usuario']; ?></li>
            <li><strong>Tipo de Tela:</strong> <?php echo $_POST['tipoTela']; ?></li>
            <li><strong>Cantidad:</strong> <?php echo $_POST['cantidad']; ?> kg</li>
            <li><strong>Dirección de Retiro:</strong> <?php echo $_POST['direccionRetiro']; ?></li>
        </ul>

        <a href="index.php" class="btn">Volver al Inicio</a>
       
    </div>

    <!-- estilo :v -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #4CAF50;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>

</body>
</html>