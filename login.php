<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST">
            <div>
                <input type="email" name="email" id="email" placeholder="E-mail" required>
            </div>
            <br>
            <div>
                <input type="password" name="senha" id="senha" placeholder="Senha" required>
            </div>
            <br>
            <div>
                <button type="submit" id="botao">Entrar</button>
            </div>
            <br>
            <div>
                <a href="cadastrar.php">Criar Login</a>
            </div>
            <?php
                session_start();
                //verifica se a requisicao http foi feita via POST
                // carante que o codigo so execute quando o formulario or enviado corrretamente
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    //conectando o banco de dados
                    $host = '127.0.0.1'; //endereco do servidor do banco de dados
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

                    //filter_input - pegas os valores enviados pelo formulario via post e aplica um filtro de sanitizacao
                    //FILTER_SANITIZE_STRING - remove caracteres potencialmete perigosos
                    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
                    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

                    // Consulta ao banco de dados para verificar se o email e a senha estao certos
                    $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
                    //$pdo->prepare($sql)- prepara a consulta SQL
                    $stmt = $pdo->prepare($sql);
                    //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':senha', $senha);
                    //executa a consulta
                    $stmt->execute();

                    //$stmt->rowCount - verifica se houve alguma correspondencia no banco
                    if ($stmt->rowCount() > 0) {

                        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                        $tipoUsuario = $usuario['tipo'];

                        //se houver um usuario com email e senha informados a sessao é iniciada
                        $_SESSION['email'] = $email;
                        $_SESSION['tipo'] = $tipoUsuario;

                        if($tipoUsuario == 'administrador'){
                            header('Location: admin.php');
                        }else{
                            //header - envia um cabecalho http informando ao navegador que ele deve redirecionar para inicial.html
                            header('Location: inicial.html');
                        }
                        //interrompe a execucao imediatamente para garantir que nada mais seja processado apos o redirecionamento
                        exit;
                    } else {
                        echo "ERRO: E-MAIL OU SENHA INCORRETOS!";
                        exit;
                    }
                }
            ?>
        </form>
        <p id="mensagem"></p>
    </div>
    <script>
        function login(){
            //window - objeto global que representa a janela do navegador
            window.location.href = "inicial.html"
        }
    </script>
</body>
</html>