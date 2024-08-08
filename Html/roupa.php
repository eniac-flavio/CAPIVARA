<?php
    include '../Php/conexao.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

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
            
            // Verifica se o usuário está logado
            session_start();
            $email_usuario = '';
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                $email_usuario = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

                if ($email_usuario === '') {
                    echo "Erro: Usuário não logado.";
                    exit;
                }

                // Consulta se o usuário curtiu o produto
                $sqlCurtida = "SELECT * FROM amei WHERE id_produto = ? AND email_usuario = ?";
                $stmtCurtida = $conn->prepare($sqlCurtida);
                $stmtCurtida->bind_param("is", $id, $email_usuario);
                $stmtCurtida->execute();
                $resultCurtida = $stmtCurtida->get_result();
                $produto['curtido'] = $resultCurtida->num_rows > 0;
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

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produto['nome'];?></title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/roupas.css">
    <link rel="shortcut icon" href="../Img/logo.svg" type="image/x-icon">
    <script src="../Js/app.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        function incrementQuantity() {
            var quantInput = document.getElementById('quant');
            var currentQuant = parseInt(quantInput.value);
            var sizeSelect = document.getElementById('tamanho');
            var selectedSize = sizeSelect.value;
            var maxQuant;

            // Valor max dependendo do tamanho
            if (selectedSize === 'p') {
                maxQuant = <?php echo $quantidadeDisponivelP; ?>;
            } else if (selectedSize === 'm') {
                maxQuant = <?php echo $quantidadeDisponivelM; ?>;
            } else if (selectedSize === 'g') {
                maxQuant = <?php echo $quantidadeDisponivelG; ?>;
            }
            if (currentQuant < maxQuant) {
                quantInput.value = currentQuant + 1;
            }
            updateQuantityLimit();
        }

        function decrementQuantity() {
            var quantInput = document.getElementById('quant');
            var currentQuant = parseInt(quantInput.value);

            if (currentQuant > 1) {
                quantInput.value = currentQuant - 1;
            }
            updateQuantityLimit();
        }

        function updateQuantityLimit() {
            var sizeSelect = document.getElementById('tamanho');
            var quantityInput = document.getElementById('quant');
            var size = sizeSelect.value;
            var maxQuant;

            if (size === 'p') {
                maxQuant = <?php echo $produto['p']; ?>;
            } else if (size === 'm') {
                maxQuant = <?php echo $produto['m']; ?>;
            } else if (size === 'g') {
                maxQuant = <?php echo $produto['g']; ?>;
            }

            if (maxQuant > 10) {
                maxQuant = 10;
            }

            quantityInput.max = maxQuant;

            if (parseInt(quantityInput.value) > parseInt(quantityInput.max)) {
                quantityInput.value = quantityInput.max;
            }
        }
        function handleLike() {
            var productId = <?php echo $produto['id']; ?>;
            var isLiked = <?php echo $produto['curtido'] ? 'true' : 'false'; ?>;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var heartIcon = document.getElementById('heartIcon');
                    heartIcon.name = xhr.responseText === 'liked' ? 'heart' : 'heart-outline';
                }
            };

            var url = '../Php/handle_like.php?productId=' + productId + '&action=' + (isLiked ? 'unlike' : 'like');
            xhr.open('GET', url, true);
            xhr.send();
        }


        function adicionarAoCarrinho() {
            var productId = document.getElementById('produtoId').value;
            var selectedSize = document.getElementById('tamanho').value;
            var selectedQuantity = document.getElementById('quant').value;

            var formData = new FormData();
            formData.append('id_produto', productId);
            formData.append('tamanho', selectedSize);
            formData.append('quantidade', selectedQuantity);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../Php/addcarrinho.php', true);

            xhr.onload = function () {
                var mensagemDiv = document.getElementById('mensagem');

                if (xhr.status == 200) {
                    mensagemDiv.innerHTML = '<p style="color: green;">' + xhr.responseText + '</p>';
                } else {
                    mensagemDiv.innerHTML = '<p style="color: red;">Erro: ' + xhr.responseText + '</p>';
                }
            };

            xhr.send(formData);
        }
    </script>
</head>
<body>
    <input type="hidden" id="produtoId" value="<?php echo $produto['id']; ?>">
    <header class="flexstart">
        <a href="javascript:history.back()" class="flexcenter aligncenter">
            <ion-icon name="chevron-back-outline"></ion-icon> <h2>Voltar</h2>
        </a>
    </header>

    <main class="flex">
        <div class="container flexcenter" id="containerZoom">
            <div class="content flexcenter">
                <aside class="flexcenter aligncenter">
                    <img src="<?php echo $produto['img']; ?>" alt="Roupas">
                </aside>
                <aside class="flexcenter aligncenter">
                    <img src="<?php echo $produto['img']; ?>" alt="Roupas">
                </aside>
                <aside class="flexcenter aligncenter">
                    <img src="<?php echo $produto['img']; ?>" alt="Roupas">
                </aside>
                <aside class="flexcenter aligncenter">
                    <img src="<?php echo $produto['img']; ?>" alt="Roupas">
                </aside>
            </div>

            <div class="content flexcenter aligncenter" id="central">
                <img src="<?php echo $produto['img']; ?>" alt="Roupas">
            </div>  
        </div>


        <div class="container aligncenter">

            <section class="flexstart">
                <div class="container">
                    <h2><?php echo $produto['nome']; ?></h2>
                    <p>Ref.: <?php echo $produto['id']; ?></p>
                    <h3>R$ <?php echo $produto['preco']; ?></h3>
                    <p>ou 3x de R$ <?php echo number_format($produto['preco'] / 3, 2); ?></p>
                </div>
                <div class="container">
                    <ion-icon id="heartIcon" name="<?php echo $produto['curtido'] ? 'heart' : 'heart-outline'; ?>" onclick="handleLike()"></ion-icon>
                </div>
            </section>

            <section id="segcontent">
                <p><?php echo $produto['descricao']; ?></p>
            </section>

            <section class="aligncenter" id="tercontent">
                <div class="container flexstart aligncenter">
                    <div class="content">
                        <ion-icon name="sparkles-outline"></ion-icon>
                    </div>
                    <div class="content">
                        <h3>Tamanho</h3>
                    </div>
                </div>
                <div class="container flexstart aligncenter">
                   <select name="tamanho" id="tamanho" onchange="updateQuantityLimit()">
                        <option value="p">P</option>
                        <option value="m" selected>M</option>
                        <option value="g">G</option>
                    </select>
                </div>

                <div class="conteiner">
                    <div class="container flexstart aligncenter">
                        <div class="content">
                            <ion-icon name="copy-outline"></ion-icon>
                        </div>
                        <div class="content">
                            <h3>Quantidade</h3>
                        </div>
                    </div>
                    <div class="content quantcontent flexstart aligncenter" style="margin-bottom: 28.3%;">
                        <div class="container"><ion-icon name="remove-outline" onclick="decrementQuantity()"></ion-icon></div>
                        <div class="container"><input type="text" name="quant" id="quant" value="1" readonly></div>
                        <div class="container"><ion-icon name="add-outline" onclick="incrementQuantity()"></ion-icon></div>
                    </div>
                </div>
            </section>

            

            <section>
                <div class="container flexcenter aligncenter">
                    <div class="content flexcenter aligncenter">
                       <ion-icon name="bag-outline" onclick="adicionarAoCarrinho()"></ion-icon>
                    </div>
                    <div class="content flexcenter aligncenter">
                        <h2>Comprar</h2>
                    </div>
                </div>
                <div id="mensagem"></div>
            </section>

        </div>
    </main>
</body>
</html>