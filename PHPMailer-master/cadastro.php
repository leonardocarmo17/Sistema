<?php
$emailExists = false;
$error = '';
$result = null;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';


$mail = new PHPMailer(true);
if (isset($_POST['submit'])) {
    include_once('../php/conexao.php');

    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $sobre = mysqli_real_escape_string($conexao, $_POST['sobre']);
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
    $data_nasc = mysqli_real_escape_string($conexao, $_POST['data_nascimento']);
    $arquivoExcluido = $_POST['arquivo_excluido'];
   
    $nomeDoArquivo = null;
    $path = null;
   
    if ($arquivoExcluido !== '1' && isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
       
        $arquivo = $_FILES['imagem'];
        $nomeDoArquivo = $arquivo['name'];
        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
        $pasta = "../arquivos/";
        $novoNomeDoArquivo = uniqid() . '.' . $extensao;
        $path = $pasta . $novoNomeDoArquivo;

        if ($arquivo['size'] > 2000000) { 
            $error = 'Arquivo muito grande, máximo de 2MB.';
        } elseif (!in_array($extensao, ['jpg', 'png'])) {
            $error = 'Tipo de arquivo não aceito. Apenas JPG ou PNG!';
        } elseif (!move_uploaded_file($arquivo['tmp_name'], $path)) {
            $error = 'Falha ao mover arquivo para a pasta de upload.';
        }
    }
   
   
    if ($error === '') {
        
        $email_check_query = "SELECT * FROM usuarios WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conexao, $email_check_query);

        if ($result && mysqli_num_rows($result) > 0) {
            $emailExists = true;
            $error = 'Este email já está em uso, utilize outro email.';
        } else {

            $token = bin2hex(random_bytes(16));
            $query = "INSERT INTO usuarios (nome, sobre, email, senha, data_nasc, nome_arquivo, path, niveldeacesso, token, ativo) 
          VALUES ('$nome', '$sobre', '$email', '$senha', '$data_nasc', '$nomeDoArquivo', '$path', 1, '$token', 0)";

            if (mysqli_query($conexao, $query)) {
                

                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Username = 'leonardocarmoc@gmail.com';  //Seu email
                $mail->Password = 'nsse ypiy twyt irfa'; // Codigo para ser enviado, senha gerada pelo google
                $mail->SMTPSecure = 'tls'; 
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                
                $mail->setFrom('leonardocarmoc@gmail.com', 'Verificar Conta');
                $mail->addAddress($email, $nome);
                $mail->isHTML(true);
                $mail->Subject = $nome . ', Sistema.';
                $mail->Body = '
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                background: linear-gradient(to right, #e0f2f1, #a5d6a7);
                font-family: "Arial", sans-serif;
                text-align: center;
                padding: 30px;
            }
            .base {
                background-color: #ffffff;
                border-radius: 15px;
                width: 90%; 
                max-width: 750px; 
                padding: 40px; 
                margin: auto;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }
            .textinho {
                font-size: 24px;
                font-weight: bold;
                color: #333;
                margin-bottom: 20px;
            }
            .mensagem {
                font-size: 18px;
                color: #555;
                margin-bottom: 30px;
                line-height: 1.5;
            }
            .confirma {
                margin-top: 30px;
            }
            .confirma a {
                display: inline-block;
                background: #28a745;
                color: white;
                border-radius: 50px;
                padding: 16px 50px;
                font-size: 22px;
                font-weight: bold;
                text-decoration: none;
                transition: 0.3s;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            }
            .confirma a:hover {
                background: #218838;
                transform: scale(1.05);
                box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
            }
            .rodape {
                margin-top: 40px;
                font-size: 14px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="base">
            <div class="textinho">
                <b>Olá, ' . $nome . '!</b>
            </div>
            <div class="mensagem">
                <p>Estamos felizes em ter você aqui! Para ativar sua conta, basta clicar no botão abaixo.</p>
            </div>
            <div class="confirma">
                <a href="http://sistemabasico.great-site.net/../PHPMailer-master/confirmar.php?token=' . $token . '">
                    Verificar Conta
                </a>
            </div>
            <div class="rodape">
                <p>Se você não se cadastrou, ignore este e-mail.</p>
            </div>
        </div>
    </body>
    </html>';

            

    $mail->send();

} else {
    $error = 'Erro ao registrar: ' . mysqli_error($conexao);
}
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="../css/modo.css">
    <script type="text/javascript" src="../js/modo.js" defer></script>

    <title>Cadastro</title>
</head>
<style>
    .base {
    border: 20px solid rgb(231, 231, 231);
    background-color: rgb(255, 251, 251);
    border-radius: 5%;
    width: 90%; 
    max-width: 700px; 
    height: auto; 
    padding: 20px; 
    margin: 0 auto; 
    position: absolute;
    top: 50%; 
    left: 50%; 
    transform: translate(-50%, -50%);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
}

.textinho {
    margin-bottom: 20px;
    text-align: center;
    font-size: 20px;
    word-spacing: 1px;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: 300;
}
.textinho p{
    color: black;
}
.textinho a{
    text-decoration: none;
}
</style>
<body class="darkmode">
<button id="theme-switch">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="blue"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="blue"><path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Zm326-268Z"/></svg>
    </button>
    <?php if ($result && mysqli_num_rows($result) < 1): ?> 
    <div class="base">
        <div class="textinho">
            <p> Verifique a sua conta no E-mail: <br>
            <b><?php echo $email; ?></b> <br>
            para ter acesso ao site!</p>
            <br>
            <p>Redirecionar para o<a href="../login.php"> <b>Login</b></a></p>
        </div>
    </div>
    <?php else: ?>
    <div class="container" id="primeiraopc">
        <div class="login">
            <div class="content">
                <img src="../img/logoFinal.png" alt="">
            </div>
            <div class="loginform">
                <h1>Cadastre-se</h1>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon>
                        <input type="text" name="nome" id="nome" placeholder="Nome" minlength="3" required>
                    </div>
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon>
                        <input type="text" name="sobre" id="sobre" placeholder="Sobrenome" minlength="3" required>
                    </div>
                    <div class="tbox">
                        <ion-icon name="at-outline"></ion-icon>
                        <input type="email" name="email" id="email" placeholder="Email" minlength="12" required>
                    </div>
                    <div class="tbox">
                        <ion-icon name="lock-closed"></ion-icon>
                        <input type="password" name="senha" id="senha" placeholder="Senha" minlength="5" required>
                    </div>
                    <div class="tbox">
                        <ion-icon name="calendar-outline" onclick="document.getElementById('data_nascimento').focus();"></ion-icon>
                        <input type="date" name="data_nascimento" id="data_nascimento" max="" min='1910-01-01' required>
                    </div>
                    <div class="tbox">
                        <ion-icon name="camera-outline"></ion-icon>
                        <input name="imagem" type="file" id="imagem" onchange="updateFileName(); previewImagem()">
                        <label for="imagem" class="texto" id="file-label">Selecionar</label>
                        <button type="button" onclick="deleteArquivo()" class="deleteicon">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                        <input type="hidden" name="arquivo_excluido" id="arquivo_excluido" value="0">
                    </div>
                    <img id="preview" style="width: 150px; height: 150px; display: none; border: 1px solid #55efc4;"><br><br>
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="delete_image" id="delete_image" value="0">
                    <input type="submit" name="submit" id="submit" value="Enviar" class="btn">
                    <br><br>
                    <?php if ($error): ?>
                        <p style="color: red;"><?php echo $error; ?></p>
                    <?php endif; ?>
                </form>
                <br>
                <div class="subTitulo">
                    <h2 style="text-align: center">Registrado? Entre <a href="../php/login.php">Aqui.</a></h2>
                    <br>
                    <p style="text-align: center">Voltar para <a href="../php/home.php">Tela Inicial.</a></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
   
    <script src="../js/cadastro.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
<script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>

</body>
</html>
