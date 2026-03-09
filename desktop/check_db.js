const pool = require('./database/mysql-connection');

async function checkDatabase() {
    try {
        const [rows] = await pool.query('SELECT COUNT(*) as cnt FROM tbl_produtos');
        console.log('COUNT FROM Desktop Database:', rows[0].cnt);
        
        const [all] = await pool.query('SELECT id_produto, excluido_em FROM tbl_produtos');
        console.log('All IDs:', all.map(r => r.id_produto).join(', '));
        console.log('Has excluido_em:', all.filter(r => r.excluido_em !== null).length);
        
        process.exit(0);
    } catch (e) {
        console.error(e);
        process.exit(1);
    }
}

checkDatabase();
