<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/add.css">
    <link rel="shortcut icon" href="../Img/logo.svg" type="image/x-icon">
    <script>
        function limitarQuantidadeMinima() {
            var quantidadeInput = document.getElementById('quantidade');

            quantidadeInput.addEventListener('input', function () {
                if (quantidadeInput.value < 1) {
                    quantidadeInput.value = 1;
                }
            });
        }
        function formatarPreco(input) {
            let valor = input.value.replace(/\D/g, '');
            valor = ("00000" + valor).slice(-5);
            let parteInteira = valor.slice(0, -2) || '0';
            let centavos = valor.slice(-2);
            input.value = 'R$ ' + parteInteira + '.' + centavos;
        }
    </script>
</head>
<body onload="limitarQuantidadeMinima();">
    <?php
        include 'conexao.php';

        $mensagem = ''; 

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $preco = str_replace('R$ ', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $imagem = $_POST['imagem'];
            $tipo = $_POST['tipo'];
            $quantP = $_POST['quantP'];
            $quantM = $_POST['quantM'];
            $quantG = $_POST['quantG'];

            $quantidadeTotal = $quantP + $quantM + $quantG;

            $sqlAdicionarProduto = "INSERT INTO produtos (nome, descricao, preco, img, quantidade, tipo, p, m, g) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtAdicionarProduto = $conn->prepare($sqlAdicionarProduto);
            $stmtAdicionarProduto->bind_param("ssssssiii", $nome, $descricao, $preco, $imagem, $quantidadeTotal, $tipo, $quantP, $quantM, $quantG);

            if ($stmtAdicionarProduto->execute()) {
                $mensagem = "Produto adicionado com sucesso!";
            } else {
                $mensagem = "Erro ao adicionar o produto: " . $stmtAdicionarProduto->error;
            }

            $stmtAdicionarProduto->close();
        }
        
        echo $mensagem;
    ?>
    <main class="flexcenter aligncenter">
        <div class="container"></div>
        <div class="container flexcenter aligncenter">
            <div class="content">
                <form action="add.php" method="post" enctype="multipart/form-data">
                    <h1>Adicionar Produto</h1>
                    <label for="nome">
                        <input type="text" name="nome" id="nome" required placeholder="Nome do produto*" tabindex="1"><br>
                    </label>
                    <label for="descricao">
                        <textarea name="descricao" id="descricao" required placeholder="Descrição do produto*" tabindex="2"></textarea><br>
                    </label>
                    <label for="preco">
                        <input type="text" name="preco" id="preco" oninput="formatarPreco(this)" required placeholder="Preço do produto*" tabindex="3"><br>
                    </label>
                    <label for="imagem">
                        <input type="url" name="imagem" id="imagem" required placeholder="URl da imagem do produto*" tabindex="4"><br>
                    </label>
                    <label for="tipo">
                        <select name="tipo" id="tipo" tabindex="5">
                            <option value="" selected disabled hidden>Tipo do produto</option>
                            <option value="camisa">Camisa</option>
                            <option value="blusa">Blusa</option>
                            <option value="calca">Calça</option>
                        </select>
                    </label><br>
                    <label for="quantidadeP">
                        <input type="number" name="quantP" id="quantP" required placeholder="Quantidade P*" tabindex="6"><br>
                    </label>
                    <label for="quantidadeM">
                        <input type="number" name="quantM" id="quantM" required placeholder="Quantidade M*" tabindex="7"><br>
                    </label>
                    <label for="quantidadeG">
                        <input type="number" name="quantG" id="quantG" required placeholder="Quantidade G*" tabindex="8"><br>
                    </label>
                    <input type="submit" value="Adicionar Produto" tabindex="9">
                </form>
                <div class="flexcenter aligncenter"><a href="../Html/index.php">Voltar</a></div>
            </div>
        </div>
    </main>
</body>
</html>