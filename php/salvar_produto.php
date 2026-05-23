<?php
session_start();
include("conectar.php");

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$loja_id = $_POST['loja_id']; // ID vindo oculto do formulário

$pasta = "../uploads/";

if(!file_exists($pasta)){
    mkdir($pasta, 0777, true);
}

$nomeArquivo = time() . "_" . $_FILES['foto']['name'];
$caminho = $pasta . $nomeArquivo;

move_uploaded_file(
    $_FILES['foto']['tmp_name'],
    $caminho
);

$imagemBanco = "uploads/" . $nomeArquivo;

// Inserção vinculando o produto com o ID da sua respectiva loja
$sql = "
INSERT INTO produtos (nome, preco, imagem, loja_id) 
VALUES ('$nome', '$preco', '$imagemBanco', '$loja_id')
";

$conexao->query($sql);

header("Location: ../dono.php");
?>