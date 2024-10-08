<?php
session_start();
include_once('conexao.php');

// Verifica se o usuário está logado
if (isset($_SESSION['nome']) && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Consulta para obter apenas o usuário logado
    $sqlSelect = "SELECT id, nome, niveldeacesso FROM usuarios WHERE email = '$email'";
    $result2 = $conexao->query($sqlSelect);

    if ($result2 && $result2->num_rows > 0) {
        $user = $result2->fetch_assoc();
        // Gerar link para o próprio usuário
    } else {
        echo "Usuário não encontrado.";
    }
    
    // Seleciona a coluna 'path' apenas para o usuário logado
    $sql = "SELECT path FROM usuarios WHERE email = '$email'";
    $result = $conexao->query($sql);

    // Obtém o caminho da imagem do usuário logado
    $user_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/nav.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Página Inicial</title>
</head>
<body>
<?php if (isset($user_data)): ?>
    <p>Nível de Acesso: <?php echo htmlspecialchars($user['niveldeacesso']); ?></p>
<?php endif; ?>

<?php if (isset($_SESSION['nome'])): ?>
    <header class="hidao">
        <nav class="flex justify-between items-center w-[92%]">
            <div>
                <img class="w-16 cursor-pointer" src="img/logoFinal.png" alt="...">
            </div>
            <div class="cor-links nav-links duration-500 md:static absolute md:min-h-fit min-h-[30vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5">
                <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                    <li class="titulo">
                        <a class="produto" href="#">Products</a>
                    </li>
                    <li class="titulo">
                        <a class="produto" href="#">Products</a>
                    </li>
                    <li class="titulo">
                        <a class="produto" href="#">Products</a>
                    </li>
                    <li class="titulo">
                        <a class="produto" href="#">Products</a>
                    </li>
                </ul>
            </div>
            <div class="modo">
                <input type="checkbox" class="checkbox" id="chk">
                <label class="label" for="chk">
                <i class="fas fa-moon"></i>
                <i class="fas fa-sun"></i>
                <div class="bola"></div>
                </label>
            </div>
            <div class="relative flex items-center gap-4"> 
                <div class="relative flex items-center">
                    <div class="flex justify-center items-center">
                        <div onclick="toggleMenu()" >
                            <?php if (!empty($user_data['path'])): ?>
                                <img class="imagem" src="<?php echo htmlspecialchars($user_data['path']); ?>" height="150" width="150" alt="Foto do usuário">
                            <?php else: ?>
                                <img class="imagem" src="img/user.png" height="150" width="150"> 
                            <?php endif; ?>
                        </div>
                    </div>
                <div class="sub-menu-wrap" id="subMenu">
                    <div class="sub-menu">
                        <div class="user-info">
                            <h2>Bem Vindo(a), <strong><?php echo htmlspecialchars($_SESSION['nome']); ?>!</strong></h2>
                        </div>
                        <hr>
                        <?php if ($user['niveldeacesso'] == 2 || $user['niveldeacesso'] == 3): ?>
                            <a href="sistema.php" class="sub-menu-link">
                                <ion-icon name="pencil"></ion-icon>
                                <p>Sistema</p>
                                <span>></span>
                            </a>
                        <?php endif; ?>
                        <a href="DadosCliente.php?id=<?php echo $user['id']; ?>" class="sub-menu-link">
                            
                            <ion-icon name="pencil"></ion-icon>
                            <p>Editar Perfil</p>
                            <span>></span>
                        </a>
                        <a href="sair.php" class="sub-menu-link">
                            <ion-icon name="log-out-outline"></ion-icon>
                            <p>Sair</p>
                            <span>></span>
                        </a>
                    </div>
                </div>
                </div>
                <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-3xl cursor-pointer md:hidden" id="menu-icon"></ion-icon>
            </div>
        </nav>
    </header>     
<?php else: ?>
    <header class="hidao">
        <nav class="flex justify-between items-center w-[92%]">
            <div>
                <img class="w-16 cursor-pointer" src="img/logoFinal.png" alt="...">
            </div>
            <div class="cor-links nav-links duration-500 md:static absolute md:min-h-fit min-h-[30vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5">
                <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                    <li class="titulo">
                        <a class="produto" href="#">Products</a>
                    </li>
                    <li class="titulo">
                        <a class="produto" href="#">Products</a>
                    </li>
                    <li class="titulo">
                        <a class="produto" href="#">Products</a>
                    </li>
                    <li class="titulo">
                        <a class="produto" href="#">Products</a>
                    </li>
                </ul>
            </div>
            <div class="modo">
                <input type="checkbox" class="checkbox" id="chk">
                <label class="label" for="chk">
                <i class="fas fa-moon"></i>
                <i class="fas fa-sun"></i>
                <div class="bola"></div>
                </label>
            </div>
            <div class="flex items-center gap-8">
                <a href="login.php" class="meio3">
                    <ion-icon name="person-circle-outline"></ion-icon>
                    <p class="meio4">Entrar</p>
                </a>
                <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-3xl cursor-pointer md:hidden" id="menu-icon"></ion-icon>
            </div>
        </nav>
    </header>
<?php endif; ?>

<script>
    let subMenu = document.getElementById("subMenu");

    function toggleMenu(){
        subMenu.classList.toggle("open-menu");
    }
</script>


<script src="js/login.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://kit.fontawesome.com/998c60ef77.js" crossorigin="anonymous"></script>
</body>
</html>
