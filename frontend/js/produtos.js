const produtosContainer = document.getElementById('produtos-lista');
const cartCountEl = document.getElementById('cart-count');
const cartItemsEl = document.getElementById('cart-items');
const cartTotalEl = document.getElementById('cart-total');
const btnFinalizar = document.getElementById('btn-finalizar');
const pedidoStatusEl = document.getElementById('pedido-status');
let carrinho = [];
const localStorageKey = 'carrinhoKiPedreiro';
function renderizarProdutos(produtos) {
    produtosContainer.innerHTML = '';
    produtos.forEach(produto => {
        const precoFormatado = parseFloat(produto.preco_produtos).toFixed(2);
        const cardHtml = `
            <div class="produto-card">
                <img src="${produto.imagem_produtos || `data:image/svg+xml;utf8,${encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="200" height="200" fill="#1a1a1a"/><rect x="60" y="55" width="80" height="65" rx="4" fill="none" stroke="#444" stroke-width="2"/><circle cx="82" cy="78" r="8" fill="#444"/><polyline points="60,120 85,95 105,112 125,88 140,120" fill="none" stroke="#444" stroke-width="2"/><text x="100" y="155" text-anchor="middle" fill="#555" font-size="11" font-family="sans-serif">Sem imagem</text></svg>')}`}" alt="${produto.nome_produtos}">
                <h3 class="card-title text-uppercase">${produto.nome_produtos}</h3>
                <p class="card-text">R$ ${precoFormatado}</p>
                <button class="btn-add-cart" 
                        data-id="${produto.id_produto}" 
                        data-nome="${produto.nome_produtos}" 
                        data-preco="${produto.preco_produtos}">
                    Adicionar ao Carrinho
                </button>
            </div>
        `;
        produtosContainer.insertAdjacentHTML('beforeend', cardHtml);
    });
}

function inicializar() {
    carregarCarrinhoLocalStorage();
    renderizarCarrinho();
    fetch('/backend/api/produtos')
        .then(response =>
            response.ok ? response.json() : Promise.reject('Erro ao carregar produtos')
        )
        .then(json => {
            if (json.status === 'success' && json.data) {
                renderizarProdutos(json.data);
            } else {
                throw new Error(json.message || 'Erro ao buscar produtos da API.');
            }
        })
        .catch(err => {
            console.error("Erro ao carregar produtos:", err);
            produtosContainer.innerHTML = `<p style="color: red;">${err.message || 'Não foi possível carregar os produtos.'}</p>`;
        });


    produtosContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-add-cart')) {
            const { id, nome, preco } = e.target.dataset;
            adicionarAoCarrinho(id, nome, preco);
        }
    });

    cartItemsEl.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-cart')) {
            const id = e.target.dataset.id;
            removerDoCarrinho(id);
        }
    });

    btnFinalizar.addEventListener('click', finalizarPedido);
}
function salvarCarrinhoLocalStorage() {
    localStorage.setItem(localStorageKey, JSON.stringify(carrinho));
}

function carregarCarrinhoLocalStorage() {
    const carrinhoSalvo = localStorage.getItem(localStorageKey);
    if (carrinhoSalvo) {
        carrinho = JSON.parse(carrinhoSalvo);
    }
}

inicializar();