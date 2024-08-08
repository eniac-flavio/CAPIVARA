<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['q'])) {
            $q = $_GET['q'];
        } else {
            $q = "";
        }
    }

    if (isset($_GET['minPrice'])) {
        $minPrice = $_GET['minPrice'];
    } else {
        $minPrice = ""; 
    }

    if (isset($_GET['maxPrice'])) {
        $maxPrice = $_GET['maxPrice'];
    } else {
        $maxPrice = ""; 
    }

    include 'conexao.php';
    $sql = "SELECT * FROM produtos WHERE nome LIKE '%$q%'";

    if (!empty($minPrice) && !empty($maxPrice)) {
        $sql .= " AND preco >= '$minPrice' AND preco <= '$maxPrice'";
    } elseif (!empty($minPrice)) {
        $sql .= " AND preco >= '$minPrice'";
    } elseif (!empty($maxPrice)) {
        $sql .= " AND preco <= '$maxPrice'";
    }

    $result = $conn->query($sql);

    if (!$result) {
        echo "Erro na consulta: " . $conn->error;
    }

    
    if ($result->num_rows > 0) {
        echo '<main flexcenter aligncenter>';
        echo '<a class="flex aligncenter" href="../Html/index.php"><ion-icon name="chevron-back-outline"></ion-icon><h1>Voltar</h1></a>';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="produto flex alingcenter"><a class="flex" href="../Html/roupa.php?id=' . $row["id"] . '">';

            echo '<div class="container flexcenter aligncenter">';
            echo '<img src="' . $row["img"] . '" alt="' . $row["nome"] . '">';
            echo '</div>';

            echo '<div class="container flex aligncenter">';

            echo '<div class="content flexbetween aligncenter">';
            echo '<div class="esq flex aligncenter"><p>Id ' . $row["id"] . '</p><h2>' . $row["nome"] . '</h2></div>';
            echo '<div class="dir"><p>R$ ' . $row["preco"] . '</p></div>';
            echo '</div>';

            echo '<div class="content aligncenter">';
            echo '<div class="desc"><p>' . $row["descricao"] . '</p></div>';
            echo '</div>';

            echo '</div>';

            echo '</a></div>';
        }
        echo '</main>';
    } else {
        echo '<div class="notfound flexcenter aligncenter">';
        echo '<h4>404</h4>';
        echo '<h5>NADA ENCONTRADO</h5>';
        echo '<a href="../Html/index.php">Voltar</a>';
        echo '</div>';
    }
    $conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $q;?></title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/q.css">
    <link rel="shortcut icon" href="../Img/logo.svg" type="image/x-icon">
    <script src="../Js/app.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https:// unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
</body>
</html>
