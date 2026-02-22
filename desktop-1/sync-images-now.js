const { getDatabase, saveDatabase } = require('./database/sqlite-connection');
const fs = require('fs');
const path = require('path');

// Função para salvar imagem Base64
function saveBase64Image(base64String, prefix = 'img') {
    if (!base64String || typeof base64String !== 'string') return null;

    // Se já for um path ou URL, retorna como está
    if (!base64String.startsWith('data:image')) {
        return base64String;
    }

    try {
        // Extrair extensão e dados
        const matches = base64String.match(/^data:image\/([a-zA-Z0-9]+);base64,(.+)$/);
        if (!matches) return null;

        const ext = matches[1] === 'jpeg' ? 'jpg' : matches[1];
        const data = matches[2];

        // Diretório de armazenamento
        const storageDir = path.join(__dirname, 'storage/images');
        if (!fs.existsSync(storageDir)) {
            fs.mkdirSync(storageDir, { recursive: true });
        }

        // Nome do arquivo
        const filename = `${prefix}_${Date.now()}_${Math.floor(Math.random() * 1000)}.${ext}`;
        const filePath = path.join(storageDir, filename);

        // Salvar arquivo
        fs.writeFileSync(filePath, data, 'base64');

        // Retorna caminho absoluto com protocolo file:// para o Electron
        return `file://${filePath.replace(/\\/g, '/')}`;
    } catch (error) {
        console.error('Erro ao salvar imagem base64:', error.message);
        return null;
    }
}

// Buscar produtos da API e sincronizar
async function syncImagesFromAPI() {
    try {
        console.log('🔄 Buscando produtos da API...\n');

        const response = await fetch('http://localhost:8000/backend/api/produtos');
        const json = await response.json();

        if (json.status !== 'success' || !Array.isArray(json.data)) {
            console.error('❌ Resposta inválida da API');
            return;
        }

        console.log(`✅ API retornou ${json.data.length} produtos\n`);

        const db = await getDatabase();
        let processados = 0;
        let salvos = 0;

        for (const item of json.data) {
            const id = item.id_produto || item.id;
            const nome = item.nome_produtos || item.nome;

            console.log(`\n📦 Produto ${id}: "${nome}"`);

            let imagemProcessada = null;

            // Prioridade 1: Base64 em caminho_imagem
            if (item.caminho_imagem && item.caminho_imagem.startsWith('data:image')) {
                console.log('  🖼️  Encontrou Base64 em caminho_imagem');
                const localPath = saveBase64Image(item.caminho_imagem, `prod_${id}`);
                if (localPath) {
                    imagemProcessada = localPath;
                    console.log(`  ✅ Salvo como: ${localPath.substring(0, 80)}...`);
                    salvos++;
                }
            }
            // Prioridade 2: Base64 em imagem_produtos
            else if (item.imagem_produtos && item.imagem_produtos.startsWith('data:image')) {
                console.log('  🖼️  Encontrou Base64 em imagem_produtos');
                const localPath = saveBase64Image(item.imagem_produtos, `prod_${id}`);
                if (localPath) {
                    imagemProcessada = localPath;
                    console.log(`  ✅ Salvo como: ${localPath.substring(0, 80)}...`);
                    salvos++;
                }
            }
            // Prioridade 3: Path relativo (sem download por enquanto)
            else if (item.imagem_produtos) {
                console.log(`  ⚠️  Path relativo: ${item.imagem_produtos} (não processado)`);
            } else {
                console.log('  ℹ️  Sem imagem');
            }

            // Atualizar banco de dados se tiver imagem processada
            if (imagemProcessada) {
                // Verificar se produto já existe
                const existing = db.exec("SELECT id FROM tbl_produtos WHERE id_produto_api = ?", [id]);

                if (existing.length > 0 && existing[0].values.length > 0) {
                    // Update
                    const localId = existing[0].values[0][0];
                    db.run("UPDATE tbl_produtos SET imagem = ? WHERE id = ?", [imagemProcessada, localId]);
                    console.log(`  💾 Atualizado no banco (Local ID: ${localId})`);
                } else {
                    console.log(`  ⚠️  Produto não existe no banco local`);
                }

                processados++;
            }
        }

        saveDatabase();

        console.log('\n' + '='.repeat(60));
        console.log(`✅ Sincronização concluída!`);
        console.log(`   ${processados} produtos processados`);
        console.log(`   ${salvos} imagens salvas em storage/images`);
        console.log('='.repeat(60));

    } catch (error) {
        console.error('❌ Erro:', error.message);
    }
}

console.log('🚀 Iniciando sincronização de imagens...\n');
syncImagesFromAPI();
