<?php
session_start();
header('Content-Type: application/json'); // Aseguramos que la respuesta sea JSON

// Incluir PHPMailer y conexión
require 'PHPMailer/PHPMailer.php';  
require 'PHPMailer/SMTP.php';       
require 'PHPMailer/Exception.php';  
include 'conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Preparar la respuesta JSON
$response = array(
    'status' => 'error',
    'message' => 'Ocurrió un error desconocido'
);

try {
    // Verificar si el usuario está logueado
    if (!isset($_SESSION["rut_usuario"]) || !isset($_SESSION["email_usuario"])) {
        throw new Exception('No se han encontrado los datos del usuario en la sesión.');
    }

    $rutUsuario = $_SESSION["rut_usuario"];
    $usuarioEmail = $_SESSION["email_usuario"];

    // Verificar si se recibieron los datos del formulario
    if (!isset($_POST['tipoTela']) || !isset($_POST['cantidad']) || !isset($_POST['direccionRetiro'])) {
        throw new Exception('Datos del formulario incompletos.');
    }

    // Obtener datos del formulario
    $tipoTela = $_POST['tipoTela'];
    $cantidad = $_POST['cantidad'];
    $direccionRetiro = $_POST['direccionRetiro'];

    // Validar cantidad
    if (!is_numeric($cantidad) || $cantidad <= 0) {
        throw new Exception('Cantidad no válida.');
    }

    // Obtener la dirección completa
    $queryDir = "SELECT nom_dir, calle_dir, num_calle_dir, comuna_dir 
                 FROM direccion_retiro 
                 WHERE id_dir = ?";
    $stmtDir = $conexion->prepare($queryDir);
    
    if (!$stmtDir) {
        throw new Exception('Error al preparar la consulta de dirección: ' . $conexion->error);
    }

    $stmtDir->bind_param("i", $direccionRetiro);
    $stmtDir->execute();
    $resultDir = $stmtDir->get_result();

    if ($resultDir->num_rows == 0) {
        throw new Exception('No se encontró la dirección especificada.');
    }

    $rowDir = $resultDir->fetch_assoc();
    $direccion = $rowDir['nom_dir'] . ", " . $rowDir['calle_dir'] . " " . $rowDir['num_calle_dir'] . ", " . $rowDir['comuna_dir'];
    $stmtDir->close();

    // Obtener ID del residuo
    $queryRes = "SELECT id_res FROM residuo WHERE nombre_res = ?";
    $stmtRes = $conexion->prepare($queryRes);
    
    if (!$stmtRes) {
        throw new Exception('Error al preparar la consulta de residuo: ' . $conexion->error);
    }

    $stmtRes->bind_param("s", $tipoTela);
    $stmtRes->execute();
    $resultRes = $stmtRes->get_result();

    if ($resultRes->num_rows == 0) {
        throw new Exception('No se encontró el tipo de tela especificado.');
    }

    $rowRes = $resultRes->fetch_assoc();
    $id_residuo = $rowRes['id_res'];
    $stmtRes->close();

    // Insertar solicitud
    $fechaSolicitud = date('Y-m-d H:i:s');
    $queryInsert = "INSERT INTO solicitud (rut_usuario, id_dir, id_res, fecha_sol, cant_res) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conexion->prepare($queryInsert);
    
    if (!$stmtInsert) {
        throw new Exception('Error al preparar la consulta de inserción: ' . $conexion->error);
    }

    $stmtInsert->bind_param("siiss", $rutUsuario, $direccionRetiro, $id_residuo, $fechaSolicitud, $cantidad);
    
    if (!$stmtInsert->execute()) {
        throw new Exception('Error al insertar la solicitud: ' . $stmtInsert->error);
    }
    
    $stmtInsert->close();

    // Enviar correo
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'drackracer@gmail.com';
    $mail->Password = 'iyphkooslbxszvsc';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPDebug = 0; // Desactivar debug

    // Configuración del correo
    
    $mail->CharSet = 'UTF-8'; // Asegura que los caracteres especiales se muestren correctamente
    $mail->setFrom('drackracer@gmail.com', 'Equipo de Retiro de Telas Modutext'); // Remitente
    $mail->addAddress($usuarioEmail); // Correo del destinatario
    $mail->addAddress('drackracer@gmail.com'); // Opcional: Copia para el remitente

    // Configuración del contenido
    $mail->isHTML(true);
    $mail->Subject = 'Confirmación de Solicitud de Retiro de Telas Modutext';
    $mail->Body = "
    <p>Estimado/a {$_SESSION['rut_usuario']},</p>
    <p>Gracias por enviar su solicitud de retiro de telas Modutext. A continuación, los detalles de su solicitud:</p>
    <ul>
        <li><strong>Tipo de Tela:</strong> $tipoTela</li>
        <li><strong>Cantidad:</strong> $cantidad kg</li>
        <li><strong>Dirección de Retiro:</strong> $direccion</li>
    </ul>
    <p>Nos pondremos en contacto pronto para confirmar los detalles.</p>
    <p>Saludos,<br>Equipo de Retiro de Telas</p>";
    
    // Enviar el correo
    $mail->send();

    // Respuesta de éxito
    $response['status'] = 'success';
    $response['message'] = 'Solicitud enviada correctamente. Se ha enviado un correo de confirmación.';

} catch (Exception $e) {
    // Manejo de errores
    $response['status'] = 'error';
    $response['message'] = 'Error al enviar el correo: ' . $e->getMessage();
} finally {
    // Cerrar conexión si está definida
    if (isset($conexion)) {
        $conexion->close();
    }
}

// Enviar respuesta en formato JSON
echo json_encode($response);
exit;