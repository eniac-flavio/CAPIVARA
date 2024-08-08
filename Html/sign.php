<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include '../Php/conexao.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $cpf = $_POST['cpf'];
        $endereco = $_POST['endereco'];

        $sql = "INSERT INTO usuarios (email, senha, cpf, endereco) VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssss", $email, $senha, $cpf, $endereco);

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
            $_SESSION['logged_in'] = true;
            $_SESSION['user_email'] = $email;
            header("Location: index.php");
            exit;
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/login.css">
    <link rel="shortcut icon" href="../Img/logo.svg" type="image/x-icon">
    <script src="../Js/app.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        function formatCPF(cpf) {
          cpf = cpf.replace(/\D/g, ''); // Apenas números
          cpf = cpf.slice(0, 11); // Apenas 11 números
          // Formatação adicional
          cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2'); 
          cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
          cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
          return cpf;
        }
      
        function updateCPFField(input) {
          input.value = formatCPF(input.value);
        }
      
        const cpfInput = document.getElementById('cpf');
      
        cpfInput.addEventListener('input', function () {
          updateCPFField(this);
        });
      </script>
</head>
<body>



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
                <form action="sign.php" method="post" name="formlogin">
                    <div class="searchContainer flex"><input type="email" name="email" id="email" placeholder="Informe seu e-mail*" required tabindex="1"></div>
                    <div class="searchContainer flex"><input type="password" name="senha" id="senha" placeholder="Informe sua senha*" required tabindex="2"></div>
                    <div class="searchContainer flex"><input type="text" name="cpf" id="cpf" placeholder="Informe seu CPF*" required onkeyup="updateCPFField(this)" tabindex="3"></div>
                    <div class="searchContainer flex"><input type="text" name="endereco" id="endereco" placeholder="Informe seu endereço*" required tabindex="4"></div>
                    <input type="submit" name="enviar" id="enviar" value="Cadastre-se" tabindex="7">
                    <p>Você já possui uma conta? <a href="login.php" tabindex="8">Entre</a></p>
                </form>
            </div>
        </div>
    </main>
</body>
</html>