<?php 
session_start(); 
include("php/conectar.php"); 

// Proteção da página: Se não estiver logado, manda de volta pro login 
if(!isset($_SESSION['id_usuario'])){     
    header("Location: login.html");     
    exit; 
} 

$dono_id = $_SESSION['id_usuario']; 

// VERIFICA SE O DONO JÁ TEM UMA LOJA CADASTRADA 
$sqlLoja = "SELECT * FROM loja WHERE dono_id = '$dono_id'"; 
$resLoja = $conexao->query($sqlLoja); 
$temLoja = $resLoja->num_rows > 0; 
$dadosLoja = $resLoja->fetch_assoc(); 

if($temLoja){     
    $loja_id = $dadosLoja['id'];     
    $resultado = $conexao->query("SELECT * FROM produtos WHERE loja_id = '$loja_id' ORDER BY id DESC"); 
} 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Painel do Estabelecimento - PedeAí</title>     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">     
    <link rel="stylesheet" href="css/style.css">     
    <style>         
        :root { 
            --primary: #ff7300; 
            --dark: #eb8500; 
        }         
        body { 
            background: #f1f4f9; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }         
        .sidebar { 
            background: var(--dark); 
            color: white; 
            min-height: 100vh; 
            padding: 20px; 
        }         
        .card-admin { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 4px 12px rgba(0,0,0,.05); 
        }         
        .img-preview { 
            width: 60px; 
            height: 60px; 
            object-fit: cover; 
            border-radius: 8px; 
        }         
        .btn-loja { 
            display: inline-block; 
            margin-bottom: 20px; 
            font-weight: bold; 
            text-decoration: none; 
        }     
    </style> 
</head> 
<body> 
<div class="container-fluid"> 
    <div class="row">     
        <!-- Barra Lateral -->
        <div class="col-md-2 sidebar d-flex flex-column justify-content-between py-4">         
            <div>             
                <h4 class="fw-bold text-center mb-4">🍔 PedeAí</h4>             
                <hr>             
                <p class="fw-bold text-center bg-white text-dark p-2 rounded small">Painel do Parceiro</p>         
            </div>         
            <div>             
                <a href="php/logout.php" class="btn btn-light btn-sm w-100 fw-bold shadow-sm">Sair / Logout</a>         
            </div>     
        </div>     
        
        <!-- Conteúdo Principal -->
        <div class="col-md-10 p-4">                  
            <a href="index.php" class="btn btn-warning btn-loja text-white px-4 shadow-sm">              
                🛒 Ver Aplicativo do Consumidor (Cardápio)         
            </a>         
            
            <?php if(!$temLoja){ ?>             
                <!-- Formulário para Criar Loja -->
                <div class="card card-admin p-4 mb-4 border-start border-danger border-4">                 
                    <h5 class="text-danger fw-bold">⚠️ Configure o perfil da sua Lanchonete primeiro</h5>                 
                    <p class="text-muted">Antes de adicionar lanches, precisamos dos dados do seu estabelecimento comercial.</p>                 
                    <hr>                                             
                    <form action="php/salvar_loja.php" method="POST" enctype="multipart/form-data">                     
                        <div class="row g-3">                         
                            <div class="col-md-4">                             
                                <label class="form-label fw-semibold">Nome da Lanchonete</label>                             
                                <input type="text" name="nome" class="form-control" placeholder="Ex: BurguerX, Mania de Pizza" required>                         
                            </div>                         
                            <div class="col-md-4">                             
                                <label class="form-label fw-semibold">Tipo do Estabelecimento</label>                             
                                <input type="text" name="tipo" class="form-control" placeholder="Ex: Hamburgueria, Pizzaria, Japonesa" required>                         
                            </div>                         
                            <div class="col-md-4">                             
                                <label class="form-label fw-semibold">WhatsApp de Pedidos</label>                             
                                <input type="text" name="telefone" class="form-control" placeholder="Ex: 5581992320121" required>                         
                            </div>                         
                            <div class="col-md-12">                             
                                <label class="form-label fw-semibold">Logotipo / Foto de Fachada</label>                             
                                <input type="file" name="foto" class="form-control" required>                         
                            </div>                         
                            <div class="col-12 mt-4">                             
                                <button class="btn btn-success w-100 p-2 fw-bold text-uppercase tracking-wider shadow">Salvar e Configurar Loja</button>                         
                            </div>                     
                        </div>                 
                    </form>             
                </div>         
            <?php } else { ?>             
                <!-- Loja já existente: Gerenciar Cardápio -->
                <div class="alert alert-success d-flex justify-content-between align-items-center shadow-sm rounded-3">                 
                    <span>Você está gerenciando o cardápio de: <strong><?php echo htmlspecialchars($dadosLoja['nome']); ?></strong> (<?php echo htmlspecialchars($dadosLoja['tipo']); ?>)</span>                 
                    <span class="badge bg-dark px-3 py-2">ID da Loja: <?php echo $loja_id; ?></span>             
                </div>             
                
                <!-- Adicionar Produto -->
                <div class="card card-admin p-4 mb-4 border-start border-primary border-4">                 
                    <h5 class="fw-bold mb-3 text-primary">Adicionar Produto ao Seu Cardápio</h5>                 
                    <form action="php/salvar_produto.php" method="POST" enctype="multipart/form-data">                     
                        <input type="hidden" name="loja_id" value="<?php echo $loja_id; ?>">                                                  
                        <div class="row g-3 align-items-end">                         
                            <div class="col-md-4">                             
                                <label class="form-label small fw-bold text-muted">Nome do Lanche</label>                             
                                <input type="text" name="nome" class="form-control" placeholder="Ex: X-Burguer Especial" required>                         
                            </div>                         
                            <div class="col-md-2">                             
                                <label class="form-label small fw-bold text-muted">Preço (R$)</label>                             
                                <input type="number" step="0.01" name="preco" class="form-control" placeholder="15.90" required>                         
                            </div>                         
                            <div class="col-md-4">                             
                                <label class="form-label small fw-bold text-muted">Foto do Produto</label>                             
                                <input type="file" name="foto" class="form-control" required>                         
                            </div>                         
                            <div class="col-md-2">                             
                                <button class="btn btn-primary w-100 fw-bold">➕ Salvar Item</button>                         
                            </div>                     
                        </div>                 
                    </form>             
                </div>             
                
                <!-- Lista de Produtos Cadastrados -->
                <div class="card card-admin p-4 shadow-sm">                 
                    <h5 class="fw-bold mb-3 text-secondary">Seus Itens Cadastrados</h5>                 
                    <div class="table-responsive">                     
                        <table class="table table-hover align-middle">                         
                            <thead class="table-light">                             
                                <tr>                                 
                                    <th style="width: 100px;">Foto</th>                                 
                                    <th>Nome do Produto</th>                                 
                                    <th>Preço</th>                                 
                                    <th style="width: 120px;" class="text-center">Ações</th>                             
                                </tr>                         
                            </thead>                         
                            <tbody>                             
                                <?php if($resultado->num_rows > 0){                                   
                                    while($p = $resultado->fetch_assoc()){ ?>                                 
                                        <tr>                                     
                                            <td>                                         
                                                <img src="<?php echo htmlspecialchars($p['imagem']); ?>" class="img-preview border shadow-sm" alt="Foto do produto">                                     
                                            </td>                                     
                                            <td class="fw-semibold text-dark"><?php echo htmlspecialchars($p['nome']); ?></td>                                     
                                            <td class="text-success fw-bold">R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>                                     
                                            <td class="text-center">                                         
                                                <a href="php/remover_produto.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-danger btn-sm px-3 fw-bold" onclick="return confirm('Tem certeza que deseja excluir este item?')">Excluir</a>                                     
                                            </td>                                 
                                        </tr>                                 
                                    <?php }                             
                                } else { ?>                                 
                                    <tr>                                     
                                        <td colspan="4" class="text-center text-muted py-4">Nenhum produto cadastrado no seu cardápio ainda.</td>                                 
                                    </tr>                             
                                <?php } ?>                         
                            </tbody>                     
                        </table>                 
                    </div>             
                </div>         
            <?php } ?>     
        </div> 
    </div> 
</div> 
</body> 
</html>