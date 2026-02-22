const { getDatabase } = require('../database/sqlite-connection.js');

async function run() {
    try {
        console.log("Applying migrations...");
        const db = await getDatabase();

        // Verify columns
        const tables = ['tbl_produtos'];
        for (const table of tables) {
            console.log(`\nVerifying ${table}...`);
            const res = db.exec(`PRAGMA table_info(${table})`);
            if (res.length > 0 && res[0].values) {
                const cols = res[0].values.map(r => r[1]);
                console.log('Columns:', cols.join(', '));
                if (cols.includes('deletado') && cols.includes('sincronizado')) {
                    console.log('✅ Migrations successful');
                } else {
                    console.log('❌ Missing columns!');
                }
            }
        }
    } catch (error) {
        console.error("Error running migrations:", error);
    }
}

run();
