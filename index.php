
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PedeAí | Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/cliente.css">
    <style>
        /* Estilização moderna e centralizada para o bloqueio de loja suspensa */
        #bloqueio-suspenso {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(15, 23, 42, 0.95); /* Fundo escuro premium */
            backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card-suspensao {
            background: white;
            color: #1e293b;
            border-radius: 24px;
            padding: 40px 30px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3), 0 10px 10px -5px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .icon-warning {
            font-size: 4rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div id="conteudo-app">
        <div class="header">
            <div class="topo">
                <div>
                    <h4 class="mb-0" id="nome-plataforma">PedeAí 🍔</h4>
                    <small id="status-loja">• Encontre as melhores lanchonetes •</small>
                </div>
                <div class="acoes-login" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <!-- Botão Admin -->
                    <?php if(isset($_SESSION['id_usuario']) && isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin'){ ?>
                        <a href="admin.php" style="display: inline-block; padding: 8px 16px; background-color: #f1c40f; color: #2c3e50; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.15);">⚙️ Painel Admin</a>
                    <?php } ?>

                    <!-- Botão Dono da Loja / Links de Sessão -->
                    <?php if(isset($_SESSION['id_usuario'])){ ?>
                        <?php if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'dono'){ ?>
                            <a href="dono.php" style="display: inline-block; padding: 8px 16px; background-color: #2ecc71; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px;">➕ Adicionar Produtos</a>
                        <?php } ?>
                        <a href="php/logout.php" class="btn-login">🔄 Trocar Conta</a>
                    <?php } else { ?>
                        <a href="login.html" class="btn-login">🔑 Entrar</a>
                        <a href="cadastro.html" class="btn-cadastro">Cadastrar</a>
                    <?php } ?>
  
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <div id="btn-voltar-container" class="mb-3" style="display: none;">
                <a href="index.php" class="btn btn-warning fw-bold">← Voltar para Lanchonetes</a>
            </div>
            <div id="lista-cardapio"></div>
        </div>

        <div class="cart-bar" id="barra-carrinho" onclick="abrirModalPedido()" style="display: none;">
            <div>
                <span class="total-label">Finalizar Pedido</span>
                <span id="total-carrinho" class="total-valor">R$ 0,00</span>
            </div>
            <span class="fw-bold">Ver Pedido →</span>
        </div>
    </div>



    <div id="bloqueio-suspenso">
        <div class="card-suspensao">
            <div class="icon-warning">⚠️</div>
            <h3 class="fw-bold mt-2 text-danger">Acesso Pausado</h3>
            <p class="text-secondary my-3 fs-6">Este estabelecimento foi temporariamente suspenso pela coordenação da plataforma e não está aceitando pedidos no momento.</p>
            <hr class="text-muted opacity-25">
            <a href="index.php" class="btn btn-warning w-100 fw-bold py-2.5 rounded-3 mt-2 shadow-sm text-dark">🔍 Explorar Outras Lanchonetes</a>
        </div>
    </div>
    <!-- Modal do Pedido -->
<div id="modalPedido" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:10000;">
    <div style="background:white; padding:20px; border-radius:10px; max-width:400px; width:100%;">
        <h4>Resumo do Pedido</h4>
        <div id="resumoPedido"></div>
        <hr>
        <input type="text" id="nomeCliente" class="form-control mb-2" placeholder="Nome">
        <input type="text" id="ruaCliente" class="form-control mb-2" placeholder="Rua">
        <input type="text" id="bairroCliente" class="form-control mb-2" placeholder="Bairro">
        <input type="text" id="referenciaCliente" class="form-control mb-2" placeholder="Referência">
        <button class="btn btn-success w-100 mt-2" onclick="enviarWhatsApp()">📲 Enviar para WhatsApp</button>
        <button class="btn btn-secondary w-100 mt-2" onclick="fecharModal()">❌ Cancelar</button>
    </div>
</div>


   <script>
    let carrinho = [];
    let telefoneLoja = "";
    const urlParams = new URLSearchParams(window.location.search);
    const lojaId = urlParams.get('loja_id');

    if (!lojaId) {
        fetch("php/listar_lojas.php")
            .then(res => res.json())
            .then(lojas => {
                if(lojas.length === 0){
                    document.getElementById('lista-cardapio').innerHTML =
                        '<div class="alert alert-light text-center">Nenhuma lanchonete cadastrada ainda.</div>';
                    return;
                }

                document.getElementById('lista-cardapio').innerHTML = lojas.map(l => `
                    <div class="card-produto" style="cursor:pointer;" onclick="location.href='index.php?loja_id=${l.id}'">
                        <div class="img-container">
                            <img src="${l.imagem}" class="img-card">
                        </div>
                        <div class="produto-info">
                            <div class="produto-nome">${l.nome}</div>
                            <span class="badge bg-secondary">${l.tipo}</span>
                        </div>
                    </div>
                `).join('');
            });
    } else {
        document.getElementById('btn-voltar-container').style.display = "block";
        document.getElementById('barra-carrinho').style.display = "flex";

        fetch(`php/listar_produtos.php?loja_id=${lojaId}`)
            .then(res => res.json())
            .then(dados => {

                if(dados.loja && dados.loja.status === "suspenso") {
                    document.getElementById('nome-plataforma').innerText = dados.loja.nome;
                    document.getElementById('status-loja').innerText = "• SUSPENSA •";
                    document.getElementById('bloqueio-suspenso').style.display = "flex";
                    document.getElementById('barra-carrinho').style.display = "none";
                    return;
                }

                if(dados.loja){
                    document.getElementById('nome-plataforma').innerText = dados.loja.nome;
                    document.getElementById('status-loja').innerText = `• Aberto agora (${dados.loja.tipo}) •`;
                    telefoneLoja = dados.loja.telefone;
                }

                if(dados.produtos.length === 0){
                    document.getElementById('lista-cardapio').innerHTML =
                        '<div class="alert alert-light text-center">Essa loja ainda não adicionou produtos.</div>';
                    return;
                }

                document.getElementById('lista-cardapio').innerHTML = dados.produtos.map(p => `
                    <div class="card-produto">
                        <div class="img-container">
                            <img src="${p.imagem}" class="img-card">
                        </div>
                        <div class="produto-info">
                            <div class="produto-nome">${p.nome}</div>
                            <div class="d-flex justify-content-between">
                                <span>R$ ${p.preco}</span>
                                <button onclick="adicionar('${p.nome}', '${p.preco}')">+</button>
                            </div>
                        </div>
                    </div>
                `).join('');
            });
    }

    function adicionar(nome, preco){
        carrinho.push({ nome, preco: parseFloat(preco) });
        atualizarTotal();
    }

    function removerItem(index){
        carrinho.splice(index, 1);
        atualizarTotal();
        abrirModalPedido(); // atualiza modal automaticamente
    }

    function atualizarTotal(){
        let total = carrinho.reduce((s, i) => s + i.preco, 0);
        document.getElementById('total-carrinho').innerText = `R$ ${total.toFixed(2)}`;
    }

    function abrirModalPedido(){
        if(carrinho.length === 0) return alert("Carrinho vazio");

        let resumo = carrinho.map((i, idx) =>
            `• ${i.nome} - R$ ${i.preco.toFixed(2)} 
            <button onclick="removerItem(${idx})" style="margin-left:10px; color:red;">Remover</button>`
        ).join("<br>");

        let total = carrinho.reduce((s, i) => s + i.preco, 0);

        document.getElementById('resumoPedido').innerHTML =
            resumo + "<br><br><b>Total: R$ " + total.toFixed(2) + "</b>";

        document.getElementById('modalPedido').style.display = "flex";
    }

    function fecharModal(){
        document.getElementById('modalPedido').style.display = "none";
    }

    function enviarWhatsApp(){
        let nome = document.getElementById('nomeCliente').value;
        let rua = document.getElementById('ruaCliente').value;
        let bairro = document.getElementById('bairroCliente').value;
        let referencia = document.getElementById('referenciaCliente').value;

        let itens = carrinho.map(i => `• ${i.nome} - R$ ${i.preco.toFixed(2)}`).join("\n");
        let total = carrinho.reduce((s, i) => s + i.preco, 0);

        let mensagem = `📦 Novo Pedido:\n${itens}\n\n💰 Total: R$ ${total.toFixed(2)}\n\n👤 Nome: ${nome}\n🏠 Rua: ${rua}\n📍 Bairro: ${bairro}\n🔎 Referência: ${referencia}`;

        let url = `https://wa.me/${telefoneLoja}?text=${encodeURIComponent(mensagem)}`;
        window.open(url, "_blank");
    }
</script>
</body>
</html>

