<style>
    .edit-container {
        padding: 30px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        max-width: 800px;
        margin: 20px auto;
        box-shadow: var(--shadow-md);
    }

    .edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .edit-header h2 {
        margin: 0;
        color: var(--accent);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: -0.5px;
    }

    .back-link {
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .back-link:hover { color: var(--accent); }

    .info-card {
        background: var(--bg-main);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px dashed var(--border-color);
    }

    .info-card p { margin: 5px 0; font-size: 14px; }
    .info-card strong { color: var(--accent); }

    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .star-rating {
        display: flex;
        gap: 10px;
        font-size: 24px;
        color: #444;
        cursor: pointer;
    }

    .star-rating i.active { color: #f2cc7d; }

    textarea.form-control {
        width: 100%;
        min-height: 120px;
        resize: vertical;
        padding: 15px;
        background: var(--bg-main);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 10px;
        outline: none;
        transition: 0.3s;
    }

    textarea.form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(197, 160, 45, 0.1); }

    .btn-save {
        background: var(--accent);
        color: #000;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        text-transform: uppercase;
        width: 100%;
        margin-top: 10px;
    }

    .btn-save:hover { background: var(--accent-hover); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(223, 209, 85, 0.3); }

</style>

<div class="edit-container">
    <div class="edit-header">
        <h2><i class="fa fa-pencil"></i> Editar Avaliação</h2>
        <a href="/backend/avaliacao/listar" class="back-link"><i class="fa fa-arrow-left"></i> Voltar</a>
    </div>

    <div class="info-card">
        <p><strong>Produto:</strong> <?= htmlspecialchars($avaliacao['nome_produto']) ?></p>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($avaliacao['nome_cliente']) ?></p>
        <p><strong>Data Original:</strong> <?= date('d/m/Y H:i', strtotime($avaliacao['data_avaliacao_avaliacoes'] ?? $avaliacao['criado_em'])) ?></p>
    </div>

    <form action="/backend/avaliacao/atualizar/<?= $avaliacao['id_avaliacoes'] ?>" method="POST">
        <div class="form-group">
            <label>Nota (1 a 5)</label>
            <div class="star-rating" id="starRating">
                <?php for($i=1; $i<=5; $i++): ?>
                    <i class="fa fa-star <?= $i <= $avaliacao['nota_avaliacoes'] ? 'active' : '' ?>" data-value="<?= $i ?>"></i>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="nota" id="notaInput" value="<?= $avaliacao['nota_avaliacoes'] ?>">
        </div>

        <div class="form-group">
            <label>Comentário</label>
            <textarea name="comentario" class="form-control" required><?= htmlspecialchars($avaliacao['comentario_avaliacoes']) ?></textarea>
        </div>

        <button type="submit" class="btn-save"><i class="fa fa-save"></i> Atualizar Avaliação</button>
    </form>
</div>

<script>
    const stars = document.querySelectorAll('#starRating i');
    const input = document.getElementById('notaInput');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const val = star.getAttribute('data-value');
            input.value = val;
            
            stars.forEach(s => {
                if(s.getAttribute('data-value') <= val) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });
</script>
