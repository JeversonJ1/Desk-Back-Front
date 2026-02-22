const fs = require('fs');
const path = require('path');
const initSqlJs = require('sql.js');

async function checkDB() {
    const dbPath = path.join(__dirname, 'database/koketsu.sqlite');

    if (!fs.existsSync(dbPath)) {
        console.log('❌ Banco de dados não existe ainda');
        return;
    }

    console.log(`📁 Banco: ${dbPath}\n`);

    const SQL = await initSqlJs();
    const filebuffer = fs.readFileSync(dbPath);
    const db = new SQL.Database(filebuffer);

    const res = db.exec("SELECT id, id_produto_api, nome, imagem FROM tbl_produtos WHERE deletado = 0 OR deletado IS NULL");

    if (res.length > 0 && res[0].values) {
        console.log(`Total: ${res[0].values.length} produtos\n`);

        res[0].values.forEach((row, index) => {
            const [id, apiId, nome, imagem] = row;
            console.log(`${index + 1}. "${nome}" (ID: ${id}, API: ${apiId})`);

            if (imagem) {
                if (imagem.startsWith('file://')) {
                    console.log(`   ✅ file:// - ${imagem.substring(7, 70)}...`);
                } else if (imagem.startsWith('data:image')) {
                    console.log(`   📷 Base64 - ${imagem.length} chars`);
                } else {
                    console.log(`   ⚠️  Relativo - ${imagem}`);
                }
            } else {
                console.log(`   ❌ NULL`);
            }
        });
    } else {
        console.log('Nenhum produto encontrado');
    }
}

checkDB().catch(console.error);
