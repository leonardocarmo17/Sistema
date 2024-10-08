<?php
include('../conexao.php'); 
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
                
                $mail->setFrom('', ''); // primeiro é o email, e depois o nome que você quer
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
<body>
    <div class="modo">
        <input type="checkbox" class="checkbox" id="chk">
        <label class="label" for="chk">
            <i class="fas fa-moon"></i>
            <i class="fas fa-sun"></i>
            <div class="bola"></div>
        </label>
    </div>
    
        <div class="container">
        <div class="login">
            <div class="content">
                <img src="../img/logo.png" alt="">
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
                    <p style="text-align: center"> Voltar para <a href="../home.php">Tela Inicial.</a></p>
                    <br>
                    <p style="text-align: center">Faça seu <a href="../login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script src="../js/recuperar.js"></script> 
    <script src="../js/login.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script src="https://kit.fontawesome.com/998c60ef77.js" crossorigin="anonymous"></script>
</body>
</html>
