<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
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
                <h1>Login</h1>
                <form action="testeLogin.php" method="POST">
                    <div class="tbox">
                        <ion-icon name="person-sharp"></ion-icon><input type="text" name="email" id="email" placeholder="Email">
                    </div>
                    <div class="tbox">
                        <ion-icon name="lock-closed"></ion-icon><input type="password" name="senha" id="senha" placeholder="Senha">
                    </div>
                    <input type="submit" name="submit" id="submit" value="Enviar" class="btn">
                </form>
                <br>
                <div class="subTitulo">
                    <h2 style="text-align: center;"> Não possui conta? <a href="PHPMailer-master/cadastro.php">Registre-se</a></h2>
                    <br><br>
                    <p style="text-align: center;"> Voltar para <a href="home.php">Tela Inicial.</a><br><br> Recupere sua senha <a href="../PHPMailer-master/recuperar.php">Aqui.</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script src="js/login.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/998c60ef77.js" crossorigin="anonymous"></script>
</body>
</html>