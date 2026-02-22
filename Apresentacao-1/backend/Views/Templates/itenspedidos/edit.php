<div class="page-wrapper">
    <div class="top-navigation">
        <a href="/backend/itenspedidos/listar" class="btn-back-lux">
            <i class="fa fa-chevron-left"></i> VOLTAR PARA ITENS
        </a>
    </div>

    <div class="premium-card">
        <div class="card-header">
            <div class="header-icon">
                <i class="fa fa-edit"></i>
            </div>
            <h2 class="gold-gradient-text">EDITAR ITEM <span class="item-number">#<?= htmlspecialchars($itempedido['id_itens_pedidos']) ?></span></h2>
            <p class="subtitle">Ajuste de quantidade e valores para o pedido vinculado</p>
        </div>

        <form action="/backend/itenspedidos/atualizar/<?php echo $itempedido['id_itens_pedidos']; ?>" method="post" class="modern-form">
            
            <div class="info-row-static">
                <div class="static-box">
                    <label>Pedido Vinculado</label>
                    <a href="/backend/pedido/listar/<?= htmlspecialchars($itempedido['id_pedido']) ?>" class="link-cyan">
                        <i class="fa fa-external-link-alt"></i> #<?php echo $itempedido['id_pedido']; ?>
                    </a>
                </div>
                <div class="static-box">
                    <label>Produto Selecionado</label>
                    <span class="product-name"><?php echo htmlspecialchars($itempedido['nome_produto'] ?? 'ID ' . $itempedido['id_produto']); ?></span>
                </div>
            </div>

            <hr class="divider-dark">

            <div class="grid-row">
                <div class="grid-col">
                    <label class="label-premium">Quantidade</label>
                    <div class="input-wrapper">
                        <i class="fa fa-sort-numeric-up icon-inner"></i>
                        <input class="input-dark" type="number" id="quantidade" 
                               value="<?php echo $itempedido['quantidade']; ?>" 
                               name="quantidade" required min="1">
                    </div>
                </div>

                <div class="grid-col">
                    <label class="label-premium">Preço Unitário (R$)</label>
                    <div class="input-wrapper">
                        <span class="currency-label">R$</span>
                        <input class="input-dark padding-currency" type="number" step="0.01" 
                               id="preco_unitario" value="<?php echo $itempedido['preco_unitario']; ?>" 
                               name="preco_unitario" required>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save-gold">
                    <i class="fa fa-sync-alt"></i> ATUALIZAR ITEM
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Base do Dash e Background */
    .page-wrapper {
        padding: 50px 20px;
        max-width: 650px;
        margin: 0 auto;
    }

    /* Botão Voltar (Resolvendo camuflagem) */
    .top-navigation { margin-bottom: 20px; }
    .btn-back-lux {
        color: #f2cc7d;
        text-decoration: none;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        padding: 10px 20px;
        border: 1px solid rgba(242, 204, 125, 0.2);
        border-radius: 50px;
        transition: 0.3s;
    }
    .btn-back-lux:hover { background: rgba(242, 204, 125, 0.1); border-color: #f2cc7d; }

    /* Card Premium */
    .premium-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 40px;
        box-shadow: var(--shadow-md);
    }

    .card-header { text-align: center; margin-bottom: 35px; }
    .header-icon { font-size: 28px; color: var(--text-muted); margin-bottom: 10px; }

    .gold-gradient-text {
        background: linear-gradient(135deg, #f2cc7d 0%, #b8860b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 24px;
        font-weight: 900;
        margin: 0;
    }
    .item-number { color: var(--text-muted); -webkit-text-fill-color: var(--text-muted); }
    .subtitle { color: var(--text-muted); font-size: 13px; margin-top: 8px; }

    /* Informações Estáticas */
    .info-row-static { display: flex; gap: 20px; margin-bottom: 20px; }
    .static-box { 
        flex: 1; 
        background: var(--bg-main); 
        padding: 15px; 
        border-radius: 12px; 
        border-left: 3px solid var(--accent-gold); 
    }
    .static-box label { display: block; color: var(--text-muted); font-size: 10px; text-transform: uppercase; font-weight: 800; }
    .product-name { color: var(--text-main); font-weight: 600; font-size: 14px; }
    .link-cyan { color: #00bcd4; text-decoration: none; font-weight: 700; font-size: 15px; }

    .divider-dark { border: 0; border-top: 1px solid var(--border-color); margin: 25px 0; }

    /* Estilização de Inputs */
    .grid-row { display: flex; gap: 20px; }
    .grid-col { flex: 1; }
    .label-premium { display: block; color: #f2cc7d; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px; }

    .input-wrapper { position: relative; display: flex; align-items: center; }
    .icon-inner, .currency-label { position: absolute; left: 15px; color: var(--text-muted); font-size: 14px; }

    .input-dark {
        background: var(--bg-card) !important;
        border: 1px solid var(--border-color);
        color: var(--text-main);
        padding: 15px 15px 15px 45px;
        border-radius: 12px;
        width: 100%;
        font-size: 16px;
        transition: 0.3s;
    }
    .input-dark:focus { border-color: var(--accent-gold); outline: none; background: var(--bg-main) !important; box-shadow: var(--shadow-sm); }
    .padding-currency { padding-left: 45px; }

    /* Botão de Salvar */
    .btn-save-gold {
        background: linear-gradient(180deg, #f2cc7d 0%, #d4a74a 100%);
        color: #000;
        border: none;
        padding: 20px;
        width: 100%;
        border-radius: 12px;
        font-weight: 900;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        margin-top: 30px;
        transition: 0.3s;
        box-shadow: var(--shadow-md);
    }
    .btn-save-gold:hover { transform: translateY(-3px); filter: brightness(1.2); box-shadow: 0 15px 30px rgba(212, 167, 74, 0.3); }

    @media (max-width: 600px) { .grid-row, .info-row-static { flex-direction: column; } }
</style>