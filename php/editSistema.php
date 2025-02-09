<?php
if (!empty($_GET['id'])) {
    include_once('conexao.php');
    session_start();
    
    
    
    $id = $_GET['id'];
    $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";
    $result = $conexao->query($sqlSelect);
    
    if ($result->num_rows > 0) {
        while ($user_data = mysqli_fetch_assoc($result)) {
            $nome = $user_data['nome'];
            $sobre = $user_data['sobre'];
            $email = $user_data['email'];
            $senha = $user_data['senha'];
            $data_nasc = $user_data['data_nasc'];
            $path = $user_data['path'];
            $nivel = isset($user_data['niveldeacesso']) ? (int)$user_data['niveldeacesso'] : '1';
        
        }
    } else {
        header('Location: sistema.php');
    }
    if (!isset($_SESSION['niveldeacesso'])) {
    // Se não estiver definida, redireciona para a página de login ou outra página
    header('Location: sistema.php');
    exit;
}
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
    <?php if($_SESSION['niveldeacesso'] == 2): ?>
  
    <div class="container">
    
        <div class="login">
            <div class="content">
                <img src="../img/logoFinal.png" alt="">
            </div>
            <div class="loginform">
                <h1>Editar Cadastro do Cliente</h1>
                <form method="POST" action="saveEdit.php" enctype="multipart/form-data">
                    <label for="nome">Nome:</label>
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon>
                        <input type="text" name="nome" id="nome" placeholder="Nome" value="<?php echo $nome ?>" required>
                    </div>

                    <label for="sobre">Sobrenome:</label>
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon>
                        <input type="text" name="sobre" id="sobre" placeholder="Sobrenome" value="<?php echo $sobre ?>" required>
                    </div>

                    <label for="email">Email:</label>
                    <div class="tbox">
                        <ion-icon name="at-outline"></ion-icon>
                        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $email ?>" required>
                    </div>
                    <label for="data_nascimento">Data de Nascimento:</label>
                    <div class="tbox">
                        <ion-icon name="calendar-outline" onclick="document.getElementById('data_nascimento').focus();"></ion-icon>
                        <input type="date" name="data_nascimento" id="data_nascimento" value="<?php echo $data_nasc ?>" required>
                    </div>
                    <br>
                    <label for="niveldeacesso">Nivel de Acesso:</label>
                        <select name="niveldeacesso" id="niveldeacesso" disabled>
                            <option value="<?php echo $nivel; ?>"><?php echo $nivel == 1 ? 'Usuario' : 'Admin'; ?></option>
                        </select>
                    <br><br><br>
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
                            <img id="preview-existente" src="<?php echo $path; ?>" style="margin-bottom: 25px; margin-top: -20px;">
                        </div>
                    <?php endif; ?>
                    
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="delete_image" id="delete_image" value="0">
                    <input type="submit" name="update" id="update" value="Atualizar" class="btn">
                </form>
                <br>
                <p class="voltar">Voltar para o <a href="sistema.php"> Sistema</a></p>
                <br>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($_SESSION['niveldeacesso'] == 3): ?>
    <div class="container">
        <div class="login">
            <div class="content">
                <img src="../img/logoFinal.png" alt="">
            </div>
            <div class="loginform">
                <h1>Editar Cadastro do Cliente</h1>
                <form method="POST" action="saveEdit.php" enctype="multipart/form-data">
                    <label for="nome">Nome:</label>
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon>
                        <input type="text" name="nome" id="nome" placeholder="Nome" value="<?php echo $nome ?>" required>
                    </div>

                    <label for="sobre">Sobrenome:</label>
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon>
                        <input type="text" name="sobre" id="sobre" placeholder="Sobrenome" value="<?php echo $sobre ?>" required>
                    </div>

                    <label for="email">Email:</label>
                    <div class="tbox">
                        <ion-icon name="at-outline"></ion-icon>
                        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $email ?>" required>
                    </div>
                 
                        <label for="senha">Senha:</label>
                        <div class="tbox">
                            <ion-icon name="lock-closed"></ion-icon>
                            <input type="text" name="senha" id="senha" placeholder="Senha" value="<?php echo $senha ?>" required>
                        </div>
                    <label for="data_nascimento">Data de Nascimento:</label>
                    <div class="tbox">
                        <ion-icon name="calendar-outline" onclick="document.getElementById('data_nascimento').focus();"></ion-icon>
                        <input type="date" name="data_nascimento" id="data_nascimento" value="<?php echo $data_nasc ?>" required>
                    </div>
                    <br>
                   <label for="niveldeacesso">Nivel de Acesso:</label>
                    <select name="niveldeacesso" id="niveldeacesso" required>
                        <option value="1" <?php if ($nivel == 1) echo 'selected'; ?>>Usuário</option>
                        <option value="2" <?php if ($nivel == 2) echo 'selected'; ?>>Admin</option>
                    </select>                  
                    <br><br><br>
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
                            <img id="preview-existente" src="<?php echo $path; ?>" style="margin-bottom: 25px; margin-top: -20px;">
                        </div>
                    <?php endif; ?>
                    
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="delete_image" id="delete_image" value="0">
                    <input type="submit" name="update" id="update" value="Atualizar" class="btn">
                </form>
                <br>
                <p class="voltar">Voltar para o <a href="sistema.php"> Sistema</a></p>
                <br>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <script src="../js/editSistema.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>
