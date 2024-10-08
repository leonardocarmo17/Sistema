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
    include_once('../conexao.php');

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
                $mail->Password = 'qaoc scic ioop hppi'; // Codigo para ser enviado, senha gerada pelo google
                $mail->SMTPSecure = 'tls'; 
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                
                $mail->setFrom('leonardocarmoc@gmail.com', 'Verificar Conta');
                $mail->addAddress($email, $nome);
                $mail->isHTML(true);
                $mail->Subject = $nome . ', Verifique sua Conta.';
                   $mail->Body = '
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <style>
            .base {
                border: 20px solid rgb(231, 231, 231);
                background-color: rgb(255, 251, 251);
                border-radius: 5%;
                width: 80%; 
                max-width: 700px; 
                padding: 20px; 
                margin: 0 auto; 
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
            }
            .textinho {
                margin-bottom: 20px;
                text-align: center;
                font-size: 20px;
                font-family: Arial, Helvetica, sans-serif;
                font-weight: 600;
                color: black;
                text-decoration: none;
            }
            .confirma {
                display: flex; 
                justify-content: center; 
                align-items: center; 
            }
            .confirma a {
                background-color: #4CAF50; 
                color: white; 
                border: none; 
                border-radius: 5px;
                padding: 20px 40px; 
                font-size: 25px; 
                cursor: pointer; 
                text-decoration: none;
                text-align: center;
                margin: 0 auto;
                transition: background-color 0.3s;
            }
            .confirma a:hover {
                background-color: #45a049; 
            }
        </style>
    </head>
    <body>
        <div class="base">
            <div class="textinho">
                <b>Olá, ' . $nome . '!</b>
                <p>Verifique sua conta clicando no link abaixo:</p>
            </div>
            <div class="confirma">
                <a href="http://sistemacriadocomphp.great-site.net/../PHPMailer-master/confirmar.php?token=' . $token . '">
                    Verificar Conta
                </a>
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
<body>

    <div class="modo">
        <input type="checkbox" class="checkbox" id="chk">
        <label class="label" for="chk">
            <i class="fas fa-moon"></i>
            <i class="fas fa-sun"></i>
            <div class="bola"></div>
        </label>
    </div>
   
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
                    <h2 style="text-align: center">Registrado? Entre <a href="../login.php">Aqui.</a></h2>
                    <br>
                    <p style="text-align: center">Voltar para <a href="../home.php">Tela Inicial.</a></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
   
    <script src="../js/cadastro.js"></script>
    <script src="../js/login.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/998c60ef77.js" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</body>
</html>
