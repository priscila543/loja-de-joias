<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de compras</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="carrinho.css">
</head>
<body>
    <div class="container">
        <?php
            // obs: ele cria um id de sessao unico e armazena as informacoes no servidor
            session_start();//inicia ou recupera uma sessao 

            //verifica se a variavel de sessao itens ja existe, se nao existir, inicializa como um array vazio
            if(!isset($_SESSION['itens'])) {
                $_SESSION['itens'] = array();
            }
            
            if(isset($_GET['add']) && $_GET['add'] == "carrinho") {
                //obtem o id do produto enviado via URL (metodo GET)
                $idProduto = $_GET['id'];

                // se o produto ja estiver no carrinho, incementa a quantidade
                if(!isset($_SESSION['itens'] [$idProduto])) {
                    $_SESSION['itens'] [$idProduto] = 1;

                //se nao existir, adiciona com quantidade 1
                }else{
                    $_SESSION['itens'] [$idProduto] +=1;
                }
            }
            //se nao houver itens no carrinho, exibe a menssagem e um link para adicionar itens
            if(count($_SESSION['itens']) == 0) {
                echo 'Carrinho Vazio<br><a href="produto.php">Adicionar itens</a>';
            
            //inicializa como um array vazio para armazenar detalhes dos produtos
            }else{
                $_SESSION['dados'] = array();
                
                //percorre cada produto dentro de $_SESSION['itens']
                foreach($_SESSION['itens'] as $idProduto => $quantidade) {

                $host = '127.0.0.1';
                $dbname = '20221214010027';
                $user = 'postgres';
                $password = 'pabd';

                try{
                    $pdo = new PDO("pgsql:host=$host;port=5432;dbname=$dbname", $user, $password);
                    //setAttribute - configuta atributos da conexao com o banco de dados
                    //PDO::ATTR_ERRMODE é configurado para PDO::ERRMODE_EXCEPTION, erros na conexao gerarão excecoes
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                }catch(PDOException $e){
                    echo "ERRO: " . $e->getMessage();
                    exit;
                }

                //$pdo->prepare($sql)- prepara a consulta SQL
                $select = $pdo->prepare("SELECT * FROM produtos WHERE id=?");
                //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
                $select->bindParam(1, $idProduto);
                 //executa a consulta
                $select->execute();
                //fetchALL - retorna todos os resultados como um array associativo (chaves nomeadas em vez de indices numericos)
                $produtos = $select->fetchALL();
                $total = $quantidade * $produtos[0]["preco"];
                    echo
                        $produtos[0] ["nome"].'<br/>'.
                        'Preço:'.number_format($produtos[0] ["preco"],2,",",".").'<br/>'.
                        'Quantidade:'.$quantidade.'<br/>
                        Total: '.number_format($total,2,",",".").'<br/>
                        <a href="remover.php?remover=carrinho&id='.$idProduto.'">Remover</a>
                        <hr><br><br><br>';

                    //array_push - adiciona um novo iten ao final de um array
                    array_push($_SESSION['dados'],
                    $_SESSION['dados'],
                    array(
                    'id_produto' => $idProduto, 
                    'quantidade' => $produtos[0]['quantidade'],
                    'preco' => $produtos[0]['preco'],
                    'total' => $total
                    ));
                }
            }
        ?>
        <br><br>
        <button type="submit" onclick="telainicial()">Voltar para tela Inicial</button>
        <br><br>
        <button type="submit" onclick="produtos()">Produtos</button>

        <script>
            function telainicial() {
                window.location.href = "inicial.html"
            }
            function produtos() {
                window.location.href = "produto.php"
            }
        </script>
    </div>
</body>
</html>