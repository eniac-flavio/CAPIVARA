<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include '../Php/conexao.php';

    $email_usuario = '';
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

        $email_usuario = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
        
        $headerIcon = '<a href="index.php?logout=true" tabindex="3" ><ion-icon name="log-out-outline" style="color: var(--c3);"></ion-icon></a>';

        $sqlFuncionario = "SELECT * FROM usuarios WHERE email = '$email_usuario' and acesso = 'funcionario'";
        $resultFuncionario = $conn->query($sqlFuncionario);

        if ($resultFuncionario->num_rows > 0) {
            $sql = "SELECT * FROM produtos";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }

            $nivel_acesso = 'funcionario';
            $editButtonFuncionario = '<div class="edit"><a class="flexcenter" href="../Php/edit.php?id=' . $row['id'] . '">Editar</a></div>';
            $addButtonFuncionario = '<a href="../Php/add.php">Adicionar Produto</a>';
        } else {
            $nivel_acesso = '';
            $editButtonFuncionario = '';
            $addButtonFuncionario = '';
        }
    } else {
        $headerIcon = '<a href="login.php" tabindex="3"><ion-icon name="log-in-outline"></ion-icon></a>';
        $nivel_acesso = '';
        $editButtonFuncionario = '';
        $addButtonFuncionario = '';
    }
    if (isset($_GET['logout']) && $_GET['logout'] == true) {
        session_unset();
        session_destroy();
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blusas</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/index.css">
    <link rel="shortcut icon" href="../Img/logo.svg" type="image/x-icon">
    <script src="../Js/app.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
<header class="flexbetween blur20 aligncenter">
        <div class="header flexcenter aligncenter">
            <div class="container flexcenter">
                <div class="content flexcenter aligncenter">
                    <ion-icon name="search-outline" id="searchIcon" tabindex="2"></ion-icon>
                    <div id="searchContainer" class="flexcenter hide aligncenter">
                        <form action="../Php/q.php" method="get" name="formsearch" class="flexbetween" id="formsearch">
                            <input type="text" name="q" id="searchInput" placeholder="Pesquisar"> <br>
                            <input type="number" id="minPrice" name="minPrice" placeholder="Preço mínimo" min="0" onkeydown="return event.key != '-'" tabindex="-1"> <br>
                            <input type="number" id="maxPrice" name="maxPrice" placeholder="Preço máximo" min="0" onkeydown="return event.key != '-'" tabindex="-1">
                            <input type="submit" id="submitInput" value="Pesquisar" class="hide">
                        </form>
                    </div>
                </div>
            </div>
            <div class="container header-logo">
                <a href="index.php" class="flexcenter aligncenter" tabindex="1">
                    <img src="../Img/logo.svg" alt="capivara" class="logo">
                    <h1>Capivara</h1>
                </a>
            </div>
            <div class="container header-icons">
                <a href="amei.php">
                    <ion-icon name="heart-outline"></ion-icon>
                </a>
                <ion-icon name="bag-outline" id="carrinho" onclick="openpopup()" style="cursor: pointer;"></ion-icon>
                <?php echo $headerIcon; ?>
            </div>
        </div>
    </header>

    <?php
        $email_usuario = '';
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

            $headerIcon = '<a href="index.php?logout=true" tabindex="3" ><ion-icon name="log-out-outline" style="color: var(--c3);"></ion-icon></a>';

            $email_usuario = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
            $sqlEmail = "SELECT * FROM carrinho WHERE email_usuarios = '$email_usuario'";
            $result = $conn->query($sqlEmail);

            $produtosNoCarrinho = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Array é uma variável grande
                    $produto = array(
                        'id' => $row['id_produtos'],
                        'quantidade_p' => $row['p_carrinho'],
                        'quantidade_m' => $row['m_carrinho'],
                        'quantidade_g' => $row['g_carrinho'],
                    );
                    $produtosNoCarrinho[] = $produto;
                }
            }

            $totalCarrinho = 0;

            foreach ($produtosNoCarrinho as $produto) {
                $sqlProduto = "SELECT * FROM produtos WHERE id = " . $produto['id'];
                $resultProduto = $conn->query($sqlProduto);

                if ($resultProduto->num_rows > 0) {
                    $rowProduto = $resultProduto->fetch_assoc();
                    $totalCarrinho += $rowProduto['preco'] * ($produto['quantidade_p'] + $produto['quantidade_m'] + $produto['quantidade_g']);
                }
            }
        }
    ?>

    <div class="popup hide" id="popup">
        <div class="container flexbetween aligncenter">
            <div class="content flexcenter aligncenter">
                <h2>Cesta</h2>
            <?php 
            $totalCarrinho = 0; 
            if (!empty($produtosNoCarrinho)): ?>
                <div class="itenscarrinho">
                        <?php
                            $quantidadeTotal = 0;

                            foreach ($produtosNoCarrinho as $produto) {
                                $sqlProduto = "SELECT * FROM produtos WHERE id = " . $produto['id'];
                                $resultProduto = $conn->query($sqlProduto);

                                if ($resultProduto->num_rows > 0) {
                                    $numProdutos = $resultProduto->num_rows;
                                    $rowProduto = $resultProduto->fetch_assoc();

                                    $totalProduto = ($produto['quantidade_p'] + $produto['quantidade_m'] + $produto['quantidade_g']) * $rowProduto['preco'];

                                    $quantidadeTotal += $produto['quantidade_p'] + $produto['quantidade_m'] + $produto['quantidade_g'];
                                    $totalItens = $produto['quantidade_p'] + $produto['quantidade_m'] + $produto['quantidade_g'];

                                    echo '<div class="produto flexbetween aligncenter" data-id="' . $rowProduto['id'] . '">';
                                    echo '<img src="' . $rowProduto['img'] . '" alt="' . $rowProduto['nome'] . '">';
                                    echo '<div>';
                                    echo '<p><strong>' . $rowProduto["nome"] . '</strong></p>';
                                    echo '<p>R$' . number_format($rowProduto["preco"], 2, ',', '.') . '</p>';
                                    echo '</div>';
                                    echo '</div>';

                                    $totalCarrinho += $totalProduto;
                                }
                            }
                        ?>
                    </div>
                    <p>Quantidade total de produtos: <?php echo $numProdutos; ?></p>
                <?php else: ?>
                    <p>Nenhum produto no carrinho.</p>
                <?php endif; ?>
            </div>

            <div class="content">
                <h3>Total R$<span id="totalpopup"><?php echo number_format($totalCarrinho, 2, ',', '.'); ?></span></h3>
                <button onclick="comprar()">Comprar</button>
            </div>
        </div>
    </div>

    <nav class="flexcenter">
        <a href="camisas.php" tabindex="4">Camisas</a>
        <a href="blusas.php" tabindex="5" style="color: var(--c3);">Blusas</a>
        <a href="calcas.php" tabindex="6">Calças</a>
    </nav>

    <main>
        <?php
            include '../Php/conexao.php';
            $tipoPagina = 'blusa';
            $sql = "SELECT * FROM produtos WHERE tipo = '$tipoPagina'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo '<div class="article flexcenter aligncenter"><div class="section flexaround aligncenter">';
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="produto">';
                    echo '<a href="roupa.php?id=' . $row['id'] . '">';
                    echo '<div class="img flexcenter aligncenter">';
                    echo '<img src="' . $row['img'] . '" alt="' . $row['nome'] . '">';
                    echo '</div>';
                    echo '<div class="desc aligncenter">';
                    echo '<h2>' . $row['nome'] . '</h2>';
                    echo '<p>R$ ' . $row['preco'] . '</p>';
                    echo $editButtonFuncionario;
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
                echo '</div></div>';
            } else {
                echo '<div class="flexcenter aligncenter">';
                echo 'Nenhuma<strong style="color: var(--c3);"> blusa </strong>encontrada';
                echo '</div>';
            }
            $conn->close();
        ?>
            <div class="configimg flexcenter aligncenter">
            <?php echo $addButtonFuncionario; ?>
        </div>
    </main>

    <footer>
        <p>Siga-nos no instagram <a href="https://www.instagram.com/srcapivarastore/" target="_blank"><ion-icon name="logo-instagram"></ion-icon>@srcapivarastore</a>.</p>
    </footer>

<script>
    var carrinho = document.getElementById('carrinho');
    var popup = document.getElementById('popup');

    function openpopup() {
        popup.classList.remove('hide');
        document.addEventListener('click', fecharAoClicarFora);
    }
    function closepopup() {
        popup.classList.add('hide');
        document.removeEventListener('click', fecharAoClicarFora);
    }
    function fecharAoClicarFora(event) {
        if (!popup.contains(event.target) && !carrinho.contains(event.target)) {
            closepopup();
        }
    }
    document.addEventListener('dblclick', function(event) {
        // Verifica se o clique foi em um produto
        if (event.target.classList.contains('produto')) {
            // Obtém o ID do produto a partir do atributo data-id
            var productId = event.target.getAttribute('data-id');

            // Chama a função para remover o produto do carrinho
            removerProdutoCarrinho(productId);
        }
    });

    function removerProdutoCarrinho(productId) {
        var formData = new FormData();
        formData.append('id_produto', productId);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../Php/tirarcarrinho.php', true);

        location.reload();
        
        xhr.send(formData);
    }
</script>
</body>
</html>