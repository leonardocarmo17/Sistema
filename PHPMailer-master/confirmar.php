<?php
include_once('../php/conexao.php');

$mensagem = "";
$sucesso = false;

if (isset($_GET['token'])) {
    $token = trim($_GET['token']);

    // Usa prepared statement para evitar SQL Injection
    $sql = "UPDATE usuarios SET ativo = 1 WHERE token = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    
    if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
        $sucesso = true;
        header("Refresh: 3; url=../php/login.php"); // Redireciona após 3 segundos
        $mensagem = "Conta ativada com sucesso! Redirecionando para o login...";
    } else {
        $mensagem = "Erro ao confirmar o e-mail. O token pode ser inválido ou a conta já foi ativada.";
    }
    mysqli_stmt_close($stmt);
} else {
    $mensagem = "Token inválido.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/confirmar.css">
    <title>Confirmação de E-mail</title>
</head>
<body>
    <div class="container">
        <p class="<?php echo $sucesso ? 'sucesso' : 'erro'; ?>"><?php echo $mensagem; ?></p>
        <?php if (!$sucesso): ?>
            <a class="link-voltar" href="cadastro.php">Voltar para o Cadastro</a>
        <?php endif; ?>
    </div>
</body>
</html>
