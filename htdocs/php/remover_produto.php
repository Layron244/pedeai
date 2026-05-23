<?php

include("conectar.php");

$id = $_GET['id'];

$conexao->query(
"DELETE FROM produtos WHERE id='$id'"
);

header("Location: ../dono.php");

?>