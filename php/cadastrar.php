<?php

include("conectar.php");

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$tipo = $_POST['tipo'];

$sql = "
INSERT INTO usuarios
(
usuario,
senha,
tipo
)

VALUES
(
'$usuario',
'$senha',
'$tipo'
)
";

if($conexao->query($sql)){

    echo "

    <script>

        alert('Cadastro realizado');

        window.location='../login.html';

    </script>

    ";

}else{

    echo "Erro";

}

?>