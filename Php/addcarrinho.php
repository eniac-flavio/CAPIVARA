<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }                
    include '../Php/conexao.php';

    if (isset($_POST['id_produto'])) {
        $id = $_POST['id_produto'];

        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $produto = $result->fetch_assoc();
            $quantidadeDisponivelP = $produto['p'];
            $quantidadeDisponivelM = $produto['m'];
            $quantidadeDisponivelG = $produto['g'];

            if (isset($_SESSION['user_email'])) {
                $email_usuario = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

                if ($email_usuario === '') {
                    echo "Erro: Usuário não logado.";
                    exit;
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id_produto = $id;
                    $tamanho = $_POST['tamanho'];
                    $quantidade = $_POST['quantidade'];

                    $sqlVerificaCarrinho = "SELECT * FROM carrinho WHERE email_usuarios = ? AND id_produtos = ? AND p_carrinho > 0 AND m_carrinho > 0 AND g_carrinho > 0";
                    $stmtVerificaCarrinho = $conn->prepare($sqlVerificaCarrinho);
                    $stmtVerificaCarrinho->bind_param("si", $email_usuario, $id_produto);
                    $stmtVerificaCarrinho->execute();
                    $resultVerificaCarrinho = $stmtVerificaCarrinho->get_result();

                    if ($resultVerificaCarrinho->num_rows > 0) {
                        echo "Este produto já está na lista!";
                    } else {
                        $sql = "INSERT INTO carrinho (email_usuarios, id_produtos, p_carrinho, m_carrinho, g_carrinho) VALUES (?, ?, ?, 0, 0)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sii", $email_usuario, $id_produto, $quantidade);
                        
                        if ($stmt->execute()) {
                            echo "Produto adicionado ao carrinho com sucesso!";
                        } else {
                            echo "Erro ao adicionar o produto ao carrinho.";
                        }
                    }
                }
            }
        } else {
            echo "Produto não encontrado.";
            exit;
        }
    } else {
        echo "ID do produto não fornecido.";
        exit;
    }
?>