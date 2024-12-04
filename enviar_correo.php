<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';  
require 'PHPMailer/SMTP.php';       
require 'PHPMailer/Exception.php';  

// Asegurarse de que es una petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'drackracer@gmail.com'; // Tu correo
            $mail->Password = 'iyphkooslbxszvsc'; // Tu contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('drackracer@gmail.com', 'Formulario de Contacto');
            $mail->addAddress('drackracer@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'Nuevo mensaje del formulario de contacto Modutext';
            $mail->Body = "
                <h1>Nuevo Mensaje</h1>
                <p><strong>Nombre:</strong> $name</p>
                <p><strong>Correo:</strong> $email</p>
                <p><strong>Teléfono:</strong> $phone</p>
                <p><strong>Mensaje:</strong><br>$message</p>
            ";

            $mail->send();
            // Enviar respuesta JSON
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => '¡Correo enviado !']);
        } catch (Exception $e) {
            // Enviar error en formato JSON
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Error al enviar el Correo: ' . $mail->ErrorInfo]);
        }
    } else {
        // Enviar error de validación en formato JSON
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
    }
} else {
    // Enviar error de método en formato JSON
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>