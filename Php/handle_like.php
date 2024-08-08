<?php
include '../Php/conexao.php';

if (isset($_GET['productId']) && isset($_GET['action'])) {
    $productId = $_GET['productId'];
    $action = $_GET['action'];
    session_start();
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $email_usuario = $_SESSION['user_email'];

        $sqlCurtida = "SELECT * FROM amei WHERE id_produto = ? AND email_usuario = ?";
        $stmtCurtida = $conn->prepare($sqlCurtida);
        $stmtCurtida->bind_param("is", $productId, $email_usuario);
        $stmtCurtida->execute();
        $resultCurtida = $stmtCurtida->get_result();
        $produto['curtido'] = $resultCurtida->num_rows > 0;

        if ($action === 'like') {
            // Adiciona a curtida se ainda não existir
            if ($produto['curtido'] === false) {
                $stmt = $conn->prepare("INSERT INTO amei (id_produto, email_usuario) VALUES (?, ?)");
                $stmt->bind_param("is", $productId, $email_usuario);
                $stmt->execute();
                echo 'liked';
            }
        } elseif ($action === 'unlike') {
            // Remove a curtida se existir
            if ($produto['curtido'] === true) {
                $stmt = $conn->prepare("DELETE FROM amei WHERE id_produto = ? AND email_usuario = ?");
                $stmt->bind_param("is", $productId, $email_usuario);
                $stmt->execute();
                echo 'unliked';
            }
        }

        $stmtCurtida->close();
    }
}

$conn->close();
?>