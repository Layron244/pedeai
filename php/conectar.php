<?php

$host = "sql213.infinityfree.com";
$user = "if0_41997060";
$pass = "graucabo1234";
$banco = "if0_41997060_tonho";

$conexao = new mysqli($host, $user, $pass, $banco);

if($conexao->connect_error){

    die("Erro conexão");

}

?>
