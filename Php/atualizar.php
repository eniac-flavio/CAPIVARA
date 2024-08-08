<?php
    include 'conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
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

        $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, img=?, quantidade=?, tipo=?, p=?, m=?, g=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssiiii", $nome, $descricao, $preco, $imagem, $quantidadeTotal, $tipo, $quantP, $quantM, $quantG, $id);

        if ($stmt->execute()) {
            header("Location: ../Html/index.php");
            exit();
        } else {
            echo "Erro ao atualizar o produto: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Método de requisição inválido.";
    }
?>