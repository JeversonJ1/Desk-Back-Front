const http = require('http');

const urls = [
    'http://localhost:8000/backend/api/usuarios',
    'http://localhost:8000/backend/api/api/usuarios',
    'http://localhost:8000/backend/api/database/clientes',
    'http://localhost:8000/backend/api/api/database/clientes',
    'http://localhost:8000/backend/api/produtos',
    'http://localhost:8000/backend/api/api/produtos',
];

async function check(url) {
    console.log(`Checking ${url}...`);
    return new Promise((resolve) => {
        http.get(url, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                console.log(`  -> Status: ${res.statusCode}`);
                try {
                    JSON.parse(data);
                    console.log('  -> Valid JSON');
                } catch (e) {
                    console.log('  -> NOT JSON');
                    console.log('  -> Preview: ' + data.substring(0, 50));
                }
                resolve();
            });
        }).on('error', (e) => {
            console.log(`  -> Error: ${e.message}`);
            resolve();
        });
    });
}

(async () => {
    for (const url of urls) {
        await check(url);
    }
})();
