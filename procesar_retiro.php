<?php
session_start();

// Incluir PHPMailer y conexión
require 'PHPMailer/PHPMailer.php';  
require 'PHPMailer/SMTP.php';       
require 'PHPMailer/Exception.php';  
include 'conexion.php';

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
$direccionRetiro = $_POST['direccionRetiro']; // Esto contiene el id_dir enviado desde el formulario

// Validar cantidad
if (!is_numeric($cantidad) || $cantidad <= 0) {
    echo "Cantidad no válida.";
    exit();
}

// Obtener la dirección completa desde la base de datos usando el id_dir
$queryDir = "SELECT nom_dir, calle_dir, num_calle_dir, comuna_dir 
             FROM direccion_retiro 
             WHERE id_dir = ?";
$stmtDir = $conexion->prepare($queryDir);

if ($stmtDir === false) {
    echo "Error al preparar la consulta de dirección: " . $conexion->error;
    exit();
}

$stmtDir->bind_param("i", $direccionRetiro);
$stmtDir->execute();
$resultDir = $stmtDir->get_result();

if ($resultDir->num_rows > 0) {
    $rowDir = $resultDir->fetch_assoc();
    $direccion = $rowDir['nom_dir'] . ", " . $rowDir['calle_dir'] . " " . $rowDir['num_calle_dir'] . ", " . $rowDir['comuna_dir'];
} else {
    echo "Error: No se encontró la dirección especificada.";
    exit();
}

$stmtDir->close();

// Insertar solicitud en la base de datos
$fechaSolicitud = date('Y-m-d H:i:s');
$queryInsert = "INSERT INTO solicitud (rut_usuario, id_dir, id_res, fecha_sol, cant_res) VALUES (?, ?, ?, ?, ?)";
$stmtInsert = $conexion->prepare($queryInsert);

if ($stmtInsert === false) {
    echo "Error al preparar la consulta de inserción: " . $conexion->error;
    exit();
}

$queryRes = "SELECT id_res FROM residuo WHERE nombre_res = ?";
$stmtRes = $conexion->prepare($queryRes);

$stmtRes->bind_param("s", $tipoTela);
$stmtRes->execute();
$resultRes = $stmtRes->get_result();

if ($resultRes->num_rows > 0) {
    $rowRes = $resultRes->fetch_assoc();
    $id_residuo = $rowRes['id_res'];
    $stmtInsert->bind_param("siiss", $rutUsuario, $direccionRetiro, $id_residuo, $fechaSolicitud, $cantidad);
    $stmtInsert->execute();
} else {
    echo "No se encontró el tipo de tela especificado.";
    exit();
}

$stmtInsert->close();
$stmtRes->close();

// Configurar PHPMailer para enviar el correo
$mail = new PHPMailer(); 
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'drackracer@gmail.com';
    $mail->Password = 'iyphkooslbxszvsc';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    $mail->setFrom('drackracer@gmail.com', 'Equipo de Retiro de Telas');
    $mail->addAddress($usuarioEmail);
    $mail->addAddress('drackracer@gmail.com'); // Copia al administrador

    $mail->isHTML(true);
    $mail->Subject = 'Confirmacion de Solicitud de Retiro de Telas';
    $mail->Body = "
    <p>Estimado/a {$_SESSION['rut_usuario']},</p>
    <p>Gracias por enviar su solicitud de retiro de telas. A continuación, los detalles de su solicitud:</p>
    <ul>
        <li><strong>Tipo de Tela:</strong> $tipoTela</li>
        <li><strong>Cantidad:</strong> $cantidad kg</li>
        <li><strong>Dirección de Retiro:</strong> $direccion</li>
    </ul>
    <p>Nos pondremos en contacto pronto para confirmar los detalles.</p>
    <p>Saludos,<br>Equipo de Retiro de Telas</p>";

    $mail->send();
    echo "Correo enviado con éxito al usuario y al administrador.";
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}