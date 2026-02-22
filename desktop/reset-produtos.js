const fs = require('fs');
const path = require('path');
const initSqlJs = require('sql.js');

async function resetProdutos() {
    const dbPath = path.join(__dirname, 'database/koketsu.sqlite');

    if (!fs.existsSync(dbPath)) {
        console.log('❌ Banco não existe');
        return;
    }

    console.log('🗑️  Limpando produtos do banco...\n');

    const SQL = await initSqlJs();
    const filebuffer = fs.readFileSync(dbPath);
    const db = new SQL.Database(filebuffer);

    // Deletar apenas produtos
    db.run("DELETE FROM tbl_produtos");
    db.run("DELETE FROM tbl_tamanhos");
    db.run("DELETE FROM tbl_categorias");

    console.log('✅ Produtos, tamanhos e categorias removidos');
    console.log('ℹ️  Usuários e outros dados mantidos\n');

    // Salvar
    const data = db.export();
    const buffer = Buffer.from(data);
    fs.writeFileSync(dbPath, buffer);

    console.log('💾 Banco atualizado!');
    console.log('\n🔄 Agora recarregue a aplicação Electron (Ctrl+R)');
    console.log('   Os produtos serão sincronizados novamente com as imagens corretas!\n');
}

resetProdutos().catch(console.error);
