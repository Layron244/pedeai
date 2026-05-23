<?php

include("conectar.php");

$id = $_GET['id'];

$sql = "
UPDATE usuarios
SET status='ativo'
WHERE id='$id'
";

$conexao->query($sql);

header("Location: ../admin.php");

?>