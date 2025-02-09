<?php
    session_start();

    if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
        include_once('conexao.php');
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM usuarios WHERE email = '$email' and ativo = 1";
        $result = $conexao->query($sql);

       

        if(mysqli_num_rows($result) < 1){
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: login.php');
        }
        else{
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['niveldeacesso'] = $row['niveldeacesso'];
            header('Location: home.php');
        }
    }
    else{
        header('Location: login.php');
    }
?>