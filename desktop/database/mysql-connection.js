// ===========================================================
// mysql-connection.js — Pool de conexão MySQL para o desktop
// Lê credenciais do .env na raiz do projeto
// ===========================================================
const mysql = require('mysql2/promise');
const path = require('path');
const fs = require('fs');

// Tenta carregar .env da raiz do projeto (dois níveis acima de desktop/)
function loadEnv() {
    const envPath = path.resolve(__dirname, '..', '..', '.env');
    if (!fs.existsSync(envPath)) return {};
    const lines = fs.readFileSync(envPath, 'utf8').split(/\r?\n/);
    const env = {};
    for (const line of lines) {
        const match = line.match(/^([^#=]+)=(.*)$/);
        if (match) env[match[1].trim()] = match[2].trim();
    }
    return env;
}

const env = loadEnv();

const pool = mysql.createPool({
    host: env.DB_HOST || 'localhost',
    user: env.DB_USER || 'root',
    password: env.DB_PASS || '',
    database: env.DB_NAME || 'koketsu',
    port: parseInt(env.DB_PORT || '3306'),
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,
    charset: 'utf8mb4',
    timezone: '+00:00'
});

// Testa a conexão ao iniciar
pool.getConnection()
    .then(conn => {
        console.log('[MySQL] Conexão com o banco de dados estabelecida com sucesso.');
        conn.release();
    })
    .catch(err => {
        console.error('[MySQL] ERRO ao conectar ao banco de dados:', err.message);
        console.error('[MySQL] Verifique as credenciais no arquivo .env e se o MySQL está rodando.');
    });

module.exports = pool;
