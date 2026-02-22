const { getDatabase } = require('./database/sqlite-connection');

async function checkProducts() {
    const db = await getDatabase();

    // Verificar todos os produtos
    const all = db.exec("SELECT id, id_produto_api, nome, imagem FROM tbl_produtos");

    if (all.length > 0 && all[0].values) {
        console.log(`Total de produtos no banco: ${all[0].values.length}\n`);

        all[0].values.forEach((row, index) => {
            const [id, apiId, nome, imagem] = row;
            console.log(`${index + 1}. ID: ${id} | API_ID: ${apiId} | Nome: "${nome}"`);
            if (imagem) {
                const preview = imagem.substring(0, 60);
                console.log(`   Imagem: ${preview}${imagem.length > 60 ? '...' : ''}`);
                console.log(`   Tipo: ${imagem.startsWith('file://') ? 'file://' : imagem.startsWith('data:') ? 'data:' : 'relativo'}`);
            } else {
                console.log(`   Imagem: NULL`);
            }
            console.log('');
        });
    } else {
        console.log('❌ Nenhum produto no banco');
    }

    // Verificar produtos deletados
    const deleted = db.exec("SELECT COUNT(*) FROM tbl_produtos WHERE deletado = 1");
    if (deleted.length > 0) {
        console.log(`\nProdutos deletados: ${deleted[0].values[0][0]}`);
    }
}

checkProducts().catch(console.error);
