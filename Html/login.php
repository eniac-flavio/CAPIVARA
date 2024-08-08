<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/login.css">
    <link rel="shortcut icon" href="../Img/logo.svg" type="image/x-icon">
    <script src="../Js/app.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <?php
        session_start(); 
        
        include '../Php/conexao.php';

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        $loginError = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            
            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $senha_do_banco = $row['senha'];

                if ($senha === $senha_do_banco) {
                    echo "Login realizado com sucesso!";

                    // Criar um Cookie "Lembrar senha"
                    if (isset($_POST['lembrar']) && $_POST['lembrar'] == 'on') {
                        setcookie('lembrar_senha', $senha, time() + 3600 * 24 * 30); // 30 dias de validade
                    }
                    $_SESSION['logged_in'] = true;

                    $_SESSION['user_email'] = $email;
                    header("Location: index.php");
                    exit;
                } else {
                    $loginError = "Senha incorreta!";
                }
            } else {
                $loginError = "Usuário não encontrado! Crie uma conta se necessário.";
            }
        }

        // Se tiver uma senha lembrada, ela é guardada aqui
        $lembrar_senha = isset($_COOKIE['lembrar_senha']) ? $_COOKIE['lembrar_senha'] : "";
        $_SESSION['logged_in'] = true;
    ?>




    <main class="aligncenter flexcenter">
        <div class="container flexcenter aligncenter">
        </div>
        <div class="container">
            <div class="content flexcenter aligncenter">
                <a href="index.php?logout=true" class="flexcenter aligncenter" tabindex="-1">
                    <img src="../Img/logo.svg" alt="Capivara" class="logo">
                    <h1>Capivara</h1>
                </a>
            </div>
            <div class="content flexcenter aligncenter">
                <form action="login.php" method="post" name="formlogin">
                    <div class="searchContainer flex"><input type="email" name="email" id="email" placeholder="E-mail*" required tabindex="1"></div>
                    <div class="searchContainer flex"><input type="password" name="senha" id="senha" placeholder="Senha*" required tabindex="2" value="<?php echo $lembrar_senha; ?>"></div>
                    <aside class="flexbetween aligncenter">
                        <br class="br">
                        <label for="lembrar">
                            <input type="checkbox" name="lembrar" id="lembrar" tabindex="-1">
                            Lembrar minha senha
                        </label>
                        <br class="br"><br class="br"><br class="br">
                        <a href="#" tabindex="-1">Esqueceu a senha?</a>
                    </aside>
                    <input type="submit" name="enviar" id="enviar" value="Login" tabindex="3">
                    <p>Você não possui uma conta? <a href="sign.php" tabindex="4">Cadastre-se</a></p>
                </form>
            </div>
            <?php
                if (!empty($loginError)) {
                    echo '<div class="error-message flexcenter aligncenter" style="color: red;">' . $loginError . '</div>';
                }
            ?>
        </div>
    </main>
</body>
</html>