<?php
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $preco = (isset($_POST["preco"]) && $_POST["preco"] != null) ? $_POST["preco"] : "";
    $quantidade = (isset($_POST["quantidade"]) && $_POST["quantidade"] != null) ? $_POST["quantidade"] : "";
} else if (!isset($id)) {
    // Se não foi citado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $preco = NULL;
    $quantidade = NULL;
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
            $stmt = $pdo->prepare("UPDATE produtos SET nome=?, preco=?, quantidade=? WHERE id = ?");
            //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
            $stmt->bindParam(4, $id);
        } else {
            //$pdo->prepare($sql)- prepara a consulta SQL
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, quantidade) VALUES (?, ?, ?)");
        }
        //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $preco);
        $stmt->bindParam(3, $quantidade);
        
        //executa a consulta
        if ($stmt->execute()) {
            //$stmt->rowCount - verifica se houve alguma correspondencia no banco
            if ($stmt->rowCount() > 0) {
                //echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $preco = null;
                $quantidade = null;
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
 
// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
//verifica se a variável $_REQUEST["act"] está definida e não é null
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        //$pdo->prepare($sql)- prepara a consulta SQL
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
        //PDO::PARAM_INT - o parametro vinculado a consulta SQL deve ser tratado como numero inteiro
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        //executa a consulta
        if ($stmt->execute()) {
            //PDO::FETCH_OBJ - ira retornar os resultados como objetos
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $preco = $rs->preco;
            $quantidade = $rs->quantidade;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if utilizado pela etapa Delete
//verifica se a variável $_REQUEST["act"] está definida e não é null
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        //$pdo->prepare($sql)- prepara a consulta SQL
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
        //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        //executa a consulta
        if ($stmt->execute()) {
            //echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

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

// Bloco if utilizado pela etapa Delete
//verifica se a variável $_REQUEST["act"] está definida e não é null
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        //$pdo->prepare($sql)- prepara a consulta SQL
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        //$stmt->bindParam - associam os valores às variaveis protegendo conta injucoes SQL
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        //executa a consulta
        if ($stmt->execute()) {
            //echo "Registo foi excluído com êxito";
            $id = null;
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
    <link rel="stylesheet" href="admin.css">
</head>
    <body>
        <div class="container">
            <form action="?act=save" method="POST" name="form1" >
                <h1>Cadastro de Joias</h1>
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
                Preço:
                <input type="number" name="preco" <?php

                // Preenche o email no campo email com um valor "value"
                //isset($id) - verifica se a variavel esta definida e nao é null
                if (isset($preco) && $preco != null || $preco != "") {
                    echo "value=\"{$preco}\"";
                }
                ?> />
                Quantidade:
                <input type="number" name="quantidade" <?php

                // Preenche o celular no campo celular com um valor "value"
                //isset($id) - verifica se a variavel esta definida e nao é null
                if (isset($quantidade) && $quantidade != null || $quantidade != "") {
                    echo "value=\"{$quantidade}\"";
                }
                ?> />
                <br><br>
                <input type="submit" value="salvar" />
            </form>
            <br>
            <table border="1" width="100%">
                <tr>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Quatidade</th>
                    <th>Opções</th>
                </tr>
                <?php

                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    //$pdo->prepare($sql)- prepara a consulta SQL
                    $stmt = $pdo->prepare("SELECT * FROM produtos ORDER BY preco ASC");
                    //executa a consulta
                    if ($stmt->execute()) {
                        //fetch - reupera a proxima linha do resultado da consulta
                        //PDO::FETCH_OBJ - faz com que os dados sejam retornados como um objeto
                        //cada linha da consulta sera acessada como uma instancia de um objeto
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            //$rs - armazena o objeto da linha atual, e a cada iteração do loop, um novo conjunto de dados é extraído da consulta.
                            echo "<td>".$rs->nome."</td><td>".$rs->preco."</td><td>".$rs->quantidade
                                        ."</td><td><center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                        ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                        ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>
            <br>
            <table border="1" width="100%">
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Senha</th>
                    <th>Tipo</th>
                    <th>Opção</th>
                </tr>
                <?php

                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    //$pdo->prepare($sql)- prepara a consulta SQL
                    $stmt = $pdo->prepare("SELECT * FROM usuarios");
                    //executa a consulta
                    if ($stmt->execute()) {
                        //fetch - reupera a proxima linha do resultado da consulta
                        //PDO::FETCH_OBJ - faz com que os dados sejam retornados como um objeto
                        //cada linha da consulta sera acessada como uma instancia de um objeto
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            //$rs - armazena o objeto da linha atual, e a cada iteração do loop, um novo conjunto de dados é extraído da consulta.
                            echo "<td>".$rs->nome."</td><td>".$rs->email."</td><td>".$rs->senha."</td><td>".$rs->tipo
                                        ."</td><td><center><a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>
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
