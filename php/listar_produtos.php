<?php
// php/listar_produtos.php
include("conectar.php");

$loja_id = isset($_GET['loja_id']) ? intval($_GET['loja_id']) : 0;

// Busca os dados da loja solicitada (incluindo o novo campo status)
$sqlLoja = "SELECT * FROM loja WHERE id = $loja_id";
$resLoja = $conexao->query($sqlLoja);
$loja = $resLoja->fetch_assoc();

// Busca apenas os produtos dessa lanchonete específica
$sqlProd = "SELECT * FROM produtos WHERE loja_id = $loja_id ORDER BY id DESC";
$resProd = $conexao->query($sqlProd);

$produtos = [];
while($row = $resProd->fetch_assoc()){
    $produtos[] = $row;
}

// Retorna tudo encapsulado em um objeto JSON unificado
echo json_encode([
    "loja" => $loja,
    "produtos" => $produtos
]);
?>