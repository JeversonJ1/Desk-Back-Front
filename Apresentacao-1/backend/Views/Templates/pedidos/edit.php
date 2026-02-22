<?php
$pedido = $dados['pedido'] ?? [];
?>

<div class="page-wrapper">
    <div class="top-navigation">
        <a href="/backend/pedido/listar" class="btn-back-lux">
            <i class="fa fa-chevron-left"></i> VOLTAR PARA LISTAGEM
        </a>
    </div>

    <div class="premium-card">
        <div class="card-header">
            <div class="header-icon">
                <i class="fa fa-file-invoice-dollar"></i>
            </div>
            <h1 class="gold-gradient-text">Editar Pedido <span class="order-number">#<?= $pedido['id_pedido'] ?? '2'; ?></span></h1>
            <p class="subtitle">Gestão de status financeiro e logístico da transação</p>
        </div>

        <form action="/backend/pedido/atualizar/<?= (int)$pedido['id_pedido']; ?>" method="POST" class="modern-form">
            <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido']; ?>">

            <div class="grid-row">
                <div class="grid-col">
                    <label class="label-premium">Data e Hora do Registro</label>
                    <div class="input-wrapper">
                        <i class="fa fa-calendar-alt icon-inner"></i>
                        <input type="datetime-local" name="data_pedido" 
                               value="<?= !empty($pedido['data_pedido']) ? date('Y-m-d\TH:i', strtotime($pedido['data_pedido'])) : ''; ?>" 
                               class="input-dark">
                    </div>
                </div>
                
                <div class="grid-col">
                    <label class="label-premium">Valor Total da Venda</label>
                    <div class="input-wrapper">
                        <span class="currency-label">R$</span>
                        <input type="number" step="0.01" name="total_pedido" 
                               value="<?= $pedido['total_pedido'] ?? '0.00'; ?>" 
                               class="input-dark padding-currency">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="label-premium">Status Logístico / Financeiro</label>
                <div class="select-luxury-wrapper">
                    <select name="status_pedido" class="select-luxury">
                        <?php 
                        $statuses = ['pendente', 'pago', 'enviado', 'concluido', 'cancelado'];
                        $current = $pedido['status_pedido'] ?? '';
                        foreach ($statuses as $s): ?>
                            <option value="<?= $s; ?>" <?= ($current === $s) ? 'selected' : ''; ?>>
                                <?= strtoupper($s); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save-gold">
                    <i class="fa fa-save"></i> ATUALIZAR REGISTRO
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Layout Base */
    .page-wrapper {
        padding: 60px 20px;
        max-width: 800px;
        margin: 0 auto;
    }

    /* Botão Voltar (Resolvendo o problema de estar camuflado) */
    .top-navigation { margin-bottom: 20px; }
    
    .btn-back-lux {
        color: #f2cc7d; /* Dourado para destaque no fundo preto */
        text-decoration: none;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: 1px solid rgba(242, 204, 125, 0.3);
        border-radius: 50px;
        transition: 0.3s;
    }

    .btn-back-lux:hover {
        background: rgba(242, 204, 125, 0.1);
        border-color: #f2cc7d;
        transform: translateX(-5px);
    }

    /* Card Premium */
    .premium-card {
        background: #111; /* Cor padrão Koketsu em image_de6f3f.png */
        border: 1px solid #222;
        border-radius: 25px;
        padding: 50px;
        box-shadow: 0 50px 100px rgba(0,0,0,0.8);
        position: relative;
    }

    .card-header { text-align: center; margin-bottom: 40px; }
    
    .header-icon { 
        font-size: 30px; 
        color: #333; 
        margin-bottom: 15px; 
    }

    .gold-gradient-text {
        background: linear-gradient(135deg, #f2cc7d 0%, #b8860b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 32px;
        font-weight: 900;
        margin: 0;
    }

    .order-number { color: #444; -webkit-text-fill-color: #444; }
    .subtitle { color: #666; font-size: 14px; margin-top: 10px; }

    /* Inputs e Labels */
    .grid-row { display: flex; gap: 20px; margin-bottom: 25px; }
    .grid-col { flex: 1; }

    .label-premium {
        display: block;
        color: #f2cc7d;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }

    .input-wrapper { position: relative; display: flex; align-items: center; }
    
    .icon-inner, .currency-label {
        position: absolute;
        left: 15px;
        color: #555;
        font-size: 14px;
        pointer-events: none;
    }

    .input-dark {
        background: #1a1a1a !important; /* Estilo image_10e727.png */
        border: 1px solid #333;
        color: #fff;
        padding: 15px 15px 15px 45px;
        border-radius: 12px;
        width: 100%;
        font-size: 16px;
        transition: 0.3s;
    }

    .input-dark:focus {
        border-color: #f2cc7d;
        background: #222 !important;
        box-shadow: 0 0 15px rgba(242, 204, 125, 0.1);
        outline: none;
    }

    /* Select Luxury */
    .select-luxury-wrapper { position: relative; }
    
    .select-luxury {
        background: #1a1a1a;
        color: #f2cc7d;
        border: 1px solid #333;
        padding: 15px;
        border-radius: 12px;
        width: 100%;
        appearance: none;
        font-weight: 700;
        cursor: pointer;
    }

    /* Botão Salvar (O mesmo usado em image_10e45e.png) */
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
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(212, 167, 42, 0.2);
        margin-top: 20px;
    }

    .btn-save-gold:hover {
        transform: translateY(-3px);
        filter: brightness(1.2);
        box-shadow: 0 15px 30px rgba(212, 167, 42, 0.4);
    }

    /* Ajuste para ícone de data */
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }

    @media (max-width: 600px) {
        .grid-row { flex-direction: column; }
    }
</style>