<div class="page-wrapper">
    <div class="top-navigation">
        <a href="/backend/itenspedidos/listar" class="btn-back-lux">
            <i class="fa fa-arrow-left"></i> VOLTAR À LISTA
        </a>
    </div>

    <div class="premium-card">
        <div class="card-header">
            <h2 class="gold-gradient-text">
                <i class="fa fa-plus-circle"></i> NOVO PEDIDO
            </h2>
            <p class="subtitle">Preencha os dados abaixo para registrar uma nova transação no sistema.</p>
        </div>

        <form action="/backend/itenspedidos/salvar" method="post" class="modern-form">
            
            <div class="form-section">
                <h4 class="section-title"><i class="fa fa-info-circle"></i> Informações Básicas</h4>
                
                <div class="grid-row">
                    <div class="grid-col">
                        <label class="label-premium">ID do Perfil (Cliente)</label>
                        <div class="input-wrapper">
                            <i class=""></i>
                            <input type="number" name="id_perfil" placeholder="Ex: 5" required class="input-dark">
                        </div>
                    </div>
                    <div class="grid-col">
                        <label class="label-premium">Data do Pedido</label>
                        <div class="input-wrapper">
                            <input type="date" name="data_pedido" value="<?= date('Y-m-d') ?>" required class="input-dark">
                        </div>
                    </div>
                </div>

                <div class="grid-row">
                    <div class="grid-col">
                        <label class="label-premium">Valor Total (R$)</label>
                        <div class="input-wrapper">
                            <span class="currency-label">R$</span>
                            <input type="number" step="0.01" name="total_pedido" placeholder="0.00" required class="input-dark padding-currency">
                        </div>
                    </div>
                    <div class="grid-col">
                        <label class="label-premium">Status Inicial</label>
                        <div class="select-wrapper">
                            <select name="status_pedido" class="select-luxury">
                                <option value="pendente">PENDENTE</option>
                                <option value="pago">PAGO</option>
                                <option value="cancelado">CANCELADO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section highlight-section">
                <h4 class="section-title"><i class="fa fa-box-open"></i> Item (Produto Inicial)</h4>
                
                <div class="grid-row">
                    <div class="grid-col">
                        <label class="label-premium">ID do Produto</label>
                        <input type="number" name="itens[0][id_produto]" placeholder="Ex: 101" required class="input-dark">
                    </div>
                    <div class="grid-col" style="flex: 0.5;">
                        <label class="label-premium">Quantidade</label>
                        <input type="number" name="itens[0][quantidade]" value="1" required class="input-dark text-center">
                    </div>
                    <div class="grid-col">
                        <label class="label-premium">Preço Unitário (R$)</label>
                        <div class="input-wrapper">
                            <span class="currency-label">R$</span>
                            <input type="number" step="0.01" name="itens[0][preco_unitario]" placeholder="0.00" required class="input-dark padding-currency">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn-save-gold">
                    <i class="fa fa-check"></i> CRIAR PEDIDO
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Reset e Base do Dashboard */
    .page-wrapper {
        padding: 40px 20px;
        max-width: 850px;
        margin: 0 auto;
        min-height: 100vh;
    }

    /* Navegação */
    .top-navigation { margin-bottom: 25px; }
    .btn-back-lux {
        color: #f2cc7d;
        text-decoration: none;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
        border: 1px solid rgba(242, 204, 125, 0.2);
        padding: 10px 20px;
        border-radius: 50px;
        transition: 0.3s;
    }
    .btn-back-lux:hover {
        background: rgba(242, 204, 125, 0.1);
        border-color: #f2cc7d;
    }

    /* Card Premium */
    .premium-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 45px;
        box-shadow: var(--shadow-md);
    }

    .card-header { text-align: center; margin-bottom: 40px; }
    .gold-gradient-text {
        background: linear-gradient(135deg, #f2cc7d 0%, #b8860b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 28px;
        font-weight: 900;
        letter-spacing: 2px;
        margin: 0;
    }
    .subtitle { color: var(--text-muted); font-size: 13px; margin-top: 8px; }

    /* Seções do Formulário */
    .form-section { margin-bottom: 35px; }
    .section-title {
        color: var(--text-muted);
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
    }
    .highlight-section {
        background: var(--bg-main);
        padding: 25px;
        border-radius: 15px;
        border: 1px dashed var(--border-color);
    }

    /* Inputs e Grid */
    .grid-row { display: flex; gap: 20px; margin-bottom: 20px; }
    .grid-col { flex: 1; }
    
    .label-premium {
        display: block;
        color: #f2cc7d;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .input-wrapper { position: relative; display: flex; align-items: center; }
    .icon-inner, .currency-label {
        position: absolute;
        left: 15px;
        color: var(--text-muted);
        font-size: 13px;
    }

    .input-dark {
        background: var(--bg-card) !important;
        border: 1px solid var(--border-color);
        color: var(--text-main);
        padding: 14px 15px;
        border-radius: 10px;
        width: 100%;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .input-dark:focus {
        border-color: #f2cc7d;
        outline: none;
        box-shadow: 0 0 10px rgba(242, 204, 125, 0.1);
    }
    .padding-currency { padding-left: 45px; }
    .text-center { text-align: center; }

    /* Select Customizado */
    .select-luxury {
        background: var(--bg-card);
        color: var(--text-main);
        border: 1px solid var(--border-color);
        padding: 14px;
        border-radius: 10px;
        width: 100%;
        appearance: none;
        cursor: pointer;
    }

    /* Botão Salvar */
    .btn-save-gold {
        background: linear-gradient(180deg, #f2cc7d 0%, #d4a74a 100%);
        color: #000;
        border: none;
        padding: 20px;
        width: 100%;
        border-radius: 12px;
        font-weight: 900;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: var(--shadow-md);
    }
    .btn-save-gold:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }

    /* Dark Mode p/ o seletor de data */
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }

    @media (max-width: 600px) {
        .grid-row { flex-direction: column; }
    }
</style>