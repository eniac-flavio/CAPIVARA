    <?php
    session_start();
    include '../Php/conexao.php';

    if (isset($_POST['id_produto'])) {
        $id_produto = $_POST['id_produto'];

        $email_usuario = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
        $sqlRemover = "DELETE FROM carrinho WHERE email_usuarios = '$email_usuario' AND id_produtos = '$id_produto'";
        $resultRemover = $conn->query($sqlRemover);

        if ($resultRemover) {
            echo 'Produto removido com sucesso!';
        } else {
            echo 'Erro ao remover o produto. ' . $conn->error;
        }
             

        if ($resultRemover) {
            echo 'Produto removido com sucesso!';
        } else {
            echo 'Erro ao remover o produto.';
        }
    } else {
        echo 'ID do produto não recebido.';
    }

    $conn->close();
?>