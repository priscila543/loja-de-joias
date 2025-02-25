<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="carrinho.css">
</head>
<body>
    <div class="container">
        <h2>Produtos</h2>
        <?php
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

            //$pdo->prepare()- prepara a consulta SQL
            $select = $pdo->prepare("SELECT * FROM produtos");
            //executa a consulta
            $select->execute();
            //fetchALL - retorna todos os resultados como um array associativo (chaves nomeadas em vez de indices numericos)
            $fetch = $select->fetchALL();

            //cada item de $fetch é atribuído à variável $produto em cada iteração
            foreach($fetch as $produto) {
                echo 'Nome do produto: '.$produto['nome'].'&nbsp; 
                    Quantidade: '.$produto['quantidade'].'&nbsp; 
                    Preço: R$ '.number_format($produto['preco'],2,",",".").'<br><br>
                    <a href="carrinho.php?add=carrinho&id='.$produto['id'].'">Adicionar ao carrinho</a>
                    <br><br>';
            }
        ?>
        <br>
        <button type="submit" onclick="telainicial()">Voltar para Tela Inicial</button>

        <script>
            function telainicial() {
                window.location.href = "inicial.html"
            }
        </script>
    </div>
</body>
</html>