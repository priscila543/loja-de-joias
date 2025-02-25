<?php
//inicia a sessao
session_start();

//isset($_GET['remover']) - verifica se a variave foi passada na URL da pagina
//$_GET['remover'] ==  "carrinho" - Verifica se o valor do parâmetro remover na URL é igual a "carrinho"
if(isset($_GET['remover']) && $_GET['remover'] ==  "carrinho") {
    //O valor do parâmetro id é armazenado na variável $idProduto
    //será usada para identificar qual item deve ser removido do carrinho.
    $idProduto = $_GET['id'];

    //unset - remove um elemento de um array
    //remove o item do carrinho armaznamento em $_SESSION['itens'] com a chave correspondente ao [$idProduto]
    unset($_SESSION['itens'][$idProduto]);

    //HTTP-EQUIV="REFRESH" - é uma forma de instruir o navegador a fazer um redirecionamento após um certo tempo
    //CONTENT="0 - o redirecionamento acontecer imediatamente
    // URL=carrinho.php" - o navegador sera redirecionado para a pagina carrinho
    echo '<meta HTTP-EQUIV="REFRESH" CONTENT="0; URL=carrinho.php"/>';
}
?>