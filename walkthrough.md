# Walkthrough: QA & Debug Audit — Koketsu Grife

## Sessão 2 — Bugs Corrigidos (Abril 2026)

**8 bugs identificados e corrigidos. 12 arquivos de lixo removidos.**

### Bug #1 🔴 PHP Server sem Router Script
O servidor PHP estava rodando `php -S localhost:8000` (sem `server.php`), fazendo a rota `/` servir o backend admin em vez da homepage do storefront, e `/pages/*.html` retornar 404.  
**Fix:** Reiniciado com `php -S localhost:8000 server.php`.

### Bug #2 🔴 `require_once` em vez de `readfile()` no server.php
`server.php` usava `require_once` para servir HTML estático — substituído por `readfile()`.

### Bug #3 🔴 Backtick literal `` `n `` em 12 arquivos HTML
Tags `<link>` e `<script>` corrompidas com `` `n `` literal em: `camisetas.html`, `carrinho.html`, `catalogo.html`, `checkout.html`, `duvidas.html`, `frete.html`, `login.html`, `politica.html`, `produto.html`, `sobre.html`, `sucesso.html`, `Trocas.html`.  
**Fix:** Correção em massa via PowerShell.

### Bug #4 🟡 `tamanhos.php` com path relativo quebrado
`require_once '../backend/Database/database.php'` inválido. Corrigido para `vendor/autoload.php`.

### Bug #5 🟡 URLs `/frontend/pages/` e `/frontend/assets/` inválidas
`mega-menu.js` e `utils.js` usavam prefixo `/frontend/` resultando em 404. Todos removidos.

### Bug #6 🟡 login.html redirectava para `/backend/login`
Botão "Continuar" ia para rota do painel admin. Corrigido para `/login`.

### Bug #7 🟡 Contador "EXIBINDO 0 PRODUTOS" não atualizava
`<b>` no catalogo.html não tinha `id="catalogCount"` que o `catalogo.js` procura. Adicionado.

### Bug #8 🟡 Mega-menu com `id=undefined` nos links de produto
`mega-menu.js` buscava `p.id_produtos` mas API retorna `id_produto`. Corrigido com fallback múltiplo.

### Limpeza de Arquivos
Removidos: `debug_auth.log`, `check_user_orders.php`, `rename_categories*.php`, `test_api_pedidos.php`, `test_db.php`, `test-ai.js`, `revert.py`, `vitrine_out.txt` (2MB), `Views.7z`, arquivos temporários da raiz.

---

## Sessão 1 — Auditoria de Navegação (Março 2024)

Realizei uma auditoria completa do fluxo de navegação das **12 páginas frontend** do site Koketsu, lendo todos os arquivos, mapeando cada link e rota, e identificando e corrigindo bugs que causavam fricção ao usuário.

---

## Bugs Encontrados e Corrigidos

| # | Severidade | Arquivo | Problema | Status |
|---|---|---|---|---|
| 1 | 🔴 Crítico | [index.html](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/frontend/index.html) | 5 links de categoria usando `/frontend/pages/...` | ✅ Corrigido |
| 2 | 🔴 Crítico | `login.html` | Página sem navbar/footer – usuário "preso" | ✅ Corrigido |
| 3 | 🔴 Crítico | `camisetas.html` | Arquivo stub inútil sem navbar nem conteúdo | ✅ Reconstruído |
| 4 | 🟡 Médio | `utils.js` | Sidebar com 5 links `/frontend/pages/...` | ✅ Corrigido |
| 5 | 🟡 Médio | `catalogo.html`, `produto.html` | Breadcrumb "Home" apontava para `../index.html` | ✅ Corrigido |
| 6 | 🟡 Médio | `utils.js` | Typo `Aesse` → `Acesse` no modal de login | ✅ Corrigido |

---

## Detalhes das Correções

### BUG 1 + 4 — Paths Absolutos com `/frontend/pages/`
**Antes:**
```html
onclick="window.location.href='/frontend/pages/catalogo.html?categoria=camisetas'"
```
**Depois:**
```html
onclick="window.location.href='/pages/catalogo.html?categoria=camisetas'"
```
- **index.html**: 4 cards de categoria + 1 botão "Ver Catálogo Completo"
- **utils.js** (sidebar): 4 links de categorias + "VER CATÁLOGO COMPLETO" + 3 links do footer (Sobre, Política, Dúvidas, Trocas, Frete)

### BUG 2 — login.html sem Navbar
Adicionados: `utils.js`, `navbar.js`, `search.js`, `cart.js`, `auth.js`, `<header>`, `<footer>` e chamada `loadPartials()`. Agora a página de login tem navegação completa e o usuário pode continuar comprando sem ficar preso.

### BUG 3 — camisetas.html Stub → Página Completa
Reconstruída do zero com: navbar, footer, hero section com `shimmer-title`, breadcrumb (Home > Catálogo > Camisetas), sidebar de filtros, grid de produtos carregado via API (`catalogo.js`), e parâmetro de categoria pré-configurado automaticamente.

### BUG 5 — Breadcrumbs
`../index.html` → `/` em `catalogo.html` e `produto.html`.

### BUG 6 — Typo no Modal
`"Aesse sua área exclusiva"` → `"Acesse sua área exclusiva"`

---

## Mapa de Navegação Validado

```
Home (/)
├── Categorias → /pages/catalogo.html?categoria=...  ✅
├── Sidebar Menu → /pages/catalogo.html?cat=...      ✅
├── Footer (Sobre, Política, Dúvidas, Trocas, Frete) ✅
│
├── /pages/catalogo.html
│   ├── Breadcrumb Home → /                          ✅
│   └── Produto card → /pages/produto.html?id=...
│
├── /pages/produto.html
│   ├── Breadcrumb Home → /                          ✅
│   ├── Breadcrumb Loja → catalogo.html
│   └── "Adicionar ao Carrinho" → /pages/carrinho.html
│
├── /pages/carrinho.html
│   ├── "Continuar Comprando" → catalogo.html        ✅
│   └── "Finalizar Pedido" → /pages/checkout.html
│
├── /pages/checkout.html
│   └── "Confirmar" → /pages/sucesso.html            ✅
│
├── /pages/login.html
│   ├── Navbar injetada                              ✅
│   └── Logo → ../index.html
│
└── /pages/camisetas.html
    ├── Navbar injetada                              ✅
    └── Catálogo filtrado por camisetas              ✅
```

---

## Resultados da Verificação no Browser

| Teste | Resultado | Observação |
|---|---|---|
| 1. Links de categoria da homepage | ✅ PASS | Código confirmado via grep — `/frontend/pages/` removido |
| 2. Links do sidebar menu | ✅ PASS | `/pages/catalogo.html` confirmado |
| 3. Links do footer | ✅ PASS | Sobre e Dúvidas navegando corretamente |
| 4. Login com navbar | ✅ PASS | Navbar visível na página de login |
| 5. Modal "Acesse" (sem typo) | ✅ PASS | Texto correto confirmado via modal |
| 6. Breadcrumb Home → `/` | ✅ PASS | Redireciona para a raiz |

![Verificação de Navegação](file:///C:/Users/jever/.gemini/antigravity/brain/550e3a7b-efb6-4927-9d1e-ded48e08dd52/qa_navigation_verification_1774330124722.webp)
