const fs = require('fs');
const path = require('path');
const initSqlJs = require('sql.js');

async function checkSchema() {
    const dbPath = path.join(__dirname, '../database/koketsu.sqlite');
    const SQL = await initSqlJs();

    if (!fs.existsSync(dbPath)) {
        console.log('Database file not found!');
        return;
    }

    const filebuffer = fs.readFileSync(dbPath);
    const db = new SQL.Database(filebuffer);

    const tables = ['tbl_clientes'];

    for (const table of tables) {
        console.log(`\nSchema for ${table}:`);
        try {
            const res = db.exec(`PRAGMA table_info(${table})`);
            if (res.length > 0 && res[0].values) {
                res[0].values.forEach(row => {
                    console.log(`- ${row[1]} (${row[2]})`);
                });
            } else {
                console.log('Table not found or empty info.');
            }
        } catch (e) {
            console.error(e);
        }
    }
}

checkSchema();
