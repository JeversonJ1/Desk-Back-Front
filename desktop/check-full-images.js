const { getDatabase } = require('./database/sqlite-connection');

async function check() {
    console.log('='.repeat(60));
    console.log('Verificando TODAS as imagens no banco de dados');
    console.log('='.repeat(60));

    const db = await getDatabase();
    const res = db.exec("SELECT id, id_produto_api, nome, imagem FROM tbl_produtos WHERE deletado = 0");

    if (res.length > 0 && res[0].values) {
        console.log(`\nTotal de produtos encontrados: ${res[0].values.length}\n`);
        res[0].values.forEach((row, index) => {
            const [id, apiId, nome, imagem] = row;
            console.log(`\n${index + 1}. Produto: "${nome}"`);
            console.log(`   LocalID: ${id}`);
            console.log(`   ApiID: ${apiId}`);
            console.log(`   Tipo de imagem: ${typeof imagem}`);

            if (imagem) {
                console.log(`   Tamanho string: ${imagem.length} chars`);
                console.log(`   Primeiros 100 chars: ${imagem.substring(0, 100)}`);
                console.log(`   Começa com file://: ${imagem.startsWith('file://')}`);
                console.log(`   Começa com data:image: ${imagem.startsWith('data:image')}`);
                console.log(`   Começa com http: ${imagem.startsWith('http')}`);
            } else {
                console.log(`   ⚠️  IMAGEM É NULL/VAZIA`);
            }
            console.log(`   ${'-'.repeat(50)}`);
        });
    } else {
        console.log('❌ Nenhum produto encontrado');
    }
}

check().catch(console.error);
