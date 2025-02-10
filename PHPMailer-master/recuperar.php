<?php
include('../php/conexao.php'); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mensagem = '';

$erro = array(); 
$mail = new PHPMailer(true);
if (isset($_POST['ok'])) {
    $email = $conexao->escape_string($_POST['email']); 
    $nome = $conexao->escape_string($_POST['nome']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro[] = "E-mail inválido."; 
    }

    if (count($erro) == 0) {
       
        $sql_code = "SELECT * FROM usuarios WHERE email = '$email'";
        $sql_query = $conexao->query($sql_code) or die($conexao->error);

        if ($sql_query->num_rows == 0) {
            $erro[] = "E-mail não cadastrado. Tente outro e-mail.";
        } else {
            $novasenha = substr(md5(time()), 0, 6);
            $nscriptografada = md5(md5($novasenha)); 
            
            try {
                
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Username   = '';  //Seu email
                $mail->Password   = ''; // Codigo para ser enviado, senha gerada pelo google
                $mail->SMTPSecure = 'tls'; 
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                
                $mail->setFrom('', 'Recupere sua conta'); // primeiro é o email, e depois o nome que você quer
                $mail->addAddress($email, $nome);
                $mail->isHTML(true);
                $mail->Subject = $nome . ', Senha nova';
                $mail->Body    = '<b>Olá!</b> A sua nova senha foi redefinida: ' . $nscriptografada;

               
                if ($mail->send()) {
                    
                    $sql_code = "UPDATE usuarios SET senha = '$nscriptografada' WHERE email = '$email'";
                    $sql_query = $conexao->query($sql_code) or die($conexao->error);
                    $mensagem = 'Senha enviada para o email';
                } else {
                    throw new Exception("Falha ao enviar e-mail.");
                }
            }               
            catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="../css/recuperar.css">
    <link rel="stylesheet" href="../css/modo.css">
    <script type="text/javascript" src="../js/modo.js" defer></script>
    
    <style>
        .saia {
            display: none;
        }
        <?php if (!empty($mensagem)) : ?>
        .saia {
            display: inline-block;
        }
        <?php endif; ?>
    </style>
</head>
<body class="darkmode">
  
        <button id="theme-switch">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="blue"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="blue"><path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Zm326-268Z"/></svg>
    </button>
        <div class="container">
        <div class="login">
            <div class="content">
                <img src="../img/logoFinal.png" alt="">
            </div>
            <div class="loginform">
                <h1>Recuperar Senha</h1>
                <form action="" method="POST">
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon><input type="text" name="nome" id="nome" placeholder="Nome">
                    </div>
                    <div class="tbox">
                        <ion-icon name="at-outline"></ion-icon><input type="email" name="email" id="email" placeholder="E-mail">
                    </div>
                    <input type="submit" name="ok" id="submit" value="Enviar" class="btn">
                </form>
                <?php 
       
        if (!empty($erro)) {
            foreach ($erro as $e) {
                echo "<p style='color: red; text-align:center; margin-top: 20px; margin-bottom: -15px;'><ion-icon name='close-outline'></ion-icon>$e</p>";
            }
        } elseif (isset($mensagem)) {
            $mensagemoficial = "<p style='color: green; text-align:center; margin-top: 20px; margin-bottom: -15px;'><ion-icon class='saia' name='checkmark-outline'></ion-icon> $mensagem </p>";
            echo $mensagemoficial;
        }
        ?>
                <br>
                <div class="subTitulo">
                    <br>
                    <p style="text-align: center"> Voltar para <a href="../php/home.php">Tela Inicial.</a></p>
                    <br>
                    <p style="text-align: center">Faça seu <a href="../php/login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script src="../js/recuperar.js"></script> 
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>
