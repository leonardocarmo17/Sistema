<?php
if (isset($_POST['update'])) {
    include_once('conexao.php');
    session_start();

    // Verificando se o usuário está autenticado e se tem permissão para editar
    if ($_SESSION['niveldeacesso'] < 2) {
        // Se não for administrador (nível 2 ou 3), redireciona ou exibe erro
        header('Location: sistema.php');
        exit;
    }

    $id = $_POST['id'];
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $sobre = mysqli_real_escape_string($conexao, $_POST['sobre']);
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
    $data_nascimento = mysqli_real_escape_string($conexao, $_POST['data_nascimento']);
    $delete_image = $_POST['delete_image'];
    $nivel = (int) $_POST['niveldeacesso']; // O nível de acesso do usuário

    $error = ''; // Variável para mensagens de erro

    // Verificando se o administrador tem permissão para alterar o nível de acesso de outros usuários
    if ($_SESSION['niveldeacesso'] < 3) {
        $nivel = 2; // Impede administradores de nível 2 de alterar o nível de acesso
    }

    // Lógica de upload de imagem
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        $arquivo = $_FILES['arquivo'];
        $nomeDoArquivo = $arquivo['name'];
        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
        $pasta = "arquivos/";
        $novoNomeDoArquivo = uniqid() . '.' . $extensao;
        $path = $pasta . $novoNomeDoArquivo;

        // Validações do arquivo
        if ($arquivo['size'] > 2000000) { 
            $error = "Arquivo muito grande, máximo de 2MB. <br> <a href='sistema.php'>Voltar</a>";
        } elseif (!in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) {
            $error = "Tipo de arquivo não aceito. Permitido: jpg, jpeg, png, gif. <br> <a href='sistema.php'>Voltar</a>";
        } elseif (!move_uploaded_file($arquivo['tmp_name'], $path)) {
            $error = "Falha ao mover arquivo para a pasta de upload. <br> <a href='sistema.php'>Voltar</a>";
        } else {
            // Atualiza o banco com a nova imagem
            $sql = "UPDATE usuarios SET nome=?, sobre=?, email=?, senha=?, data_nasc=?, path=?, niveldeacesso=? WHERE id=?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssssssii", $nome, $sobre, $email, $senha, $data_nascimento, $path, $nivel, $id);
        }
    } else {
        // Se o usuário não enviar uma imagem, trata a exclusão ou não da imagem existente
        if ($delete_image == "1") {
            // Exclui a imagem antiga se for indicado para deletar
            $sql = "SELECT path FROM usuarios WHERE id=$id";
            $result = $conexao->query($sql);
            if ($result->num_rows > 0) {
                $user_data = mysqli_fetch_assoc($result);
                $current_image_path = $user_data['path'];
                if (file_exists($current_image_path)) {
                    unlink($current_image_path); // Deleta a imagem
                }
            }
            // Atualiza os dados sem imagem
            $sql = "UPDATE usuarios SET nome=?, sobre=?, email=?, senha=?, data_nasc=?, path=NULL, niveldeacesso=? WHERE id=?";
        } else {
            // Atualiza os dados sem alteração na imagem
            $sql = "UPDATE usuarios SET nome=?, sobre=?, email=?, senha=?, data_nasc=?, niveldeacesso=? WHERE id=?";
        }
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssii", $nome, $sobre, $email, $senha, $data_nascimento, $nivel, $id);
    }

    // Executa a consulta SQL
    if (!empty($sql)) {
        $result = $stmt->execute();
        if ($result) {
            header('Location: sistema.php');
        } else {
            echo "Erro ao atualizar o cadastro. Tente novamente.";
        }
    } else {
        if (!empty($error)) {
            echo $error;
        } else {
            echo "Erro: A consulta SQL está vazia.";
        }
    }
}
?>
