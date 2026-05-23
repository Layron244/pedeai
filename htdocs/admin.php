<?php
// admin.php
session_start();
include("php/conectar.php");

// Proteção: Garante que apenas administradores acessem
if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'admin'){
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo Geral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#2b2d42; color:white; padding:40px; }
        .panel { background:#3d405b; padding:30px; border-radius:20px; max-width:900px; margin:auto; }
        .card-custom { background:white; color:black; padding:20px; border-radius:15px; margin-bottom:15px; }
    </style>
</head>
<body>

<div class="panel">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 border-bottom pb-3">
        <h2>🛡️ Painel do Administrador</h2>
        
        <!-- Grupo de ações atualizado para o administrador ir e vir facilmente -->
        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-warning fw-bold btn-sm px-3 text-dark">🛒 Ver App (Lanchonetes)</a>
            <a href="index.php" class="btn btn-warning fw-bold btn-sm px-3 text-dark"> Desconectar</a>
        </div>
    </div>

    <!-- SEÇÃO 1: GERENCIAR LOJAS CADASTRADAS -->
    <h3 class="mb-3 text-warning">🏪 Gerenciar Lanchonetes</h3>
    <?php
    $sqlLojas = "SELECT * FROM loja ORDER BY nome ASC";
    $resLojas = $conexao->query($sqlLojas);

    if($resLojas->num_rows > 0){
        while($loja = $resLojas->fetch_assoc()){
            $isSuspensa = ($loja['status'] == 'suspenso');
            ?>
            <div class="card-custom d-flex justify-content-between align-items-center <?php echo $isSuspensa ? 'border border-danger border-3' : ''; ?>">
                <div>
                    <h5 class="mb-1"><strong><?php echo htmlspecialchars($loja['nome']); ?></strong></h5>
                    <span class="badge bg-secondary"><?php echo htmlspecialchars($loja['tipo']); ?></span>
                    <span class="badge <?php echo $isSuspensa ? 'bg-danger' : 'bg-success'; ?>">
                        <?php echo $isSuspensa ? 'SUSPENSA' : 'ATIVA'; ?>
                    </span>
                </div>
                <div>
                    <?php if($isSuspensa){ ?>
                        <a href="php/alterar_status_loja.php?id=<?php echo $loja['id']; ?>&status=ativo" class="btn btn-success btn-sm fw-bold">Reativar Loja</a>
                    <?php } else { ?>
                        <a href="php/alterar_status_loja.php?id=<?php echo $loja['id']; ?>&status=suspenso" class="btn btn-danger btn-sm fw-bold" onclick="return confirm('Suspender esta loja?')">Suspender Loja</a>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='alert alert-light text-center'>Nenhuma loja configurada no sistema.</div>";
    }
    ?>

    <hr class="my-5">

    <!-- SEÇÃO 2: CONTAS PENDENTES -->
    <h3 class="mb-3 text-info">⏳ Novos Parceiros Aguardando Aprovação</h3>
    <?php
    $sqlPendentes = "SELECT * FROM usuarios WHERE tipo='dono' AND status='pendente'";
    $resultado = $conexao->query($sqlPendentes);

    if($resultado->num_rows > 0){
        while($dono = $resultado->fetch_assoc()){
            ?>
            <div class="card-custom d-flex justify-content-between align-items-center">
                <div>
                    <strong><?php echo htmlspecialchars($dono['usuario']); ?></strong><br>
                    <small class="text-muted">Conta criada recentemente</small>
                </div>
                <a href="php/aprovar.php?id=<?php echo $dono['id']; ?>" class="btn btn-primary">Aprovar Cadastro</a>
            </div>
            <?php
        }
    } else {
        echo "<div class='alert alert-light text-center'>Nenhum cadastro pendente.</div>";
    }
    ?>
</div>

</body>
</html>