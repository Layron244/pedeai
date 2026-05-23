<?php
include("conectar.php");

// Puxa todos os estabelecimentos cadastrados
$sql = "SELECT * FROM loja ORDER BY id DESC";
$resultado = $conexao->query($sql);

$lojas = [];
while($row = $resultado->fetch_assoc()){
    $lojas[] = $row;
}

echo json_encode($lojas);
?>