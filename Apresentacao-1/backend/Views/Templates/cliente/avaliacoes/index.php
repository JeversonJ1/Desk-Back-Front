<?php
$usuarioId = $_SESSION['usuario_id'] ?? 0;
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Cliente';
?>
<div class="avaliacoes-container">
    <header class="avaliacoes-header">
        <div class="header-left">
            <a href="/backend/cliente/dashboard" class="back-btn"><i class="fa fa-arrow-left"></i> Voltar ao Perfil</a>
            <h1><i class="fa fa-star"></i> Suas Avaliações</h1>
        </div>
        
        <?php if (!empty($produtos)): ?>
            <button class="btn-nova-avaliacao" onclick="abrirModal()"><i class="fa fa-plus"></i> Nova Avaliação</button>
        <?php endif; ?>
    </header>

    <p class="avaliacoes-descricao">Veja todas as avaliações que você fez para os itens do acervo</p>

    <div class="avaliacoes-badge">
        <i class="fa fa-comment"></i> <?= $totalAvaliacoes ?? 0 ?> avaliação(ões) realizada(s)
    </div>

    <div class="avaliacoes-lista">
        <?php if (empty($avaliacoes)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fa fa-star-o"></i></div>
                <h2>Nenhuma avaliação encontrada</h2>
                
                <?php if (!empty($produtos)): ?>
                    <p>Você tem produtos que podem ser avaliados!</p>
                    <button class="btn-primeira-avaliacao" onclick="abrirModal()">Fazer minha primeira avaliação</button>
                <?php else: ?>
                    <p>Você ainda não possui produtos elegíveis para avaliação.</p>
                    <a href="/" class="btn-primeira-avaliacao">Explorar a loja</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($avaliacoes as $avaliacao): ?>
                <div class="avaliacao-card">
                    <div class="avaliacao-card-body">
                        <div class="avaliacao-produto-img">
                            <?php
                                // Buscar imagem do produto
                                $imgSrc = '/img/logoperf.jpg';
                                if (!empty($avaliacao['imagem_produtos'])) {
                                    $imgSrc = '/backend/upload/' . $avaliacao['imagem_produtos'];
                                }
                            ?>
                            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Produto" onerror="this.src='/img/logoperf.jpg';">
                        </div>
                        <div class="avaliacao-info">
                            <h3 class="avaliacao-produto-nome"><?= htmlspecialchars($avaliacao['nome_produto'] ?? 'Produto') ?></h3>
                            <p class="avaliacao-data">
                                <i class="fa fa-calendar"></i> 
                                Avaliado em <?= date('d/m/Y', strtotime($avaliacao['data_avaliacao_avaliacoes'] ?? $avaliacao['criado_em'])) ?>
                            </p>
                            <div class="avaliacao-estrelas">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa fa-star <?= $i <= $avaliacao['nota_avaliacoes'] ? 'star-filled' : 'star-empty' ?>"></i>
                                <?php endfor; ?>
                                <span class="nota-texto"><?= $avaliacao['nota_avaliacoes'] ?>/5</span>
                            </div>
                            <?php if (!empty($avaliacao['comentario_avaliacoes'])): ?>
                                <div class="avaliacao-comentario">
                                    <?= htmlspecialchars($avaliacao['comentario_avaliacoes']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="avaliacao-card-footer">
                        <button class="btn-editar" onclick="abrirModalEditar(<?= $avaliacao['id_avaliacoes'] ?>, <?= $avaliacao['id_produto'] ?>, <?= $avaliacao['nota_avaliacoes'] ?>, '<?= addslashes(htmlspecialchars($avaliacao['comentario_avaliacoes'] ?? '')) ?>')">
                            <i class="fa fa-pencil"></i> Editar Avaliação
                        </button>
                        <form action="/backend/cliente/avaliacao/excluir/<?= $avaliacao['id_avaliacoes'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta avaliação?');">
                            <button type="submit" class="btn-excluir">
                                <i class="fa fa-trash"></i> Excluir Avaliação
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Nova Avaliação -->
<div class="modal-overlay" id="modalAvaliacao" style="display:none;">
    <div class="modal-content">
        <button class="modal-close" onclick="fecharModal()">&times;</button>
        <div class="modal-header-info">
            <div class="modal-icon"><i class="fa fa-star"></i></div>
            <div>
                <h3 id="modalTitulo">Nova Avaliação</h3>
                <p>Avalie este produto</p>
            </div>
        </div>

        <form id="formAvaliacao" action="/backend/cliente/avaliacao/salvar" method="POST">
            <input type="hidden" name="id_avaliacao" id="inputIdAvaliacao" value="">

            <div class="form-group" id="grupoProduto">
                <label>Produto</label>
                <select name="id_produto" id="selectProduto" required>
                    <option value="">Selecione um produto...</option>
                    <?php if (!empty($produtos)): ?>
                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto['id_produto'] ?>"><?= htmlspecialchars($produto['nome_produtos']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Como você avalia este item?</label>
                <div class="star-rating" id="starRating">
                    <i class="fa fa-star star-input" data-value="1" onclick="setRating(1)"></i>
                    <i class="fa fa-star star-input" data-value="2" onclick="setRating(2)"></i>
                    <i class="fa fa-star star-input" data-value="3" onclick="setRating(3)"></i>
                    <i class="fa fa-star star-input" data-value="4" onclick="setRating(4)"></i>
                    <i class="fa fa-star star-input" data-value="5" onclick="setRating(5)"></i>
                </div>
                <input type="hidden" name="nota" id="inputNota" value="0" required>
            </div>

            <div class="form-group">
                <label>Comentário (opcional)</label>
                <textarea name="comentario" id="inputComentario" placeholder="Conte sua experiência com este produto..." maxlength="500" rows="4"></textarea>
                <span class="char-count"><span id="charCount">0</span>/500 caracteres</span>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="fecharModal()">Cancelar</button>
                <button type="button" class="btn-enviar" onclick="validarESubmeter()">Enviar avaliação</button>
            </div>
        </form>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Montserrat:wght@300;400;600&display=swap');

    :root {
        --k-gold: #f2cc7d;
        --k-gold-dark: #b8860b;
        --bg-body-custom: var(--bg-main);
        --bg-card-custom: var(--bg-card);
        --text-color-custom: var(--text-main);
        --border-custom: var(--border-color);
        --muted-custom: var(--text-muted);
    }

    .avaliacoes-container {
        font-family: 'Montserrat', sans-serif;
        color: var(--text-color-custom);
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background-color: var(--bg-body-custom);
    }

    /* Header */
    .avaliacoes-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 25px;
        background: var(--bg-card-custom);
        border-radius: 12px;
        border: 1px solid var(--border-custom);
        border-top: 4px solid var(--k-gold);
        box-shadow: var(--shadow-md);
        margin-bottom: 15px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .back-btn {
        color: var(--k-gold);
        text-decoration: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(242, 204, 125, 0.1);
        border-radius: 8px;
        transition: 0.3s;
        font-size: 0.85rem;
    }
    .back-btn:hover { background: var(--k-gold); color: #000; }

    .avaliacoes-header h1 {
        margin: 0;
        color: var(--text-color-custom);
        font-family: 'Oswald', sans-serif;
        font-size: 1.5rem;
        letter-spacing: 1px;
    }
    .avaliacoes-header h1 i { color: var(--k-gold); }

    .btn-nova-avaliacao {
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        color: #000;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-nova-avaliacao:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(242, 204, 125, 0.4); }

    .avaliacoes-descricao {
        color: var(--muted-custom);
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .avaliacoes-badge {
        display: inline-block;
        padding: 8px 18px;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        color: #000;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.8rem;
        margin-bottom: 25px;
    }

    /* Cards */
    .avaliacoes-lista { display: grid; gap: 20px; }

    .avaliacao-card {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        border-radius: 12px;
        padding: 25px;
        box-shadow: var(--shadow-sm);
        transition: 0.3s;
    }
    .avaliacao-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: rgba(242, 204, 125, 0.3);
    }

    .avaliacao-card-body {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .avaliacao-produto-img {
        width: 100px;
        height: 120px;
        flex-shrink: 0;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid var(--border-custom);
    }
    .avaliacao-produto-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avaliacao-info { flex: 1; }

    .avaliacao-produto-nome {
        margin: 0 0 5px 0;
        font-family: 'Oswald', sans-serif;
        font-size: 1.1rem;
        color: var(--text-color-custom);
    }

    .avaliacao-data {
        color: var(--muted-custom);
        font-size: 0.8rem;
        margin: 0 0 10px 0;
    }
    .avaliacao-data i { margin-right: 5px; }

    .avaliacao-estrelas { margin-bottom: 10px; }
    .avaliacao-estrelas .star-filled { color: #f39c12; font-size: 1rem; }
    .avaliacao-estrelas .star-empty { color: #555; font-size: 1rem; }
    .nota-texto { color: var(--muted-custom); font-size: 0.85rem; margin-left: 8px; }

    .avaliacao-comentario {
        padding: 12px 18px;
        background: rgba(125, 125, 125, 0.08);
        border-radius: 8px;
        border-left: 3px solid var(--k-gold);
        font-size: 0.9rem;
        color: var(--text-color-custom);
        font-style: italic;
        margin-top: 8px;
    }

    .avaliacao-card-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border-custom);
    }

    .btn-editar {
        padding: 8px 18px;
        border: 1px solid var(--k-gold);
        background: transparent;
        color: var(--k-gold);
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-editar:hover { background: var(--k-gold); color: #000; }

    .btn-excluir {
        padding: 8px 18px;
        border: 1px solid #e74c3c;
        background: transparent;
        color: #e74c3c;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-excluir:hover { background: #e74c3c; color: #fff; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-icon { font-size: 3rem; color: var(--k-gold); margin-bottom: 15px; opacity: 0.6; }
    .empty-state h2 { color: var(--text-color-custom); font-family: 'Oswald', sans-serif; margin-bottom: 10px; }
    .empty-state p { color: var(--muted-custom); }
    .btn-primeira-avaliacao {
        display: inline-block;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        color: #000;
        padding: 12px 30px;
        border: none;
        border-radius: 25px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 20px;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn-primeira-avaliacao:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(242, 204, 125, 0.4); }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.65);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        border-radius: 16px;
        padding: 30px;
        width: 90%;
        max-width: 500px;
        position: relative;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        animation: modalIn 0.3s ease-out;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        background: none;
        border: none;
        color: var(--muted-custom);
        font-size: 1.5rem;
        cursor: pointer;
        transition: 0.3s;
    }
    .modal-close:hover { color: #e74c3c; }

    .modal-header-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .modal-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        color: #000;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .modal-header-info h3 { margin: 0; font-family: 'Oswald', sans-serif; color: var(--text-color-custom); }
    .modal-header-info p { margin: 0; font-size: 0.8rem; color: var(--muted-custom); }

    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 8px;
        color: var(--text-color-custom);
    }

    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-custom) !important;
        background: var(--bg-body-custom) !important;
        color: var(--text-color-custom) !important;
        border-radius: 8px;
        font-size: 0.9rem;
        font-family: 'Montserrat', sans-serif;
        transition: border-color 0.3s;
        box-sizing: border-box;
    }
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--k-gold) !important;
    }

    .form-group textarea { resize: vertical; min-height: 100px; }
    .char-count { font-size: 0.75rem; color: var(--muted-custom); text-align: right; display: block; margin-top: 5px; }

    /* Star Rating Input */
    .star-rating {
        display: flex;
        gap: 8px;
        font-size: 1.8rem;
    }
    .star-input {
        color: #444;
        cursor: pointer;
        transition: 0.2s;
    }
    .star-input:hover,
    .star-input.active { color: #f39c12; transform: scale(1.2); }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
    }

    .btn-cancelar {
        padding: 10px 25px;
        border: 1px solid var(--border-custom);
        background: transparent;
        color: var(--text-color-custom);
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-cancelar:hover { background: rgba(125, 125, 125, 0.1); }

    .btn-enviar {
        padding: 10px 25px;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        color: #000;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-enviar:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(242, 204, 125, 0.4); }

    /* Responsive */
    @media (max-width: 768px) {
        .avaliacoes-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .avaliacao-card-body { flex-direction: column; }
        .avaliacao-produto-img { width: 80px; height: 100px; }
        .avaliacao-card-footer { flex-direction: column; }
        .btn-editar, .btn-excluir { width: 100%; text-align: center; }
    }
</style>

<script>
let notaAtual = 0;
let modoEditar = false;

function setRating(valor) {
    notaAtual = valor;
    document.getElementById('inputNota').value = valor;
    const stars = document.querySelectorAll('.star-input');
    stars.forEach((star, i) => {
        if (i < valor) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

function abrirModal() {
    modoEditar = false;
    document.getElementById('modalTitulo').textContent = 'Nova Avaliação';
    document.getElementById('formAvaliacao').action = '/backend/cliente/avaliacao/salvar';
    document.getElementById('selectProduto').value = '';
    document.getElementById('inputComentario').value = '';
    document.getElementById('charCount').textContent = '0';
    document.getElementById('grupoProduto').style.display = 'block';
    setRating(0);
    document.getElementById('modalAvaliacao').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function abrirModalEditar(id, idProduto, nota, comentario) {
    modoEditar = true;
    document.getElementById('modalTitulo').textContent = 'Editar Avaliação';
    document.getElementById('formAvaliacao').action = '/backend/cliente/avaliacao/atualizar/' + id;
    document.getElementById('inputIdAvaliacao').value = id;
    document.getElementById('inputComentario').value = comentario;
    document.getElementById('charCount').textContent = comentario.length;
    
    // Configurar produto (mesmo que escondido, deve ter valor para não falhar o required se houver)
    const selectProduto = document.getElementById('selectProduto');
    selectProduto.value = idProduto;
    selectProduto.required = false; // Desativa required no modo edição pois o campo some
    
    document.getElementById('grupoProduto').style.display = 'none';
    setRating(nota);
    document.getElementById('modalAvaliacao').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function validarESubmeter() {
    const nota = document.getElementById('inputNota').value;
    if (nota == 0) {
        alert('Por favor, selecione uma nota de 1 a 5 estrelas.');
        return;
    }
    
    if (!modoEditar) {
        const produto = document.getElementById('selectProduto').value;
        if (!produto) {
            alert('Por favor, selecione um produto.');
            return;
        }
    }
    
    document.getElementById('formAvaliacao').submit();
}

function fecharModal() {
    document.getElementById('modalAvaliacao').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fechar ao clicar fora
document.addEventListener('click', function(e) {
    if (e.target.id === 'modalAvaliacao') fecharModal();
});

// Contador de caracteres
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('inputComentario');
    if (textarea) {
        textarea.addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });
    }
});

// Fechar com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') fecharModal();
});
</script>
