<?php
include_once('conexao.php');


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id']; 
    $sqlSelect = "SELECT * FROM usuarios WHERE id = $id";
    $result = $conexao->query($sqlSelect);

    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $nome = $user_data['nome'];
        $sobre = $user_data['sobre'];
        $email = $user_data['email'];
        $senha = $user_data['senha'];
        $data_nasc = $user_data['data_nasc'];
        $path = $user_data['path']; 
    } else {
        echo "Usuário não encontrado.";
        exit();
    }
} else {
    echo "ID não fornecido ou inválido.";
    exit();
}

?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/editSistema.css">
    <title>Editar Cadastro</title>
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
                <img src="img/logoFinal.png" alt="">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const data_nascimento = document.getElementById('data_nascimento');
            const hoje = new Date();
            const dia = String(hoje.getDate()).padStart(2, '0');
            const mes = String(hoje.getMonth() + 1).padStart(2, '0');
            const ano = hoje.getFullYear();
            const dataAtual = `${ano}-${mes}-${dia}`;
            data_nascimento.max = dataAtual;
        });
            calendarIcon.addEventListener('click', function() {
            dateInput.showPicker();
        });
    </script>
    <script src="js/editDadosClientes.js"></script>
    <script src="js/login.js"></script>
    <script src="https://kit.fontawesome.com/998c60ef77.js" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
