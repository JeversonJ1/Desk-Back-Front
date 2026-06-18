<style>
    /* --- DESIGN SYSTEM KOKETSU DELETE PREMIUM --- */
    :root {
        --bg-main: #0f0f0f;
        --bg-card: #1a1a1a;
        --border-color: #222222;
        --text-main: #ffffff;
        --text-muted: #888888;
        --accent: #F2C84B;
        --danger: #dc3545;
        --danger-hover: #b0232e;
        --danger-glow: rgba(220, 53, 69, 0.25);
        --shadow-md: 0 8px 30px rgba(0, 0, 0, 0.6);
    }

    .delete-container {
        padding: 40px;
        background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
        border: 2px solid var(--border-color);
        border-radius: 16px;
        max-width: 600px;
        margin: 50px auto;
        text-align: center;
        box-shadow: var(--shadow-md);
        font-family: Arial, sans-serif;
        color: var(--text-main);
    }

    .delete-icon {
        font-size: 46px;
        color: var(--danger);
        background: rgba(220, 53, 69, 0.08);
        width: 90px;
        height: 90px;
        line-height: 90px;
        border-radius: 50%;
        display: inline-block;
        margin-bottom: 25px;
        border: 1px solid rgba(220, 53, 69, 0.15);
        text-shadow: 0 0 12px var(--danger-glow);
        box-shadow: inset 0 0 15px rgba(220, 53, 69, 0.05);
        transition: 0.3s;
    }

    .delete-container:hover .delete-icon {
        transform: scale(1.05) rotate(-3deg);
    }

    .delete-container h2 {
        color: var(--text-main);
        font-weight: 800;
        margin-bottom: 12px;
        text-transform: uppercase;
        font-size: 22px;
        letter-spacing: -0.5px;
    }

    .delete-container p.delete-warning {
        color: var(--text-muted);
        margin-bottom: 30px;
        font-size: 15px;
        line-height: 1.5;
    }

    /* Card de Sumario da Avaliacao */
    .evaluation-summary {
        background: #0f0f0f;
        padding: 22px;
        border-radius: 12px;
        margin-bottom: 35px;
        text-align: left;
        border: 1px solid var(--border-color);
        position: relative;
    }
    
    .evaluation-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: var(--danger);
        border-radius: 12px 0 0 12px;
    }

    .evaluation-summary p {
        margin: 8px 0;
        color: var(--text-main);
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.03);
        padding-bottom: 8px;
    }
    
    .evaluation-summary p:last-child {
        border-bottom: none;
        padding-bottom: 0;
        flex-direction: column;
        gap: 6px;
    }
    
    .evaluation-summary span.summary-label {
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    .evaluation-summary strong {
        color: var(--text-main);
    }
    
    .evaluation-summary strong.prod-highlight {
        color: var(--accent);
    }
    
    .evaluation-summary .comment-quote {
        color: var(--text-muted);
        font-style: italic;
        padding: 8px 12px;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.04);
        margin-top: 4px;
    }

    /* Acoes */
    .actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-cancel {
        padding: 12px 28px;
        background: transparent;
        color: var(--text-muted);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        transition: 0.3s;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cancel:hover {
        border-color: var(--text-main);
        color: var(--text-main);
        box-shadow: 0 2px 8px rgba(255, 255, 255, 0.05);
    }

    .btn-confirm {
        padding: 12px 28px;
        background: linear-gradient(135deg, var(--danger), #b0232e);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px var(--danger-glow);
    }

    .btn-confirm:hover {
        background: var(--danger-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }
    
    .btn-confirm:active {
        transform: translateY(0);
    }
</style>

<div class="delete-container">
    <div class="delete-icon"><i class="fa fa-trash"></i></div>
    <h2>Confirmar Exclusão</h2>
    <p class="delete-warning">Você tem certeza que deseja excluir esta avaliação? Esta ação ocultará o comentário e a nota do storefront da Koketsu (exclusão lógica).</p>

    <!-- Detalhes do registro a ser excluido -->
    <div class="evaluation-summary">
        <p>
            <span class="summary-label">Produto</span>
            <strong class="prod-highlight"><?= htmlspecialchars($avaliacao['nome_produto'] ?? 'Produto não encontrado') ?></strong>
        </p>
        <p>
            <span class="summary-label">Cliente</span>
            <strong>
                <?php if (!empty($avaliacao['nome_cliente'])): ?>
                    <?= htmlspecialchars($avaliacao['nome_cliente']) ?>
                <?php else: ?>
                    Consumidor Convidado #<?= $avaliacao['id_cliente'] ?>
                <?php endif; ?>
            </strong>
        </p>
        <p>
            <span class="summary-label">Comentário</span>
            <span class="comment-quote">"<?= htmlspecialchars($avaliacao['comentario_avaliacoes']) ?>"</span>
        </p>
    </div>

    <!-- Formulario de Deletar -->
    <form action="/backend/avaliacao/deletar/<?= $avaliacao['id_avaliacoes'] ?>" method="POST" class="actions">
        <a href="/backend/avaliacao/listar" class="btn-cancel">Cancelar</a>
        <button type="submit" class="btn-confirm">Excluir Agora</button>
    </form>
</div>
