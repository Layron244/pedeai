<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("conectar.php");

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$tipo = $_POST['tipo'];

$status = "ativo";

// ESTABELECIMENTO FICA PENDENTE

if($tipo == "dono"){

    $status = "pendente";

}

// VERIFICAR SE USUÁRIO JÁ EXISTE

$verifica = "
SELECT * FROM usuarios
WHERE usuario='$usuario'
";

$resultado = $conexao->query($verifica);

if($resultado->num_rows > 0){

    echo "

    <script>

        alert('Usuário já existe');

        window.location='../cadastro.html';

    </script>

    ";

    exit;

}

// CADASTRAR

$sql = "
INSERT INTO usuarios
(
    usuario,
    senha,
    tipo,
    status
)

VALUES
(
    '$usuario',
    '$senha',
    '$tipo',
    '$status'
)
";

if($conexao->query($sql)){

    // CONTA ESTABELECIMENTO

    if($tipo == "dono"){

        echo "

        <script>

            alert('Conta enviada para análise do administrador');

            window.location='../index.php';

        </script>

        ";

    }

    // OUTRAS CONTAS

    else{

        echo "

        <script>

            alert('Cadastro realizado com sucesso');

            window.location='../login.html';

        </script>

        ";

    }

}else{

    echo "

    <h2>Erro ao cadastrar</h2>

    <p>" . $conexao->error . "</p>

    ";

}

?>