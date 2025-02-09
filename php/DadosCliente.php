<?php
session_start();
include_once('conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

// Obtém os dados do usuário logado
$sqlSelect = "SELECT id, nome, sobre, email, senha, data_nasc, path FROM usuarios WHERE email = ?";
$stmt = $conexao->prepare($sqlSelect);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = (int) $user['id'];
    $nome = htmlspecialchars($user['nome']);
    $sobre = htmlspecialchars($user['sobre']);
    $email = htmlspecialchars($user['email']);
    $senha = htmlspecialchars($user['senha']);
    $data_nasc = htmlspecialchars($user['data_nasc']);
    $path = htmlspecialchars($user['path']);
} else {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Verifica se o ID foi passado na URL e se é válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: home.php');
    exit;
}

$id = (int)$_GET['id'];

// Se o usuário tentar editar outro ID que não seja o seu próprio, redireciona para home
if ($id !== $userId) {
    header('Location: home.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/editSistema.css">
    <link rel="stylesheet" href="../css/modo.css">
    <script type="text/javascript" src="../js/modo.js" defer></script>

    <title>Editar Cadastro</title>
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
                <h1>Edite seu Cadastro</h1>
                <form method="POST" action="saveEdit2.php" enctype="multipart/form-data">
                    <label for="nome">Nome:</label>
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon>
                        <input type="text" name="nome" id="nome" placeholder="Nome" value="<?php echo htmlspecialchars($nome) ?>" minlength="3" required>
                    </div>
                    <label for="sobre">Sobrenome:</label>
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon>
                        <input type="text" name="sobre" id="sobre" placeholder="Sobrenome" value="<?php echo htmlspecialchars($sobre) ?>" minlength="3" required>
                    </div>
                    <label for="email">Email:</label>
                    <div class="tbox">
                        <ion-icon name="at-outline"></ion-icon>
                        <input type="email" name="email" id="email" style="color: gray;" placeholder="Email" value="<?php echo htmlspecialchars($email) ?>" minlength="12" readonly>
                    </div>
                    <label for="senha">Senha:</label>
                    <div class="tbox">
                        <ion-icon name="lock-closed"></ion-icon>
                        <input type="text" name="senha" id="senha" placeholder="Senha" value="<?php echo htmlspecialchars($senha) ?>" minlength="5" required>
                    </div>
                    <label for="data_nascimento">Data de Nascimento:</label>
                    <div class="tbox">
                        <ion-icon name="calendar-outline" onclick="document.getElementById('data_nascimento').focus();"></ion-icon>
                        <input type="date" name="data_nascimento" id="data_nascimento" max="" min='1910-01-01' value="<?php echo $data_nasc ?>" required>
                    </div>
                    <label for="foto">Foto:</label>
                    <div class="tbox">
                        <ion-icon name="camera-outline"></ion-icon>
                        <input name="arquivo" type="file" id="arquivo" onchange="updateFileName(); previewImagem()">
                        <label for="arquivo" class="texto" id="file-label">Selecionar</label>
                        <button type="button" onclick="deleteArquivo()" class="deleteicon">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                        <input type="hidden" name="arquivo_excluido" id="arquivo_excluido" value="0">
                    </div>
                    <img id="preview" style="display: none; margin-top: 30px; margin-bottom: -10px;"><br><br>
                    <?php if (!empty($path)) : ?>
                        <div class="img-preview">
                            <img id="preview-existente" src="<?php echo htmlspecialchars($path); ?>" style="margin-bottom: 25px; margin-top: -20px;">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id) ?>">
                    <input type="hidden" name="delete_image" id="delete_image" value="0">
                    <input type="submit" name="update" id="update" value="Atualizar" class="btn">
                </form>
                <br>
                <p style="color: white; text-align: center;">Voltar para <a  style="color: white;" href="home.php">Tela Inicial</a></p>
                <br>
            </div>
        </div>
    </div>
    <script src="../js/cadastro.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>
