/**
 * categorias.js
 * Script responsável por buscar as categorias do banco de dados (API) e renderizá-las no menu lateral.
 */

document.addEventListener('DOMContentLoaded', () => {
    carregarCategoriasMenu();
});

async function carregarCategoriasMenu() {
    try {
        // Tenta buscar as categorias da API local
        // Nota: O baseUrl é dinâmico (geralmente http://localhost:8000), 
        // mas tentaremos focar no endpoint base `/api/categorias` 
        const urlParams = new URLSearchParams(window.location.search);
        
        // Define fallback URL or get from configuration if exists
        const apiUrl = window.API_BASE_URL ? `${window.API_BASE_URL}/api/categorias` : 'http://localhost:8000/api/categorias';
        
        const response = await fetch(apiUrl);
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        
        const json = await response.json();
        const categorias = json.data || [];
        
        renderizarMenuCategorias(categorias);
    } catch (error) {
        console.error('Falha ao carregar categorias dinâmicas:', error);
        // Em caso de falha de conexão, as categorias não serão exibidas, ou poderiamos restaurar o hardcoded 
    }
}

function renderizarMenuCategorias(categorias) {
    const accordionMenu = document.getElementById('accordionMenu');
    if (!accordionMenu) return;

    // Salvar o "Catálogo Completo" que é o último link original do menu e não deve ser apagado
    const catalogoCompletoHTML = `
        <div class="accordion-item bg-transparent">
            <h2 class="accordion-header">
              <a href="${obterBaseUrlPaginas()}catalogo.html" class="accordion-button no-arrow text-warning">CATÁLOGO COMPLETO</a>
            </h2>
        </div>
    `;

    // Limpar as categorias hardcoded antigas
    accordionMenu.innerHTML = '';

    // Para cada categoria real vinda do banco...
    categorias.forEach((categoria, index) => {
        // Ignorar categorias vazias ou nulas
        if (!categoria.nome_categorias) return;

        const nomeUpper = categoria.nome_categorias.toUpperCase();
        const targetId = `collapseCategoria${index}`;
        const urlCatalogo = `${obterBaseUrlPaginas()}catalogo.html?cat=${encodeURIComponent(categoria.nome_categorias)}`;

        // Gerar o HTML para este item de sanfona (accordion) usando Tailwind
        const html = `
            <div class="border-b border-white/10 group">
                <h2>
                    <button type="button" class="w-full flex justify-between items-center py-4 text-left text-white hover:text-[var(--brand-yellow)] transition font-bold text-sm tracking-wider uppercase" 
                        onclick="const el = document.getElementById('${targetId}'); el.classList.toggle('hidden'); const icon = this.querySelector('i'); icon.classList.toggle('rotate-180');">
                        ${nomeUpper}
                        <i class="bi bi-chevron-down transition-transform duration-300"></i>
                    </button>
                </h2>
                <div id="${targetId}" class="hidden pb-4">
                    <div class="pl-4 border-l-2 border-[var(--brand-yellow)]">
                        <a href="${urlCatalogo}" class="block text-sm text-gray-400 hover:text-white transition py-2">Ver Tudo de ${categoria.nome_categorias}</a>
                    </div>
                </div>
            </div>
        `;
        
        accordionMenu.insertAdjacentHTML('beforeend', html);
    });

    // Anexar novmente o link de Catálogo Completo por último
    accordionMenu.insertAdjacentHTML('beforeend', catalogoCompletoHTML);
}

// Helper para ajudar se estamos na index.html ou destro da pasta pages/
function obterBaseUrlPaginas() {
    const isIndex = window.location.pathname.endsWith('index.html') || window.location.pathname === '/';
    return isIndex ? 'pages/' : '';
}
