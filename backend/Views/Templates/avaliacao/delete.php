<style>
    .delete-container {
        padding: 40px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        max-width: 600px;
        margin: 50px auto;
        text-align: center;
        box-shadow: var(--shadow-md);
    }

    .delete-icon {
        font-size: 60px;
        color: #ff4444;
        margin-bottom: 20px;
        background: rgba(255, 68, 68, 0.1);
        width: 100px;
        height: 100px;
        line-height: 100px;
        border-radius: 50%;
        display: inline-block;
    }

    .delete-container h2 {
        color: var(--text-main);
        font-weight: 800;
        margin-bottom: 15px;
    }

    .delete-container p {
        color: var(--text-muted);
        margin-bottom: 30px;
        font-size: 16px;
    }

    .evaluation-summary {
        background: var(--bg-main);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        text-align: left;
        border-left: 4px solid #ff4444;
    }

    .evaluation-summary p { margin: 5px 0; color: var(--text-main); font-size: 14px; }
    .evaluation-summary strong { color: var(--accent); }

    .actions {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .btn-cancel {
        padding: 12px 25px;
        background: var(--bg-main);
        color: var(--text-main);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        transition: 0.3s;
    }

    .btn-cancel:hover { background: var(--border-color); }

    .btn-confirm {
        padding: 12px 25px;
        background: #ff4444;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        text-transform: uppercase;
    }

    .btn-confirm:hover { background: #cc0000; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(255, 68, 68, 0.3); }
</style>

<div class="delete-container">
    <div class="delete-icon"><i class="fa fa-trash"></i></div>
    <h2>Confirmar Exclusão</h2>
    <p>Você tem certeza que deseja excluir esta avaliação? Esta ação não pode ser desfeita.</p>

    <div class="evaluation-summary">
        <p><strong>Produto:</strong> <?= htmlspecialchars($avaliacao['nome_produto']) ?></p>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($avaliacao['nome_cliente']) ?></p>
        <p><strong>Comentário:</strong> "<?= htmlspecialchars($avaliacao['comentario_avaliacoes']) ?>"</p>
    </div>

    <form action="/backend/avaliacao/deletar/<?= $avaliacao['id_avaliacoes'] ?>" method="POST" class="actions">
        <a href="/backend/avaliacao/listar" class="btn-cancel">Cancelar</a>
        <button type="submit" class="btn-confirm">Excluir Agora</button>
    </form>
</div>
