const http = require('http');

const BASE_URL = 'http://localhost:8000/backend/api';

const endpoints = [
    '/usuarios', // likely HTML
    '/api/usuarios', // likely JSON
    '/api/database/clientes', // from apiService, failed 404
    '/api/database/produtos',
    '/api/database/pedidos',
    '/api/database/categorias',
    // Try without double 'api' if applicable
    '/database/clientes',
    '/clientes',
    // Existing ones from apiService
    '/produtos',
    '/perfis',
    '/categorias',
    '/estoque',
    '/itens_pedidos',
    '/api/itenspedidos', // from index.php routing
    '/api/pedidos', // from index.php routing
    '/api/produtos' // from index.php routing
];

async function checkEndpoint(endpoint) {
    // Check if endpoint is full url (hack for testing variants)
    let url = endpoint.startsWith('http') ? endpoint : `${BASE_URL}${endpoint}`;

    // special cases for testing
    if (endpoint === '/database/clientes') url = 'http://localhost:8000/backend/api/database/clientes';

    console.log(`Checking ${url}...`);

    return new Promise((resolve) => {
        const req = http.get(url, (res) => {
            let data = '';
            res.on('data', (chunk) => data += chunk);
            res.on('end', () => {
                const status = res.statusCode;

                let isJson = false;
                try {
                    JSON.parse(data);
                    isJson = true;
                } catch (e) { }

                const preview = data.substring(0, 100).replace(/\n/g, ' ');

                console.log(`  -> Status: ${status}`);
                console.log(`  -> Type: ${isJson ? 'JSON' : 'Other'}`);
                console.log(`  -> Preview: ${preview}`);

                resolve({ endpoint, status, isJson });
            });
        });

        req.on('error', (e) => {
            console.error(`  -> Error: ${e.message}`);
            resolve({ endpoint, status: 'ERROR', error: e.message });
        });
    });
}

async function run() {
    console.log('--- Starting API Endpoint Tests (Round 2) ---');
    console.log(`Base URL: ${BASE_URL}\n`);

    const results = [];
    for (const ep of endpoints) {
        results.push(await checkEndpoint(ep));
    }
}

run();
