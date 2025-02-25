<?php
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $email = (isset($_POST["email"]) && $_POST["email"] != null) ? $_POST["email"] : "";
    $senha = (isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : NULL;
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : "";
} else if (!isset($id)) {
    // Se não foi citado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $email = NULL;
    $senha = NULL;
    $tipo = NULL;
}
 
// Cria a conexão com o banco de dados
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
 
// Bloco If que Salva os dados no Banco - atua como Create e Update
//verifica se a variável $_REQUEST["act"] está definida e não é null
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        if ($id != "") {
            //$pdo->prepare($sql)- prepara a consulta SQL
            $stmt = $pdo->prepare("UPDATE usuarios SET nome=?, email=?, senha=?, tipo=? WHERE id = ?");
            //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
            $stmt->bindParam(5, $id);
        } else {
            //$pdo->prepare($sql)- prepara a consulta SQL
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        }
        //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $senha);
        $stmt->bindParam(4, $tipo);
        
        //executa a consulta
        if ($stmt->execute()) {
            //$stmt->rowCount - verifica se houve alguma correspondencia no banco
            if ($stmt->rowCount() > 0) {
                //echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $email = null;
                $senha = null;
                $tipo = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cadastrar.css">
</head>
    <body>
        <div class="container">
            <form action="?act=save" method="POST" name="form1" >
                <h1>Cadastro de Usuários</h1>
                <input type="hidden" name="id" <?php
                    
                // Preenche o id no campo id com um valor "value"
                //isset($id) - verifica se a variavel esta definida e nao é null
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />
                Nome:
                <input type="text" name="nome" <?php

                // Preenche o nome no campo nome com um valor "value"
                //isset($id) - verifica se a variavel esta definida e nao é null
                if (isset($nome) && $nome != null || $nome != "") {
                    echo "value=\"{$nome}\"";
                }
                ?> />
                E-mail:
                <input type="text" name="email" <?php

                // Preenche o email no campo email com um valor "value"
                //isset($id) - verifica se a variavel esta definida e nao é null
                if (isset($email) && $email != null || $email != "") {
                    echo "value=\"{$email}\"";
                }
                ?> />
                Senha:
                <input type="text" name="senha" <?php

                // Preenche o celular no campo celular com um valor "value"
                //isset($id) - verifica se a variavel esta definida e nao é null
                if (isset($senha) && $senha != null || $senha != "") {
                    echo "value=\"{$senha}\"";
                }
                ?> />
                Tipo:
                <input type="text" name="tipo" <?php

                // Preenche o nome no campo nome com um valor "value"
                //isset($id) - verifica se a variavel esta definida e nao é null
                if (isset($tipo) && $tipo != null || $tipo != "") {
                    echo "value=\"{$tipo}\"";
                }
                ?> />
                <br><br>
                <input type="submit" value="salvar" />
                <br><br>
                <input type="reset" value="Limpar" />
            </form>
            <br>
            <button type="submit" onclick="login()">Voltar para o Login</button>
            <script>
                function login() {
                    window.location.href = "login.php"
                }
            </script>
        </div>
    </body>
</html>