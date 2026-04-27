<div class="page-wrapper">
    <h3 class="page-title">
        <i class="fa fa-tag" style="color: #f2cc7d;"></i> Novo Produto
    </h3>
    
    <header class="header-breadcrumb">
        <h5><b><i class="fa fa-info-circle"></i> Preencha os detalhes do produto e faça o upload da imagem.</b></h5>
    </header>

    <form action="/backend/produtos/salvar" method="post" enctype="multipart/form-data" class="form-card">
        
        <div class="form-grid">
            <div class="form-column">
                <div class="form-group">
                    <label for="nome_produtos">Nome do Produto:</label>
                    <input type="text" id="nome_produtos" name="nome_produtos" placeholder="Ex: Camiseta Oversized" required>
                </div>

                <div class="form-group">
                    <label for="descricao_produtos">Descrição Detalhada:</label>
                    <textarea id="descricao_produtos" name="descricao_produtos" rows="4" placeholder="Descreva as características do produto..." required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="preco_produtos">Preço (R$):</label>
                        <input type="number" step="0.01" id="preco_produtos" name="preco_produtos" placeholder="0.00" required>
                    </div>
                    <div class="form-group flex-1">
                        <label for="estoque_produtos">Estoque:</label>
                        <input type="number" id="estoque_produtos" name="estoque_produtos" placeholder="Qtd" required>
                    </div>
                </div>

                <div class="form-group">

                    <label for="id_categoria">Categoria:</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">Selecione uma Categoria...</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?= $c['id_categorias'] ?>"><?= htmlspecialchars($c['nome_categorias']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-column photo-upload-column">
                <label class="upload-area" for="imagem_produtos">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <i class="fa fa-cloud-upload"></i>
                        <span>Clique para selecionar a foto</span>
                        <small>JPG, PNG ou WebP</small>
                    </div>
                    <img id="previewCompressed" class="image-preview-main" style="display:none;" />
                    <input id="imagem_produtos" name="imagem_produtos" type="file" accept="image/*" required>
                </label>
                
                <div id="imageMeta" class="image-meta" style="display:none;">
                    <div class="meta-item">
                        <span id="infoCompressed"></span>
                    </div>
                </div>
                
                <div class="tech-previews">
                    <div class="preview-box">
                        <small>Original</small>
                        <img id="previewOriginal" />
                        <p id="infoOriginal"></p>
                    </div>
                </div>

                <!-- Galeria Adicional -->
                <div class="form-group" style="margin-top:20px;">
                    <label>Galeria de Mídias Extras (Imagens e Vídeos MP4):</label>
                    <label class="upload-area" for="galeria_produtos" style="height:120px; border-style:dashed;">
                        <div class="upload-placeholder">
                            <i class="fa fa-images"></i>
                            <span>Clique para adicionar mais fotos ou vídeos</span>
                        </div>
                        <input id="galeria_produtos" name="galeria_produtos[]" type="file" accept="image/*,video/mp4" multiple>
                    </label>
                    <div id="galeria-preview" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;"></div>
                </div>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-column">
                <div class="form-group">
                    <label>Variações de Cores:</label>
                    <div id="cores-container">
                        <div class="variation-row">
                            <input type="text" name="cores[]" placeholder="Ex: Preto" class="flex-1">
                            <input type="number" name="quantidade_cores[]" placeholder="Qtd" style="width: 80px;">
                            <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn-add" onclick="addCor()"><i class="fa fa-plus"></i> Adicionar Cor</button>
                </div>
            </div>

            <div class="form-column">
                <div class="form-group">
                    <label>Variações de Tamanhos:</label>
                    <div id="tamanhos-container">
                        <div class="variation-row">
                            <input type="text" name="tamanhos[]" placeholder="Ex: M" class="flex-1">
                            <input type="number" name="quantidade_tamanhos[]" placeholder="Qtd" style="width: 80px;">
                            <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn-add" onclick="addTamanho()"><i class="fa fa-plus"></i> Adicionar Tamanho</button>
                </div>
            </div>
        </div>

        <div class="actions-container">
            <button type="submit" class="btn-save">
                <i class="fa fa-save"></i> Salvar Produto
            </button>
            
            <a href="/backend/produtos/listar" class="btn-cancelar">
                <i class="fa fa-arrow-left"></i> Voltar para a lista
            </a>
        </div>
    </form>
</div>

<style>
    /* Estilos para as variações */
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

    /* Base e Alinhamento Centralizado como no Edit Usuário */
    .page-wrapper {
        padding: 40px 20px;
        width: 100%;
        min-height: 100vh;
        background-color: var(--bg-main);
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-sizing: border-box;
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        text-transform: uppercase;
        margin-bottom: 5px;
        text-align: center;
        width: 100%;
        max-width: 850px;
    }

    .header-breadcrumb {
        color: var(--text-muted);
        margin-bottom: 25px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 15px;
        text-align: center;
        width: 100%;
        max-width: 850px;
    }

    /* Card Expandido para acomodar duas colunas */
    .form-card {
        background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
        padding: 32px;
        border-radius: 16px;
        width: 100%;
        max-width: 850px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        border: 2px solid var(--border-color);
    }

    .form-grid {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .form-column { flex: 1; min-width: 300px; }

    /* Estilo dos Inputs */
    .form-group { display: flex; flex-direction: column; margin-bottom: 18px; }
    .form-row { display: flex; gap: 15px; }
    .flex-1 { flex: 1; }

    .form-group label {
        margin-bottom: 8px;
        font-size: 11px;
        font-weight: 800;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-group input, .form-group textarea, .form-group select {
        background: var(--input-bg);
        border: 2px solid var(--border-color);
        padding: 12px 14px;
        border-radius: var(--radius-md);
        color: var(--text-main);
        font-size: 14px;
        font-family: Arial, sans-serif;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }

    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-glow);
    }

    /* Área de Upload Estilizada */
    .upload-area {
        border: 2px dashed var(--border-hover);
        border-radius: 12px;
        height: 250px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: .3s;
        background: var(--input-bg);
    }

    .upload-area:hover { border-color: var(--accent); background: #1a1a1a; }
    .upload-area input { display: none; }

    .upload-placeholder { text-align: center; color: #666; }
    .upload-placeholder i { font-size: 40px; margin-bottom: 10px; display: block; }
    .upload-placeholder span { display: block; font-weight: bold; color: #aaa; }

    .image-preview-main {
        width: 100%;
        height: 100%;
        object-fit: contain;
        position: absolute;
        top: 0; left: 0;
    }

    .image-meta {
        margin-top: 15px;
        padding: 12px;
        background: var(--bg-card-flat);
        border-radius: var(--radius-sm);
        font-size: 12px;
        color: var(--text-muted);
        border-left: 3px solid var(--accent);
    }

    /* Esconde os detalhes técnicos para manter a beleza, mas mantém funcional */
    .tech-previews { display: none; }

    /* Botões */
    .actions-container {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-save {
        background: linear-gradient(135deg, #F2C84B, #d4a800);
        color: #000;
        padding: 15px;
        border: none;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 4px 12px rgba(242,200,75,.3);
    }

    .btn-save:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(242,200,75,.45); }

    .btn-cancelar {
        text-align: center;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        padding: 10px;
    }
</style>

<script>
// Mantive sua lógica de compressão funcional, apenas integrei aos novos IDs
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById('imagem_produtos');
    const infoCompressed = document.getElementById('infoCompressed');
    const previewImg = document.getElementById('previewCompressed');
    const placeholder = document.getElementById('uploadPlaceholder');
    const metaBox = document.getElementById('imageMeta');

    input.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        try {
            const result = await compressImage(file);
            
            // UI Updates
            placeholder.style.display = 'none';
            previewImg.style.display = 'block';
            previewImg.src = URL.createObjectURL(result.file);
            metaBox.style.display = 'block';

            const colorBox = `<div style="display:inline-block; width:12px; height:12px; background-color:${result.meta.dominantColor}; border:1px solid #555; vertical-align:middle; margin-left:5px;"></div>`;

            infoCompressed.innerHTML = `
                <strong>IMAGEM OTIMIZADA:</strong> ${result.meta.finalWidth}x${result.meta.finalHeight}px | 
                ${(result.meta.compressedSize / 1024).toFixed(1)} KB | 
                Cor: ${result.meta.dominantColor} ${colorBox}
            `;

            // Substituição do input para o envio do WebP
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(result.file);
            input.files = dataTransfer.files;

        } catch (err) {
            console.error(err);
            alert("Erro ao processar imagem.");
        }
    });

    // Lógica da Galeria Adicional
    const galeriaInput = document.getElementById('galeria_produtos');
    const galeriaPreview = document.getElementById('galeria-preview');
    let galeriaArquivos = []; // manter o controle de arquivos da galeria

    galeriaInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        if (!files.length) return;

        files.forEach(file => {
            galeriaArquivos.push(file);
            const isVideo = file.type.startsWith('video');
            const url = URL.createObjectURL(file);
            
            const div = document.createElement('div');
            div.className = 'galeria-item';
            div.style.cssText = 'position:relative; width:80px; height:80px; border-radius:8px; overflow:hidden; border:1px solid #555;';

            if (isVideo) {
                div.innerHTML = `<video src="${url}" style="width:100%; height:100%; object-fit:cover;" muted></video>
                                 <i class="fa fa-play" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:#fff; text-shadow:0 0 5px #000;"></i>`;
            } else {
                div.innerHTML = `<img src="${url}" style="width:100%; height:100%; object-fit:cover;">`;
            }

            const removeBtn = document.createElement('button');
            removeBtn.innerHTML = '<i class="fa fa-times"></i>';
            removeBtn.style.cssText = 'position:absolute; top:2px; right:2px; background:rgba(255,0,0,0.8); color:white; border:none; border-radius:50%; width:20px; height:20px; font-size:10px; cursor:pointer;';
            removeBtn.onclick = (ev) => {
                ev.preventDefault();
                galeriaArquivos = galeriaArquivos.filter(f => f !== file);
                div.remove();
                atualizarGaleriaInput();
            };

            div.appendChild(removeBtn);
            galeriaPreview.appendChild(div);
        });

        atualizarGaleriaInput();
    });

    function atualizarGaleriaInput() {
        const dt = new DataTransfer();
        galeriaArquivos.forEach(f => dt.items.add(f));
        galeriaInput.files = dt.files;
    }
});

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

// Funções auxiliares (getAverageColor e compressImage) permanecem as mesmas que você forneceu...
// [As funções de compressão que você enviou entram aqui]
async function compressImage(file, quality = 0.8, maxWidth = 1200) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                let width = img.naturalWidth;
                let height = img.naturalHeight;
                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }
                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                // Pegar cor média
                const imageData = ctx.getImageData(0, 0, width, height).data;
                let r=0, g=0, b=0, count=0;
                for (let i=0; i<imageData.length; i+=40) {
                    r+=imageData[i]; g+=imageData[i+1]; b+=imageData[i+2]; count++;
                }
                const dominantColor = "#" + ((1 << 24) + (Math.floor(r/count) << 16) + (Math.floor(g/count) << 8) + Math.floor(b/count)).toString(16).slice(1);

                canvas.toBlob((blob) => {
                    const newFile = new File([blob], file.name.split('.')[0] + ".jpg", { type: "image/jpg" });
                    resolve({ file: newFile, meta: { compressedSize: newFile.size, finalWidth: width, finalHeight: height, dominantColor } });
                }, 'image/jpg', quality);
            };
        };
    });
}
</script>