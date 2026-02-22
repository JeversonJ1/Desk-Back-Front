const { getDatabase } = require('./database/sqlite-connection');

async function check() {
    console.log('Checking database products by API ID...');
    const db = await getDatabase();
    // Check API ID 3, 1
    const res = db.exec("SELECT id, id_produto_api, imagem FROM tbl_produtos WHERE id_produto_api IN (1, 3)");

    if (res.length > 0 && res[0].values) {
        console.log(`Found ${res[0].values.length} rows for API ID 1 and 3:`);
        res[0].values.forEach(row => {
            console.log(`LocalID: ${row[0]}, ApiID: ${row[1]}, Imagem: ${row[2].substring(0, 50)}...`);
        });
    } else {
        console.log('No rows found for API ID 1 or 3');
    }
}

check().catch(console.error);
