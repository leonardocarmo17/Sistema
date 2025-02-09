<?php
session_start();
include_once('conexao.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

// Usando prepared statement para maior segurança
$sqlSelect = "SELECT id, nome, niveldeacesso, path FROM usuarios WHERE email = ?";
$stmt = $conexao->prepare($sqlSelect);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $nome = htmlspecialchars($user['nome']);
    $nivel = (int) $user['niveldeacesso'];
    $path = $user['path'];
} else {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Bloqueia usuários com niveldeacesso <= 1 ou nulo
if ($nivel <= 1 || is_null($nivel)) {
    header('Location: home.php');
    exit;
}

// Verifica se há um termo de pesquisa
if (!empty($_GET['search'])) {
    $data = "%{$_GET['search']}%";
    $sql = "SELECT * FROM usuarios WHERE (id LIKE ? OR nome LIKE ? OR email LIKE ?) AND ativo > 0 ORDER BY id DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sss", $data, $data, $data);
} else {
    $sql = "SELECT * FROM usuarios WHERE ativo > 0 ORDER BY id DESC";
    $stmt = $conexao->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/sistema.css">
    <link rel="stylesheet" href="../css/modo.css">
    <script type="text/javascript" src="../js/modo.js" defer></script>
    <title> Sistema </title>
</head>
<body class="darkmode">
    <button id="theme-switch">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="green"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="green"><path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Zm326-268Z"/></svg>
    </button>
    <?php if (isset($user_data)): ?>
        <p>Nível de Acesso: <?php echo htmlspecialchars($user['niveldeacesso']); ?></p>
    <?php endif; ?>

    <div class="meioh1">
        <h1>Bem-vindo <u><?php echo $nome; ?></u></h1>
        <h1>Acessou o Sistema</h1>
    </div>
    <div class="menu-links">
    <a class="p-1" href="sair.php">Sair</a>
    <a class="p-2" href="home.php">Home</a>
</div>
    <div class="box-search">
        <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="btn btn-primary">
            <i class="fas fa-search icon-search"></i>
        </button>
    </div>
    <div>
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Sobrenome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Senha</th>
                    <th scope="col">Data de Nascimento</th>
                    <th scope="col">Preview</th>
                    <th scope="col">Arquivo</th>
                    <th scope="col">Modo</th>
                    <th scope="col">Data de Cadastro</th>
                    <th scope="col">...</th>
                    <?php if ($user['niveldeacesso'] == 3): ?>
                        <th scope="col">...</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($user_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $user_data['id'] . "</td>";
                    echo "<td>" . $user_data['nome'] . "</td>";
                    echo "<td>" . $user_data['sobre'] . "</td>";
                    echo "<td>" . $user_data['email'] . "</td>";

                    if ($user['niveldeacesso'] == 3) {
                        echo "<td>" . $user_data['senha'] . "</td>";
                    } else {
                        echo "<td>" . str_repeat('*', strlen($user_data['senha'])) . "</td>";
                    }

                    echo "<td>" . $user_data['data_nasc'] . "</td>";

                    if (!empty($user_data['path'])) {
                        echo "<td><a href='../arquivos/" . $user_data['path'] . "' target='_blank'><img src='../arquivos/" . $user_data['path'] . "' height='50'></a></td>";
                    } else {
                        echo "<td><i class='fas fa-image icon-image'></i></td>"; 
                    }

                    if (!empty($user_data['path'])) {
                        echo "<td>" . $user_data['path'] . "</td>";
                    } else {
                        echo "<td> Nulo </td>";
                    }

                    if (isset($user_data['niveldeacesso']) && $user_data['niveldeacesso'] == 1) {
                        echo "<td> Usuário </td>";
                    } elseif (isset($user_data['niveldeacesso']) && $user_data['niveldeacesso'] == 3) {
                        echo "<td> Super Admin </td>";
                    } else {
                        echo "<td> Admin </td>";
                    }

                    echo "<td>" . $user_data['data_upload'] . "</td>";

                    if (!empty($user) && isset($user['niveldeacesso']) && $user['niveldeacesso'] == 3) { 
                        if ($user_data['email'] !== 'Admin@gmail.com') {
                            echo "<td> 
                                <a class='btn btn-sm btn-primary' href='editSistema.php?id=$user_data[id]'>
                                    <i class='fas fa-edit icon-edit'></i>
                                </a> 
                            </td>";
                            echo "<td>
                                <a class='btn btn-sm btn-danger' href='deleteCadastro.php?id={$user_data['id']}'>
                                    <i class='fas fa-trash-alt icon-delete'></i>
                                </a>
                            </td>";
                        }
                    }

                    if ($_SESSION['niveldeacesso'] == 2) {
                        if ($user_data['email'] !== 'Admin@gmail.com' && $user_data['niveldeacesso'] != 2) {
                            echo "<td> 
                                <a class='btn btn-sm btn-primary' href='editSistema.php?id=$user_data[id]'>
                                    <i class='fas fa-edit icon-edit'></i>
                                </a> 
                            </td>";
                        }
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr>
</body>
</html>