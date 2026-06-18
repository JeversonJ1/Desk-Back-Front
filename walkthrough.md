# Walkthrough: QA & Debug Audit — Koketsu Grife

## Sessão 4 — Correção do Modo Manutenção e Banner Promocional (Junho 2026)

**Corrigidos os bugs que impediam o funcionamento e exibição do Modo Manutenção e do Banner Promocional no storefront.**

### Bug #1 🔴 Rota `/backend/configuracoes/manutencao` retornando 404
Ao tentar ativar o modo manutenção no painel, o AJAX enviava um POST para `/backend/configuracoes/manutencao` mas a rota no arquivo de rotas não continha o prefixo `/backend/`.
* **Fix:** Adicionadas as rotas duplicadas com prefixo `/backend/` em [rotas.php](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/backend/Rotas/rotas.php) para `/backend/configuracoes/manutencao` e `/backend/configuracoes/salvar`.

### Bug #2 🔴 Modo Manutenção não funcionava no Storefront
Como a homepage e as páginas do e-commerce são estáticas (`.html`) e carregadas diretamente via `server.php` por motivos de performance, as validações de backend em PHP não eram executadas para os visitantes normais.
* **Fix (Server-Side):** Adicionada verificação no [server.php](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/server.php) que intercepta requisições de páginas HTML. Se a manutenção estiver ativa no `settings.json` e o usuário não for um administrador logado, o servidor bloqueia o acesso e exibe a tela de manutenção premium.
* **Fix (Client-Side):** Adicionada verificação dinâmica na função `loadPartials` do arquivo [utils.js](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/frontend/assets/js/utils.js). Caso o usuário acesse qualquer página do storefront via cache ou carregamento direto e a manutenção esteja ativa (sem privilégios de administrador), o DOM é substituído pela tela de manutenção.

### Bug #3 🔴 Banner Promocional não aparecia no site
As configurações de texto e cor do banner promocional eram salvas, mas o arquivo da API pública de configurações do frontend ([config.php](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/frontend/api/config.php)) não retornava esses valores e o frontend não possuía o HTML/CSS do banner.
* **Fix:** 
  1. Atualizado [config.php](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/frontend/api/config.php) para retornar as propriedades do banner.
  2. Implementada renderização dinâmica de um **banner infinito estilo carrossel (Marquee)** em [utils.js](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/frontend/assets/js/utils.js).
  3. Na **homepage**, o banner carrossel é posicionado perfeitamente **abaixo do banner principal (hero carousel)** de forma integrada no layout. O banner foi removido das páginas internas de catálogo e produto, aparecendo exclusivamente na tela inicial.
  4. Removidos os marcadores circulares de paginação do carrossel principal (Swiper pagination bullets) que obstruíam e prejudicavam a visibilidade dos banners de roupas.

### Melhoria 5 🟢 Ativação Dinâmica de SEO & Redes Sociais no Storefront
As configurações de Informações & SEO e Redes Sociais eram salvas no painel de controle, mas não alteravam as páginas do cliente.
* **Fix (SEO):** Atualizado [config.php](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/frontend/api/config.php) para fornecer metadados de SEO (Título, Descrição, CNPJ, E-mail de Contato, Endereço Físico) e implementada a injeção dinâmica desses metadados na aba do navegador e nas tags `<meta>` via [utils.js](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/frontend/assets/js/utils.js).
* **Fix (Redes Sociais):**
  1. Adicionado suporte a mais uma rede social (**Facebook**) nas configurações do painel administrativo, salvando em [settings.json](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/backend/Config/settings.json).
  2. Implementada injeção dinâmica nos botões e logos de redes sociais do rodapé da loja, associando os links reais (Instagram, Facebook, TikTok, YouTube) e o botão do WhatsApp ao respectivo número configurado. Ao clicar no ícone, o cliente é redirecionado em uma nova aba para a rede da marca.

---

## Sessão 3 — Remoção da Restrição de Tamanho de Upload de Banners (Junho 2026)

**Removido o limite de 2MB para upload de imagens de banners sem perda de qualidade.**

### Melhoria 1 🟢 Aumento do limite na aplicação
No arquivo [FileManager.php](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/backend/Core/FileManager.php), o tamanho máximo padrão de upload (`$tamanhoMaximo`) foi alterado de **2MB (2097152)** para **100MB (104857600)**. Isso permite que banners e outras mídias de alta resolução sejam enviados sem erros ou compactação forçada.

### Melhoria 2 🟢 Configurações do Servidor PHP (`.user.ini`)
Criados arquivos [.user.ini](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/.user.ini) na raiz do projeto e [backend/.user.ini](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/backend/.user.ini) definindo:
- `upload_max_filesize = 100M`
- `post_max_size = 100M`
- `memory_limit = 256M`
Isso garante que o servidor PHP aceite requisições POST com arquivos de tamanho elevado.

### Melhoria 3 🟢 Atualização visual da interface de Banners
No painel de configurações [index.php](file:///c:/Users/jever/OneDrive/%C3%81rea%20de%20Trabalho/Desk-Back-Front/backend/Views/Templates/configuracoes/index.php), o texto indicativo de tamanho máximo foi alterado de `"Máx 2MB"` para `"Alta Resolução"` para refletir o novo limite e guiar o usuário.

---

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
