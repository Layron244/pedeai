<?php
session_start();
include("conectar.php");

$dono_id = $_SESSION['id_usuario'];
$nome = $_POST['nome'];
$tipo = $_POST['tipo'];
$telefone = $_POST['telefone'];

$pasta = "../uploads/";

if(!file_exists($pasta)){
    mkdir($pasta, 0777, true);
}

$nomeArquivo = time() . "_" . $_FILES['foto']['name'];
$caminho = $pasta . $nomeArquivo;

move_uploaded_file($_FILES['foto']['tmp_name'], $caminho);

$imagemBanco = "uploads/" . $nomeArquivo;

// Atualizado para incluir a coluna status como 'ativo'
$sql = "
INSERT INTO loja (dono_id, nome, tipo, imagem, telefone, status) 
VALUES ('$dono_id', '$nome', '$tipo', '$imagemBanco', '$telefone', 'ativo')
";

$conexao->query($sql);

header("Location: ../dono.php");
exit;
?>