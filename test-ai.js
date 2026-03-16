async function test() {
    try {
        const response = await fetch('http://localhost:5000/api/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                message: "O que você me recomenda para um look urbano?", 
                history: [] 
            })
        });
        const data = await response.json();
        console.log('RESPOSTA IA:', data);
    } catch (e) {
        console.error('ERRO NO TESTE:', e.message);
    }
}
test();
