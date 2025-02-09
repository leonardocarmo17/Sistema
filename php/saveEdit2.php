<?php
if (isset($_POST['update'])) {
    include_once('conexao.php');

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $sobre = $_POST['sobre'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $data_nascimento = $_POST['data_nascimento'];
    $delete_image = $_POST['delete_image'];
    
    $error = ''; 
    $sql = ""; 
   
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        $arquivo = $_FILES['arquivo'];
        $nomeDoArquivo = $arquivo['name'];
        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
        $pasta = "../arquivos/";
        $novoNomeDoArquivo = uniqid() . '.' . $extensao;
        $path = $pasta . $novoNomeDoArquivo;

      
        if ($arquivo['size'] > 2000000) {
            $error = "Arquivo muito grande, máximo de 2MB. <br> <a href='home.php'>Voltar</a>";
     
        } elseif (!in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) {
            $error = "Tipo de arquivo não aceito. Permitido: jpg, jpeg, png, gif. <br> <a href='home.php'>Voltar</a>";
       
        } elseif (!move_uploaded_file($arquivo['tmp_name'], $path)) {
            $error = "Falha ao mover arquivo para a pasta de upload. <br> <a href='home.php'>Voltar</a>";
        } else {
           
            $sql = "UPDATE usuarios SET nome='$nome', sobre='$sobre', email='$email', senha='$senha', data_nasc='$data_nascimento', path='$path' WHERE id=$id";
        }
    } else {
       
        if ($delete_image == "1") {
          
            $sql = "SELECT path FROM usuarios WHERE id=$id";
            $result = $conexao->query($sql);
            if ($result->num_rows > 0) {
                $user_data = mysqli_fetch_assoc($result);
                $current_image_path = $user_data['path'];
                if (file_exists($current_image_path)) {
                    unlink($current_image_path); 
                }
            }
            $sql = "UPDATE usuarios SET nome='$nome', sobre='$sobre', email='$email', senha='$senha', data_nasc='$data_nascimento', path=NULL WHERE id=$id";
        } else {
            $sql = "UPDATE usuarios SET nome='$nome', sobre='$sobre', email='$email', senha='$senha', data_nasc='$data_nascimento' WHERE id=$id";
        }
    }


    if (!empty($sql)) {
        $result = $conexao->query($sql);

        if ($result) {
            header('Location: home.php');
        } else {
            echo "Erro ao atualizar o cadastro!";
        }
    } else {
        if (!empty($error)) {
            echo $error;
        } else {
            echo "Erro: A consulta SQL está vazia!";
        }
    }
}
?>
