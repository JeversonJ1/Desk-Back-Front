const pool = require('./database/mysql-connection.js');
const fs = require('fs');
async function run() {
    const [rows] = await pool.query("SHOW CREATE TABLE tbl_produtos");
    const [rows2] = await pool.query("SHOW CREATE TABLE tbl_categorias");
    fs.writeFileSync('schema_dump.json', JSON.stringify({
        produtos: rows[0]['Create Table'],
        categorias: rows2[0]['Create Table']
    }, null, 2), 'utf8');
    process.exit(0);
}
run();
