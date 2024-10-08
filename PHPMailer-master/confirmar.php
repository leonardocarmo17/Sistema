<?php
    include_once('../conexao.php');
    $status = false;
    if(isset($_GET['token'])){
        $token = $_GET['token'];

        //ativa conta
        $sql = "UPDATE usuarios SET ativo = 1 WHERE token = '$token'";
        if(mysqli_query($conexao, $sql)){
            header('Location: ../login.php');
        }
        else{
            $mensagem = "Erro ao confirmar o E-mail";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>  
        function recarregarPagina() {
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }
        window.onload = function() {
            var status = "<?php echo $status; ?>";  
            if (status === "1" || status === "") {
                recarregarPagina();  
            }
        };
    </script>
</head>
<body>
    <?php if ($status === null): ?>
        <p>Verifique o seu e-mail para confirmar a sua conta.</p>
    <?php else: ?>
        <p><?php echo $mensagem; ?></p>
        <a href="cadastro.php">Voltar para o Cadastro</a>
    <?php endif; ?>

</body>
</html>