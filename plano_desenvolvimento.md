# 🐯 Koketsu Grife — Plano de Desenvolvimento

## Estado Atual do Site (Diagnóstico — Abril 2026)

### ✅ O que está funcionando
| Página | Status | Observação |
|---|---|---|
| `/frontend/pages/catalogo.html` | ✅ OK | Grid de produtos, filtros, navbar funcionando |
| `/frontend/pages/carrinho.html` | ✅ OK | Lógica de carrinho via localStorage |
| `/frontend/pages/checkout.html` | ✅ OK | Formulário funcional |
| `/frontend/pages/produto.html` | ⚠️ Parcial | Redireciona antes de carregar |
| Páginas institucionais (sobre, duvidas, frete, politica, trocas) | ✅ OK | Layout ok |

### 🔴 Bugs Críticos
| # | Problema | Impacto |
|---|---|---|
| 1 | Rota `/` retorna 404 (homepage inacessível) | CRÍTICO — Cliente não acessa o site |
| 2 | Rota `/admin` retorna 404 (painel admin inacessível) | ALTO — Admin não consegue gerenciar |
| 3 | `produto.html` redireciona antes de carregar | ALTO — Perda de vendas |
| 4 | Homepage (`/`) não carrega CSS/JS (busca em `/assets/` em vez de `/frontend/assets/`) | MÉDIO |

---

## 🗺️ Mapa de Prioridades de Desenvolvimento

### 🔴 FASE 1 — Correção de Bugs Críticos (Urgente)
> Sem isso, clientes não conseguem navegar normalmente

- [ ] **Fix #1:** Corrigir rota raiz `/` no `server.php` para servir `frontend/index.html`
- [ ] **Fix #2:** Corrigir rota `/admin` no backend (router)
- [ ] **Fix #3:** Investigar e corrigir redirecionamento na página de produto
- [ ] **Fix #4:** Auditar paths de assets no `index.html`

---

### 🟡 FASE 2 — Funcionalidades Core de E-commerce (Alto Valor)
> Funcionalidades que impactam diretamente conversão

- [ ] **Checkout Real:** Integrar formulário de checkout com backend (salvar pedido via `/api/pedidos`)
- [ ] **Login Funcional:** Conectar formulário de login (`login.html`) com `/api/auth/desktop` ou `/login`
- [ ] **Página de Produto:** Garantir que produto carrega com dados corretos da API
- [ ] **WhatsApp CTA:** Botão "Finalizar via WhatsApp" no carrinho com mensagem formatada
- [ ] **Paginação Catálogo:** Paginação real no catálogo (atualmente estática/fake)

---

### 🟢 FASE 3 — UX e Design Premium (Diferenciação Visual)
> Features que elevam a percepção de premium e fidelizam clientes

- [ ] **Área do Cliente:** Dashboard do cliente (pedidos, perfil, avaliações)
- [x] **Página de Sucesso:** `sucesso.html` reescrita com confetti, partículas, resumo do pedido, timeline de status e botão WhatsApp de contato
- [x] **Sistema de Avaliações:** `reviews.js` conectado à API (`/api/avaliacoes.php`) — lista, stats com barras de distribuição, formulário autenticado
- [x] **Search Global:** `search.js` conectado à `/api/vitrine.php` com resultados em cards premium
- [x] **Newsletter:** `newsletter.js` conectado à `/api/newsletter/inscrever` (já existia, mantido)
- [ ] **Mega Menu:** Categorias e produtos em destaque carregados dinamicamente (já implementado parcialmente em `mega-menu.js`)

---

### 🔵 FASE 4 — Painel Administrativo (Operacional)
> Para o admin gerenciar o negócio

- [ ] **Fix Admin Login:** Corrigir acesso ao `/admin`
- [ ] **Dashboard KPIs:** Cards de métricas (receita, pedidos, produtos ativos)
- [ ] **CRUD Produtos:** Criar/editar/excluir produtos com imagens
- [ ] **Gestão de Pedidos:** Listar e atualizar status de pedidos
- [ ] **Gestão de Clientes:** Ver e editar clientes cadastrados

---

### 🟣 FASE 5 — Features Avançadas
> Para quando o core estiver sólido

- [ ] **Koketsu AI Concierge:** Assistente de IA funcional para sugestão de produtos
- [ ] **App Desktop Electron:** Painel Koketsu Desktop (já existe estrutura em `/frontend/koketsu/`)
- [ ] **SEO & Performance:** Meta tags dinâmicas, lazy loading, cache
- [ ] **Relatórios Admin:** Gráficos de vendas e estoque

---

## 🚀 Sugestão de Próximo Sprint

**Hoje recomendo focar em:**

1. ✅ Corrigir a rota `/` (homepage não abre)
2. ✅ Corrigir a rota `/admin` (painel não abre)
3. ✅ Corrigir `produto.html` (redireciona incorretamente)
4. 🆕 Implementar **checkout integrado** com API + WhatsApp fallback

---

## 📁 Estrutura do Projeto

```
/
├── frontend/
│   ├── index.html              ← Homepage
│   ├── pages/                  ← Todas as páginas do storefront
│   │   ├── catalogo.html       ← Catálogo de produtos
│   │   ├── produto.html        ← Detalhes do produto
│   │   ├── carrinho.html       ← Carrinho
│   │   ├── checkout.html       ← Checkout
│   │   ├── login.html          ← Login/Cadastro
│   │   └── ...institucional
│   ├── assets/
│   │   ├── js/                 ← Scripts (utils, cart, produto, catalogo...)
│   │   └── css/                ← Estilos (global + componentes)
│   └── koketsu/                ← App Desktop Electron
│
├── backend/
│   ├── index.php               ← Entry point do backend
│   ├── Rotas/Rotas.php         ← Definição de todas as rotas
│   ├── Controles/              ← Controllers PHP
│   └── Views/                  ← Views PHP (admin)
│
└── server.php                  ← Router PHP de desenvolvimento
```
