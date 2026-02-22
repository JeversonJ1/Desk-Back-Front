<div class="page-wrapper">

    <h3 class="page-title"><i class="fa fa-pencil" style="color: #f2cc7d;"></i> Editando Produto: <?= htmlspecialchars($produtos['nome_produtos']); ?></h3>
    <p class="page-subtitle"><i class="fa fa-info-circle"></i> Altere os detalhes do produto e faça o upload da imagem se desejar atualizar.</p>

    <form action="/backend/produtos/atualizar" method="post" enctype="multipart/form-data" class="form-card-modern">
        
        <input type="hidden" name="id_produto" value="<?= $produtos['id_produto']; ?>">

        <div class="form-content">
            <div class="form-inputs">
                <div class="form-group">
                    <label for="nome_produtos">NOME DO PRODUTO:</label>
                    <input type="text" id="nome_produtos" name="nome_produtos" value="<?= htmlspecialchars($produtos['nome_produtos']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="descricao_produtos">DESCRIÇÃO DETALHADA:</label>
                    <textarea id="descricao_produtos" name="descricao_produtos" rows="5" required><?= htmlspecialchars($produtos['descricao_produtos']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="preco_produtos">PREÇO (R$):</label>
                        <input type="number" step="0.01" id="preco_produtos" name="preco_produtos" value="<?= $produtos['preco_produtos']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="estoque_produtos">ESTOQUE:</label>
                        <input type="number" id="estoque_produtos" name="estoque_produtos" value="<?= $produtos['estoque_produtos']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="id_categoria">ID DA CATEGORIA:</label>
                    <input type="number" id="id_categoria" name="id_categoria" value="<?= $produtos['id_categoria']; ?>" required>
                </div>

                <!-- Cores -->
                <div class="form-group">
                    <label>CORES:</label>
                    <div id="cores-container">
                        <?php if (!empty($cores)): ?>
                            <?php foreach ($cores as $cor): ?>
                                <div class="variation-row">
                                    <input type="text" name="cores[]" value="<?= htmlspecialchars($cor['cor_cores']); ?>" placeholder="Ex: Preto" class="flex-1">
                                    <input type="number" name="quantidade_cores[]" value="<?= $cor['quantidade_cores']; ?>" placeholder="Qtd" style="width: 80px;">
                                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="variation-row">
                                <input type="text" name="cores[]" placeholder="Ex: Preto" class="flex-1">
                                <input type="number" name="quantidade_cores[]" placeholder="Qtd" style="width: 80px;">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn-add" onclick="addCor()"><i class="fa fa-plus"></i> Adicionar Cor</button>
                </div>

                <!-- Tamanhos -->
                <div class="form-group">
                    <label>TAMANHOS:</label>
                    <div id="tamanhos-container">
                        <?php if (!empty($tamanhos)): ?>
                            <?php foreach ($tamanhos as $tamanho): ?>
                                <div class="variation-row">
                                    <input type="text" name="tamanhos[]" value="<?= htmlspecialchars($tamanho['tamanho_tamanhos']); ?>" placeholder="Ex: M" class="flex-1">
                                    <input type="number" name="quantidade_tamanhos[]" value="<?= $tamanho['quantidade_tamanhos']; ?>" placeholder="Qtd" style="width: 80px;">
                                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="variation-row">
                                <input type="text" name="tamanhos[]" placeholder="Ex: M" class="flex-1">
                                <input type="number" name="quantidade_tamanhos[]" placeholder="Qtd" style="width: 80px;">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn-add" onclick="addTamanho()"><i class="fa fa-plus"></i> Adicionar Tamanho</button>
                </div>
            </div>

            <div class="form-upload-section">
                <div class="image-preview-container" onclick="document.getElementById('imagem_produtos').click();">
                    <?php 
                        $fotoAtual = !empty($produtos['imagem_produtos']) ? $produtos['imagem_produtos'] : '/img/placeholder-produto.png';
                    ?>
                    <img id="imgPreview" src="<?= $fotoAtual; ?>" alt="Preview">
                    <div class="upload-overlay">
                        <i class="fa fa-camera"></i>
                        <span>Alterar Foto</span>
                    </div>
                </div>
                <input type="file" id="imagem_produtos" name="imagem_produtos" hidden accept="image/*" onchange="previewImage(this);">
                <small class="upload-tip">JPG, PNG ou WebP</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save-modern">
                <i class="fa fa-save"></i> SALVAR ALTERAÇÕES
            </button>
            <a href="/backend/produtos/listar" class="btn-back-link">
                <i class="fa fa-arrow-left"></i> Voltar para a lista
            </a>
        </div>

    </form>

</div>

<script>
// Função para mostrar a foto nova assim que o usuário seleciona o arquivo
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function addCor() {
    const container = document.getElementById('cores-container');
    const row = document.createElement('div');
    row.className = 'variation-row';
    row.innerHTML = `
        <input type="text" name="cores[]" placeholder="Ex: Preto" class="flex-1">
        <input type="number" name="quantidade_cores[]" placeholder="Qtd" style="width: 80px;">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
    `;
    container.appendChild(row);
}

function addTamanho() {
    const container = document.getElementById('tamanhos-container');
    const row = document.createElement('div');
    row.className = 'variation-row';
    row.innerHTML = `
        <input type="text" name="tamanhos[]" placeholder="Ex: M" class="flex-1">
        <input type="number" name="quantidade_tamanhos[]" placeholder="Qtd" style="width: 80px;">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
    `;
    container.appendChild(row);
}
</script>

<style>
/* Layout Base */
.page-wrapper {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.page-title {
    color: #fff;
    font-size: 24px;
    margin-bottom: 5px;
    text-align: left;
    width: 100%;
    max-width: 900px;
}

.page-subtitle {
    color: #888;
    font-size: 14px;
    margin-bottom: 25px;
    width: 100%;
    max-width: 900px;
}

/* Card do Formulário */
.form-card-modern {
    background: #111;
    border: 1px solid #222;
    padding: 30px;
    border-radius: 15px;
    width: 100%;
    max-width: 900px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.form-content {
    display: grid;
    grid-template-columns: 1fr 300px; /* Coluna dados e Coluna Foto */
    gap: 30px;
}

/* Inputs */
.form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: #e2c93e; /* Dourado Koketsu */
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 8px;
}

.form-group input, .form-group textarea {
    background: #1a1a1a;
    border: 1px solid #333;
    color: #fff;
    padding: 12px;
    border-radius: 8px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* Variações */
.variation-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}

.btn-add {
    background: transparent;
    color: #f2cc7d;
    border: 1px dashed #f2cc7d;
    padding: 8px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    width: 100%;
    margin-top: 5px;
    transition: 0.3s;
}

.btn-add:hover {
    background: rgba(242, 204, 125, 0.1);
}

.btn-remove {
    background: #ff4d4d;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
}

/* Seção de Upload */
.image-preview-container {
    width: 100%;
    height: 250px;
    background: #1a1a1a;
    border: 2px dashed #333;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    transition: 0.3s;
}

.image-preview-container:hover {
    border-color: #e2c93e;
}

.image-preview-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: 0.3s;
}

.image-preview-container:hover .upload-overlay {
    opacity: 1;
}

.upload-overlay i { color: #fff; font-size: 30px; margin-bottom: 10px; }
.upload-overlay span { color: #fff; font-size: 14px; }
.upload-tip { color: #666; display: block; text-align: center; margin-top: 10px; }

/* Botões */
.form-actions {
    margin-top: 30px;
    border-top: 1px solid #222;
    padding-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-save-modern {
    background: #dfd155;
    color: #000;
    border: none;
    padding: 15px 40px;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.btn-save-modern:hover {
    background: #fff;
    transform: translateY(-2px);
}

.btn-back-link {
    color: #888;
    text-decoration: none;
    font-size: 14px;
}

.btn-back-link:hover { color: #fff; }

/* Responsivo */
@media (max-width: 768px) {
    .form-content { grid-template-columns: 1fr; }
    .form-upload-section { order: -1; } /* Foto em cima no celular */
}
</style>