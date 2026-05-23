<?php
// php/alterar_status_loja.php
session_start();
include("conectar.php");

// Proteção básica: só permite se estiver logado (idealmente validar se é admin)
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../login.html");
    exit;
}

$id_loja = intval($_GET['id']);
$novo_status = $_GET['status'] == 'suspenso' ? 'suspenso' : 'ativo';

// Atualiza o status apenas daquela loja específica
$sql = "UPDATE loja SET status = '$novo_status' WHERE id = $id_loja";
$conexao->query($sql);

header("Location: ../admin.php");
exit;
?>