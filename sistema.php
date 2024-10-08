<?php
    session_start();
    include_once('conexao.php');

    $nome = isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : '';
    $nivel = isset($_SESSION['niveldeacesso']) ? (int)$_SESSION['niveldeacesso'] : null;

    
    if (isset($_SESSION['nome']) && isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
    
        $sqlSelect = "SELECT id, nome, niveldeacesso FROM usuarios WHERE email = '$email'";
        $result2 = $conexao->query($sqlSelect);
    
        if ($result2 && $result2->num_rows > 0) {
            $user = $result2->fetch_assoc();
           
        } else {
            echo "Usuário não encontrado.";
        }
      
        $sql = "SELECT path FROM usuarios WHERE email = '$email'";
        $result = $conexao->query($sql);
    
        $user_data = mysqli_fetch_assoc($result);
    }


    if(!isset($_SESSION['redirecionar'])){
        if($user['niveldeacesso'] == 2 || $user['niveldeacesso'] == 3){
            $_SESSION['redirecionar'] = true;
            header('Location: sistema.php');
            exit;
        }
        else {
            $_SESSION['redirected'] = false;
            header('Location: home.php');
            exit;
        }
    }

    if(!empty($_GET['search'])){
        $data = $_GET['search'];
        $sql = "SELECT * FROM usuarios WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id DESC";
       
    }
    else{
        $sql = "SELECT * FROM usuarios WHERE ativo > 0 ORDER BY id DESC";
    }
    $result = $conexao->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/sistema.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
</head>
<style>
    
::-webkit-scrollbar {
    width: 0.8vw;
    background: #000000;
}
::-webkit-scrollbar-thumb {
    background: gray;
}
::-webkit-scrollbar:hover {
    width: 0.9vw;
}
::-webkit-scrollbar-thumb:hover {
    background: darkgray;
}
::-webkit-scrollbar-button {
    background: gray;
    height: 16px;
}
::-webkit-scrollbar-button:single-button:vertical:decrement {
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="gray" viewBox="0 0 24 24"><path d="M12 8l6 6H6z"/></svg>') center no-repeat;
    background-size: 50%;
}
::-webkit-scrollbar-button:single-button:vertical:increment {
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="gray" viewBox="0 0 24 24"><path d="M12 16l-6-6h12z"/></svg>') center no-repeat;
    background-size: 50%;
}
::-webkit-scrollbar-button:single-button:horizontal:decrement {
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="gray" viewBox="0 0 24 24"><path d="M8 12l6-6v12z"/></svg>') center no-repeat;
    background-size: 50%;
}
::-webkit-scrollbar-button:single-button:horizontal:increment {
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="gray" viewBox="0 0 24 24"><path d="M16 12l-6 6V6z"/></svg>') center no-repeat;
    background-size: 50%;
}
::-webkit-scrollbar-button:hover {
    background: darkgray;
}
</style>
<body>
<?php if (isset($user_data)): ?>
    <p>Nível de Acesso: <?php echo htmlspecialchars($user['niveldeacesso']); ?></p>
<?php endif; ?>
    <div class="modo">
                    <input type="checkbox" class="checkbox" id="chk">
                    <label class="label" for="chk">
                        <i class="fas fa-moon"></i>
                        <i class="fas fa-sun"></i>
                        <div class="bola"></div>
                    </label>
    </div>
    <div class="meioh1">
    <h1>Bem-vindo <u><?php echo $nome; ?></u></h1>
    <h1>Acessou o Sistema</h1>
    </div>
    <div class="d-flex">
        <a href="sair.php">Sair</a>
       
    </div>
    <div class="d-flex">
        <a href="home.php">Home</a>
    </div>
    <div class="box-search">
        <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()"class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
</svg></button>
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
            <?php if($user['niveldeacesso'] == 3): ?>
            <th scope="col">...</th>
            <?php endif; ?>
        </tr>
  </thead>
  <tbody>
    <?php 
    
        while($user_data = mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td>".$user_data['id']."</td>";
            echo "<td>".$user_data['nome']."</td>";
            echo "<td>".$user_data['sobre']."</td>";
            echo "<td>".$user_data['email']."</td>";
            
            if ($user['niveldeacesso'] == 3) {
                echo "<td>".$user_data['senha']."</td>";
            } else {
                echo "<td>".str_repeat('*', strlen($user_data['senha'])) ."</td>";
            }
            
            echo "<td>".$user_data['data_nasc']."</td>";
            
            if(!empty($user_data['path'])){
                echo "<td><a href='arquivos/".$user_data['path']."' target='_blank'><img src='arquivos/".$user_data['path']."' height='50'></a></td>";
            } else {
                echo "<td><i class='fas fa-image'></i></td>";
            }
            
            if(!empty($user_data['path'])){
                echo "<td>".$user_data['path']."</td>";
            } else {
                echo "<td> Nulo </td>";
            }
            
            if(isset($user_data['niveldeacesso']) && $user_data['niveldeacesso'] == 1){
                echo "<td> Usuário </td>";
            } elseif(isset($user_data['niveldeacesso']) && $user_data['niveldeacesso'] == 3){
                echo "<td> Super Admin </td>";
            } else {
                echo "<td> Admin </td>";
            }
            
            echo "<td>".$user_data['data_upload']."</td>";
            
            if ($user['niveldeacesso'] == 3) {
                if ($user_data['email'] === 'Admin@gmail.com') {
                    
                }
                else{
                    echo "<td> 
                        <a class='btn btn-sm btn-primary' href='editSistema.php?id=$user_data[id]'>
                            <svg xmlns= 'http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'> 
                                <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325'/>
                            </svg>
                        </a> 
                    </td>";
                    echo "<td>
                        <a class='btn btn-sm btn-danger' href='deleteCadastro.php?id={$user_data['id']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                                <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
                            </svg>
                        </a>
                    </td>";
                }
            }


            if ($_SESSION['niveldeacesso'] == 2) {
                if ($user_data['email'] === 'Admin@gmail.com' || $user_data['niveldeacesso'] == 2) {
                    
                }
                else{

                    echo "<td> 
                        <a class='btn btn-sm btn-primary' href='editSistema.php?id=$user_data[id]'>
                            <svg xmlns= 'http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'> 
                                <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325'/>
                            </svg>
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
    <script src="js/sistemas.js"></script>
    <script src="js/modo.js"></script>
    <script src="https://kit.fontawesome.com/998c60ef77.js" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>

