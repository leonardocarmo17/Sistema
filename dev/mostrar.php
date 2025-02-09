<?php

include_once 'Usuario.php';

// Instancia a classe Usuario passando a conexão
$usuario = new Usuario($conexao);

// Obtém os usuários
$usuarios = $usuario->listarUsuarios();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e8f5e9;
            padding: 20px;
        }
        .table-container {
            max-width: 100%;
            margin: auto;
        }
        .table th {
            background-color: #2e7d32;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
        }
        .obs {
            position: fixed;
            bottom: 10px;
            left: 10px;
            color: #fff;
            background-color: #1b5e20;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container table-container">
        <h2 class="text-center text-success fw-bold mb-4">Lista de Usuários</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Senha</th>
                        <th>Data de Nascimento</th>
                        <th>Nível de Acesso</th>
                        <th>Conta Criada em</th>
                        <th>Token</th>
                        <th>Ativo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($usuarios->num_rows > 0): ?>
                        <?php while ($row = $usuarios->fetch_assoc()): ?>
                            <tr class="align-middle">
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nome']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo str_repeat('*', strlen($row['senha'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['data_nasc'])); ?></td>
                                <td>
                                    <?php echo ($row['niveldeacesso'] == 3) ? 'Dono' : (($row['niveldeacesso'] == 2) ? 'Admin' : 'Usuário'); ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($row['data_upload'])); ?></td>
                                <td><?php echo $row['token']; ?></td>
                                <td>
                                    <span class="badge <?php echo $row['ativo'] ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $row['ativo'] ? 'Ativo' : 'Não Ativo'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">Nenhum usuário encontrado</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="obs">
        <p>Atenção: Se a conta não estiver ativa, significa que não está apta para ser logada no sistema.</p>
        <p>Para ativação, cada conta deve possuir um token válido.</p>
    </div>

</body>
</html>