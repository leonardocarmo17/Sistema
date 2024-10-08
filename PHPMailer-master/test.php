<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);

try {
    
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Username   = ''; // Seu e-mail Gmail
    $mail->Password   = ''; // Use a senha de aplicativo gerada
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;

    // Remetente e destinatário
    $mail->setFrom('', ''); 
    $mail->addAddress('', ''); 

    // Conteúdo do e-mail
    $mail->isHTML(true);
    $mail->Subject = 'Teste Envio de Email';
    $mail->Body    = 'Este é o corpo da mensagem <b>Olá!</b>';
    $mail->AltBody = 'Este é o corpo da mensagem para clientes de e-mail que não reconhecem HTML';

    // Envio do e-mail
    $mail->send();
    echo 'A mensagem foi enviada!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
