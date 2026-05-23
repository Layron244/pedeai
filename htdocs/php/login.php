<?php
session_start();
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

    // SALVA OS DADOS DO USUÁRIO NA SESSÃO
    $_SESSION['id_usuario'] = $dados['id'];
    $_SESSION['usuario'] = $dados['usuario'];
    
    // LINHA OBRIGATÓRIA: Garante que o PHP lembre se ele é 'dono', 'cliente' ou 'admin'
    $_SESSION['tipo'] = $dados['tipo']; 

    // CLIENTE
    if($dados['tipo'] == "cliente"){
        header("Location: ../index.php");
        exit;
    }
    // DONO
    elseif($dados['tipo'] == "dono"){
        if($dados['status'] != "ativo"){
            echo "
            <script>
                alert('Sua conta ainda está em análise pelo administrador');
                window.location='../login.html';
            </script>
            ";
            exit;
        }
        header("Location: ../dono.php");
        exit;
    }
    // ADMIN
    elseif($dados['tipo'] == "admin"){
        header("Location: ../admin.php");
        exit;
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