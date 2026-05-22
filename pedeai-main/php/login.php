<?php

include("conectar.php");

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$sql = "
SELECT * FROM usuarios
WHERE usuario='$usuario'
AND senha='$senha'
";

$resultado = $conexao->query($sql);

if($resultado->num_rows > 0){

    $dados = $resultado->fetch_assoc();

    if($dados['tipo'] == "cliente"){

        header("Location: ../index.html");

    }

    elseif($dados['tipo'] == "dono"){

        header("Location: ../dono.html");

    }

    elseif($dados['tipo'] == "admin"){

        header("Location: ../admin.html");

    }

}else{

    echo "
    <script>

        alert('Login inválido');

        window.location='../login.html';

    </script>
    ";

}

?>