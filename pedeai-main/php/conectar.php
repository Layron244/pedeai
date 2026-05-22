<?php

$host = "localhost";
$user = "root";
$pass = "";
$banco = "tonho_lanches";

$conexao = new mysqli($host, $user, $pass, $banco);

if($conexao->connect_error){

    die("Erro conexão");

}

?>
