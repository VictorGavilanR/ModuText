<?php
session_start();

// Incluir las clases necesarias de PHPMailer
require 'PHPMailer/PHPMailer.php';  
require 'PHPMailer/SMTP.php';       
require 'PHPMailer/Exception.php';  
include 'conexion.php';

// Importar las clases desde el espacio de nombres correcto
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Verificar si el usuario está logueado
if (isset($_SESSION["rut_usuario"]) && isset($_SESSION["email_usuario"])) {
    $rutUsuario = $_SESSION["rut_usuario"];
    $usuarioEmail = $_SESSION["email_usuario"];
} else {
    echo "Error: No se han encontrado los datos del usuario en la sesión.";
    exit();
}

// Obtener datos del formulario
$tipoTela = $_POST['tipoTela'];
$cantidad = $_POST['cantidad'];
$direccionRetiro = $_POST['direccionRetiro'];

// Información del usuario
if (isset($_SESSION['email_usuario'])) {
    $usuarioEmail = $_SESSION['email_usuario']; // Ahora el correo está en la sesión
} else {
    // Si no existe el correo en la sesión, mostrar un error
    echo "Error: No se ha encontrado el correo del usuario en la sesión.";
    exit();
}

// Obtener datos del formulario
$rut_usuario = $_SESSION['rut_usuario'];
$fechaSolicitud = date('Y-m-d H:i:s');

// Validar que la cantidad es un número válido
if (is_numeric($cantidad) && $cantidad > 0) {
    // Obtener el ID del residuo en la tabla `residuo` según el tipo de tela
    $queryRes = "SELECT id_res FROM residuo WHERE nombre_res = ?";
    $stmtRes = $conexion->prepare($queryRes);

    // Verificar si la preparación de la consulta falló
    if ($stmtRes === false) {
        // Mostrar error de preparación de la consulta
        echo "Error al preparar la consulta de tipo de tela: " . $conexion->error;
        exit();
    }

    $stmtRes->bind_param("s", $tipoTela);
    $stmtRes->execute();
    $resultRes = $stmtRes->get_result();

    if ($resultRes->num_rows > 0) {
        $rowRes = $resultRes->fetch_assoc();
        $id_residuo = $rowRes['id_res'];

        // Insertar la solicitud en la tabla `solicitud` con `cant_res` para la cantidad de kilos
        $queryInsert = "INSERT INTO solicitud (rut_usuario, id_dir, id_res, fecha_sol, cant_res) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conexion->prepare($queryInsert);

        // Verificar si la preparación de la consulta de inserción falló
        if ($stmtInsert === false) {
            // Mostrar error de preparación de la consulta
            echo "Error al preparar la consulta de inserción: " . $conexion->error;
            exit();
        }

        $stmtInsert->bind_param("siiss", $rut_usuario, $direccionRetiro, $id_residuo, $fechaSolicitud, $cantidad);

        // Ejecutar la consulta de inserción
        $stmtInsert->execute();
    } else {
        echo "No se encontró el tipo de tela especificado.";
    }

    $stmtRes->close();
    $stmtInsert->close();
} else {
    echo "Cantidad no válida.";
}

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(); 
try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'poner correo'; // Tu dirección de correo de Gmail
$mail->Password = 'contraseña'; // Contraseña de aplicación (sin espacios)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
    $mail->SMTPDebug = 2;

    // Habilitar la depuración SMTP
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    // Configurar los datos del mensaje
    $mail->setFrom('correo ', 'Equipo de Retiro de Telas');
    $mail->addAddress($usuarioEmail);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Confirmacion de Solicitud de Retiro de Telas';
    $mail->Body = "
    <p>Estimado/a {$_SESSION['rut_usuario']},</p>
    <p>Gracias por enviar su solicitud de retiro de telas. A continuación los detalles de su solicitud:</p>
    <ul>
        <li><strong>Tipo de Tela:</strong> $tipoTela</li>
        <li><strong>Cantidad:</strong> $cantidad kg</li>
        <li><strong>Dirección de Retiro:</strong> $direccionRetiro</li>
    </ul>
    <p>Nos pondremos en contacto pronto para confirmar los detalles.</p>
    <p>Saludos,<br>Equipo de Retiro de Telas</p>";

    // Enviar el correo
    $mail->send();
    echo "Correo enviado con éxito.";
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>