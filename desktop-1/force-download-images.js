console.log('Forçando download de imagens dos produtos...\n');

const { getDatabase, saveDatabase } = require('./database/sqlite-connection');
const ApiService = require('./electron/services/apiService');
const Logger = require('./electron/services/logger');
const fs = require('fs');
const path = require('path');

async function downloadImage(url, prefix = 'img') {
    try {
        if (!url) return null;
        console.log(`Baixando: ${url}`);

        const response = await fetch(url);
        if (!response.ok) {
            console.warn(`❌ Erro ${response.status} ao baixar: ${url}`);
            return null;
        }

        const buffer = await response.arrayBuffer();
        const data = Buffer.from(buffer);

        let ext = 'jpg';
        const contentType = response.headers.get('content-type');
        if (contentType) {
            if (contentType.includes('jpeg')) ext = 'jpg';
            else if (contentType.includes('png')) ext = 'png';
            else if (contentType.includes('gif')) ext = 'gif';
            else if (contentType.includes('webp')) ext = 'webp';
        }

        const storageDir = path.join(__dirname, 'storage/images');
        if (!fs.existsSync(storageDir)) {
            fs.mkdirSync(storageDir, { recursive: true });
        }

        const filename = `${prefix}_${Date.now()}_${Math.floor(Math.random() * 1000)}.${ext}`;
        const filePath = path.join(storageDir, filename);

        fs.writeFileSync(filePath, data);
        const fileUrl = `file://${filePath.replace(/\\/g, '/')}`;

        console.log(`✅ Salvo em: ${fileUrl}`);
        return fileUrl;
    } catch (error) {
        console.error(`❌ Erro ao baixar ${url}:`, error.message);
        return null;
    }
}

async function forceDownloadImages() {
    try {
        const db = await getDatabase();
        const res = db.exec("SELECT id, id_produto_api, nome, imagem FROM tbl_produtos WHERE deletado = 0");

        if (res.length > 0 && res[0].values) {
            console.log(`Total de produtos: ${res[0].values.length}\n`);

            for (const row of res[0].values) {
                const [id, apiId, nome, imagem] = row;

                console.log(`\n${nome} (Local ID: ${id}, API ID: ${apiId})`);
                console.log(`  Imagem atual: ${imagem}`);

                // Se já tem file://, pular
                if (imagem && imagem.startsWith('file://')) {
                    console.log(`  ✅ Já tem file:// - pulando`);
                    continue;
                }

                // Se for path relativo, tentar baixar
                if (imagem && imagem.includes('produtos/')) {
                    const filename = imagem.replace('produtos/', '');
                    const url = `http://localhost:8000/backend/storage/produtos/${filename}`;

                    console.log(`  Baixando de: ${url}`);
                    const localPath = await downloadImage(url, `prod_${apiId}`);

                    if (localPath) {
                        // Atualizar banco
                        db.run("UPDATE tbl_produtos SET imagem = ? WHERE id = ?", [localPath, id]);
                        saveDatabase();
                        console.log(`  ✅ Atualizado no banco: ${localPath}`);
                    }
                } else if (!imagem) {
                    console.log(`  ⚠️  Sem imagem cadastrada`);
                } else {
                    console.log(`  ⚠️  Formato desconhecido`);
                }
            }

            console.log('\n✅ Processo concluído!');
        }
    } catch (error) {
        console.error('❌ Erro:', error.message);
    }
}

forceDownloadImages();
