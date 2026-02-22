const { getDatabase, saveDatabase } = require('./database/sqlite-connection');

async function fix() {
    console.log('Manually updating DB...');
    const db = await getDatabase();

    // Manual Update for ID 3
    db.run("UPDATE tbl_produtos SET imagem = 'file://manual_update_test.png' WHERE id_produto_api = 3");

    saveDatabase();
    console.log('Update executed and saved.');
}

fix().catch(console.error);
