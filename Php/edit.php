<?php
    include 'conexao.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $produto = $result->fetch_assoc();
        } else {
            echo "Produto não encontrado.";
            exit;
        }
    } else {
        echo "ID do produto não fornecido.";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
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
    <main class="flexcenter aligncenter">
        <div class="container"></div>
        <div class="container flexcenter aligncenter">
            <div class="content">
                <form action="atualizar.php" method="post">
                    <h1>Editar Produto</h1>
                    <label for="nome">
                        <input type="text" name="nome" id="nome" value="<?php echo $produto['nome']; ?>" required placeholder="Nome do produto*" tabindex="1"><br>
                    </label>
                    <label for="descricao">
                        <textarea name="descricao" id="descricao" required placeholder="Descrição do produto*" tabindex="2"><?php echo $produto['descricao']; ?></textarea><br>
                    </label>
                    <label for="preco">
                        <input type="text" name="preco" id="preco" value="<?php echo $produto['preco']; ?>" oninput="formatarPreco(this)" required placeholder="Preço do produto*" tabindex="3"><br>
                    </label>
                    <label for="imagem">
                        <input type="url" name="imagem" id="imagem" value="<?php echo $produto['img']; ?>" required placeholder="URl da imagem do produto*" tabindex="5"><br>
                    </label>
                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                    <label for="tipo">
                        <select name="tipo" id="tipo" tabindex="6">
                            <option value="" selected disabled hidden>Tipo do produto</option>
                            <option value="camisa" <?php if ($produto['tipo'] === 'camisa') echo 'selected'; ?>>Camisa</option>
                            <option value="blusa" <?php if ($produto['tipo'] === 'blusa') echo 'selected'; ?>>Blusa</option>
                            <option value="calca" <?php if ($produto['tipo'] === 'calca') echo 'selected'; ?>>Calça</option>
                        </select>
                    </label><br>
                    <label for="quantidadeP">
                        <input type="number" name="quantP" id="quantP" value="<?php echo $produto['p']; ?>" required placeholder="Quantidade P*" tabindex="7"><br>
                    </label>
                    <label for="quantidadeM">
                        <input type="number" name="quantM" id="quantM" value="<?php echo $produto['m']; ?>" required placeholder="Quantidade M*" tabindex="8"><br>
                    </label>
                    <label for="quantidadeG">
                        <input type="number" name="quantG" id="quantG" value="<?php echo $produto['g']; ?>" required placeholder="Quantidade G*" tabindex="9"><br>
                    </label>
                    <input type="submit" value="Atualizar Produto" tabindex="10">
                </form>
            </div>
        </div>
    </main>
</body>
</html>