const http = require('http');

const DEFAULT_BASE_URL = 'http://localhost:8000/backend/api';
const BASE_URL = process.argv[2] || DEFAULT_BASE_URL;

const endpoints = [
    '/usuarios',
    '/perfis',
    '/categorias',
    '/produtos',
    '/imagens',
    '/cores',
    '/estoque',
    '/pedidos',
    '/itens_pedidos'
];

async function testEndpoint(endpoint) {
    const url = `${BASE_URL}${endpoint}`;
    return new Promise((resolve) => {
        const req = http.get(url, (res) => {
            let data = '';
            res.on('data', (chunk) => data += chunk);
            res.on('end', () => {
                let parsed = null;
                try {
                    parsed = JSON.parse(data);
                } catch (e) {
                    // Not JSON
                }

                resolve({
                    url,
                    status: res.statusCode,
                    statusText: res.statusMessage,
                    contentType: res.headers['content-type'],
                    data: parsed,
                    raw: data
                });
            });
        });

        req.on('error', (e) => {
            resolve({
                url,
                error: e.message
            });
        });

        req.setTimeout(5000, () => {
            req.destroy();
            resolve({ url, error: 'TIMEOUT (5s)' });
        });
    });
}

async function runTests() {
    console.log(`\n=== INICIANDO TESTES DE API ===`);
    console.log(`Base URL: ${BASE_URL}\n`);

    let successCount = 0;
    let errorCount = 0;

    for (const endpoint of endpoints) {
        process.stdout.write(`Testando ${endpoint}... `);

        try {
            const result = await testEndpoint(endpoint);

            if (result.error) {
                console.log(`❌ ERRO`);
                console.log(`   Detalhe: ${result.error}`);
                errorCount++;
            } else if (result.status >= 200 && result.status < 300) {
                console.log(`✅ OK (${result.status})`);

                if (result.data) {
                    const count = 'data' in result.data && Array.isArray(result.data.data) ? result.data.data.length :
                        Array.isArray(result.data) ? result.data.length : 'N/A';

                    const statusApi = result.data.status || 'N/A';
                    console.log(`   Status API: ${statusApi}`);
                    console.log(`   Items: ${count}`);
                } else {
                    console.log(`   Resposta não é JSON ou vazia.`);
                    const snippet = result.raw ? result.raw.substring(0, 100) : 'EMPTY';
                    console.log(`   Snippet: ${snippet}...`);
                }
                successCount++;
            } else {
                console.log(`⚠️  FALHA (${result.status} ${result.statusText})`);
                const snippet = result.raw ? result.raw.substring(0, 100) : 'EMPTY';
                console.log(`   Snippet: ${snippet}...`);
                errorCount++;
            }
        } catch (e) {
            console.log(`❌ EXCEÇÃO: ${e.message}`);
            errorCount++;
        }
        console.log(''); // Empty line
    }

    console.log(`=== RESUMO ===`);
    console.log(`Sucessos: ${successCount}`);
    console.log(`Erros/Falhas: ${errorCount}`);
    console.log('================');
}

runTests();
