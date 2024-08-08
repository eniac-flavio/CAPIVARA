<?php
include 'conexao.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        // Este bloco será executado se o ID estiver presente, o que significa que o formulário é para edição.
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $link_img = $_POST['link_img'];

        $sqlEditarProduto = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, link_img = ? WHERE id = ?";
        $stmtEditarProduto = $conn->prepare($sqlEditarProduto);
        $stmtEditarProduto->bind_param("ssssi", $nome, $descricao, $preco, $link_img, $id);

        if ($stmtEditarProduto->execute()) {
            echo "Produto editado com sucesso!";
        } else {
            echo "Erro ao editar o produto: " . $stmtEditarProduto->error;
        }

        $stmtEditarProduto->close();
    } else {
        // Este bloco será executado se o ID não estiver presente, o que significa que o formulário é para adicionar.
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $link_img = $_POST['link_img'];

        // Serificar se o nome do produto já existe
        $sqlVerificarNome = "SELECT id FROM produtos WHERE nome = ?";
    
        $stmtVerificarNome = $conn->prepare($sqlVerificarNome);
        $stmtVerificarNome->bind_param("s", $nome);
        $stmtVerificarNome->execute();

        $result = $stmtVerificarNome->get_result();

        if ($result->num_rows > 0) {
            echo "Já existe um produto com esse nome.";
        } else {
            $sqlInserirProduto = "INSERT INTO produtos (nome, descricao, preco, link_img) VALUES (?, ?, ?, ?)";
            $stmtInserirProduto = $conn->prepare($sqlInserirProduto);
            $stmtInserirProduto->bind_param("ssss", $nome, $descricao, $preco, $link_img);

            if ($stmtInserirProduto->execute()) {
                echo "Produto adicionado com sucesso!";
            } else {
                echo "Erro ao adicionar o produto: " . $stmtInserirProduto->error;
            }

            $stmtInserirProduto->close();
        }

        $stmtVerificarNome->close();
    }
}

$conn->close();
?>
<a href="../Html/index.php">Voltar</a>
