# Análise de Identidade Visual: Koketsu Grife

A identidade visual da Koketsu Grife é construída sobre um contraste forte e elegante, projetada para destacar produtos de moda masculina premium. Abaixo estão os pilares visuais que compõem o design do site.

## 1. Paleta de Cores
A paleta é restrita e focada no alto contraste, o que confere um aspecto de luxo e sofisticação.

*   **Cor de Fundo Principal (Background):** Preto Absoluto (`#000000`). Cria a base do "dark mode" e dá um tom de exclusividade.
*   **Cor Secundária (Cards e Seções):** Cinza Escuro / Chumbo (aprox. `#1A1A1A` ou `#222222`). Usado nos fundos dos cartões de categorias e blocos de benefícios para separá-los do fundo preto sem quebrar a estética escura.
*   **Cor de Destaque (Accent / Call-to-Action):** Dourado / Amarelo Ouro Vivo. É a cor mais importante para a conversão. Utilizada em:
    *   Botões principais (ex: "ADICIONAR AO CARRINHO").
    *   Preços com desconto.
    *   Ícones de benefícios (Frete grátis, etc.).
    *   Bordas de botões secundários (ex: "VER CATÁLOGO COMPLETO").
    *   Detalhes do logotipo e grafismos no banner.
*   **Cores de Texto:**
    *   Branco puro para títulos principais e nomes de produtos, garantindo legibilidade máxima.
    *   Cinza claro para subtítulos, descrições e preços originais riscados.

## 2. Tipografia
As fontes escolhidas são modernas, limpas e de fácil leitura, características essenciais para o e-commerce.

*   **Títulos e Destaques (Headings):** Fonte sem serifa (Sans-serif), geométrica e em peso Bold/Black (Negrito forte). Geralmente aplicada em CAIXA ALTA (All-Caps), como em "NOSSAS CATEGORIAS", "CAMISETAS" e "CALÇAS". Transmite força e presença.
*   **Corpo de Texto (Body):** Fonte sem serifa, limpa, em peso regular. Usada para as descrições ("Conforto e estilo urbano", informações de parcelamento).
*   **Botões:** Texto em negrito, em caixa alta, para gerar urgência e clareza na ação.

## 3. Logotipo e Elementos Gráficos
*   **Logotipo:** O emblema principal é a cabeça de um tigre ilustrada em linhas douradas, com a tipografia "KOKETSU" acima e "GRIFE" abaixo. O tigre simboliza força, poder e instinto, alinhando-se perfeitamente com a moda masculina imponente.
*   **Estilo do Banner Principal (Hero Image):** Utiliza fotomontagem com os modelos recortados sobrepostos a elementos gráficos dinâmicos (linhas diagonais, polígonos) e efeitos de luz/brilho dourado. Isso traz um dinamismo "urbano/esportivo" para a marca.
*   **Ícones:** Os ícones da barra de benefícios (caminhão de frete, escudo de segurança, troca, cartão) são minimalistas, lineares (outline) e na cor dourada.

## 4. Layout e Interface (UI/UX)
O design utiliza blocos bem definidos e espaçamento generoso.

*   **Bordas Arredondadas (Border-Radius):** Quase todos os elementos (banners, cards de categorias, cards de produtos, botões e tags) possuem cantos suavemente arredondados. Isso quebra a rigidez do fundo preto e traz um ar mais moderno e polido ao site.
*   **Tags/Pills:** Uso de etiquetas ovais (estilo "pílula") com borda dourada para identificar nichos dentro das categorias (ex: `[ ESSENTIALS ]`, `[ WINTER 26 ]`, `[ STREETSTYLE ]`).
*   **Botões Primários (Preenchidos):** Fundo dourado sólido com texto em preto (máximo contraste). Ex: Adicionar ao Carrinho.
*   **Botões Secundários (Outline):** Fundo transparente, borda dourada e texto dourado. Ex: Ver Catálogo Completo.

## 5. Fotografia de Produtos
A forma como os produtos são exibidos é crucial para contrastar com o fundo escuro do site:

*   **Fundo das Fotos:** As roupas e calçados são apresentados em fundos muito claros (branco, cinza bem claro ou off-white).
*   **Efeito Visual:** Essa escolha técnica faz com que os produtos "pulem" da tela (efeito pop-out) quando colocados dentro dos cards escuros do site, chamando imediatamente a atenção do olhar do utilizador para as peças.
*   **Estilo:** Mistura de fotografia "ghost mannequin" (peças invisíveis, como as camisetas e calças) e fotos de calçados em ângulos laterais limpos.

## Resumo da Sensação Transmitida:
O site passa uma imagem de marca "Premium Streetwear". Não é uma roupa básica comum; é posicionada como algo de alto valor (exclusividade), seguro (ícones de confiança) e moderno (design escuro com neon/dourado).

---

# Identidade Visual: Koketsu Desktop (Painel Admin — Electron)

O aplicativo desktop é um **painel administrativo interno** construído em Electron. Compartilha o DNA dark/dourado da marca, mas com uma linguagem visual mais funcional e técnica — voltada para produtividade operacional, não para conversão de vendas.

## 1. Paleta de Cores

A paleta é essencialmente a mesma do site, mas com algumas adições funcionais de status.

| Papel | Cor | Hex |
|---|---|---|
| Fundo base (body) | Preto quase puro | `#0f0f0f` |
| Fundo de cards e painéis | Gradiente escuro | `#1a1a1a → #0f0f0f` |
| Sidebar | Gradiente vertical | `#000000 → #111111` |
| Borda padrão | Cinza muito escuro | `#1f1f1f` / `#222` / `#333` |
| **Accent principal (dourado)** | Amarelo ouro (hover/active) | `#F2C84B` |
| Accent secundário (dourado flat) | Amarelo sólido | `#F2C84B` |
| Texto primário | Branco | `#ffffff` |
| Texto secundário / labels | Cinza médio | `#888` / `#aaa` / `#ccc` |
| Dados de métrica (grandes números) | Amarelo dourado | `#F2C84B` |
| Títulos dos cards (`h3`) | Amarelo dourado | `#F2C84B` |
| Cabeçalhos de tabela (`th`) | Dourado flat | `#F2C84B` |
| **Status: Erro / Danger** | Vermelho gradiente | `#ff4444 → #cc0000` / `#dc3545` |
| **Status: Alerta / Estoque Baixo** | Rosa-vermelho suave | `rgba(220,53,69, 0.15)` + borda `#dc3545` |
| **Status: Pedidos** | Azul claro | `#64c8ff` |
| Métricas — Produtos (accent bar) | Verde | `#51cf66 → #2f9e44` |
| Métricas — Estoque (accent bar) | Azul | `#4dabf7 → #1971c2` |
| Métricas — Pedidos (accent bar) | Laranja | `#ffa94d → #fd7e14` |
| Métricas — Receita (accent bar) | Roxo | `#b197fc → #7950f2` |
| Logout (botão) | Vermelho gradiente | `#ff6b6b → #ff3b3b` |

> **Regra fundamental:** Dourado (`#F2C84B` / `#F2C84B`) é sempre a cor de estado ativo, foco de input, hover de menu e dados numéricos de destaque. Vermelho é exclusivo para ações destrutivas e alertas de estoque crítico.

## 2. Tipografia

*   **Fonte global:** `Arial, sans-serif` — escolha utilitária, clara e universalmente disponível, sem dependência de CDN externo.
*   **Fonte de login:** `'Segoe UI', Tahoma, Geneva, Verdana, sans-serif` — mais humanista e amigável para a tela de autenticação.
*   **Pesos usados:**
    *   `800` (Extra-Bold) — Títulos de dashboard, números de métricas, botão de login, nome da marca no header.
    *   `700` (Bold) — Nomes de produtos, resumo do carrinho, rótulos de seção.
    *   `600` (Semi-Bold) — Labels de tabela, subtítulos de cards.
    *   `400` (Regular) — Corpo de texto, descrições, metadados.
*   **Caixa alta (uppercase):** Usada em categorias de produtos (`.produto-card .categoria`), labels de tabela e botões de ação (`.btn-gold` com `text-transform: uppercase`).
*   **Letter-spacing:** `1px` em labels de categoria; `0.5px` em botões de ação — cria respiro sem exagerar.

## 3. Layout e Estrutura (UI)

O desktop segue um layout clássico de painel administrativo de **sidebar fixa + área de conteúdo**:

*   **Sidebar:** largura fixa de `220px`, gradiente vertical `#000 → #111`, borda direita `1px solid #1f1f1f`, posição `fixed`. Contém logo centralizado e menu de navegação vertical.
*   **Área de conteúdo:** ocupa o restante (`flex: 1`, `margin-left: 220px`), com `padding: 25px` e scroll interno.
*   **Dashboard Header:** bloco de cabeçalho de seção com gradiente `#1a1a1a → #0f0f0f`, `border-radius: 16px`, `border: 2px solid #222`, `box-shadow`. Título em dourado `#F2C84B` + subtítulo em cinza.
*   **Grid de métricas:** 4 cards em linha (`col-md-6 col-lg-3`), cada um com uma barra de cor lateral (4px) que diferencia a categoria (verde, azul, laranja, roxo).
*   **Grid de produtos:** `repeat(auto-fill, minmax(220px, 1fr))` — responsivo e automático.
*   **Mobile:** sidebar se oculta com `transform: translateX(-100%)`, ativada por um botão hambúrguer com backdrop escuro.

## 4. Componentes e Padrões Visuais

### Cards
```
background: linear-gradient(135deg, #1a1a1a, #0f0f0f)
border-radius: 16px
border: 2px solid #222
box-shadow: 0 4px 12px rgba(0,0,0,0.3)
```
Hover: `border-color: #333`, `box-shadow` mais intenso.

### Botão Primário (`.btn-gold`)
```
background: linear-gradient(135deg, #F2C84B, #F2C84B)
color: #000   /* máximo contraste */
border-radius: 10px
box-shadow: 0 4px 12px rgba(255,216,77,0.3)
font-weight: 700; text-transform: uppercase
```
Hover: sobe 3px (`translateY(-3px)`), sombra dourada mais intensa.

### Inputs (`.form-control`)
```
background: #0f0f0f
border: 2px solid #333
border-radius: 10px
color: #fff
```
Focus: `border-color: #F2C84B` + glow sutil `rgba(255,216,77,0.1)`.

### Menu de Navegação (Sidebar)
```
.menu a            → cor: #fff, border-radius: 6px
.menu a.active / :hover → background: #F2C84B, color: #000
.menu a.menu-logout → background: #ff6b6b → #ff3b3b (gradiente vermelho)
```

### Items de Lista (Produto/Carrinho/Pedido)
*   **Produto:** `border-left: 3px solid #F2C84B` + fundo `rgba(255,216,77,0.1)`
*   **Carrinho:** `border-left: 4px solid #F2C84B` + fundo `rgba(255,216,77,0.12)`
*   **Pedido (histórico):** `border-left: 4px solid #64c8ff` + fundo `rgba(100,200,255,0.08)`
*   **Alerta de estoque:** `border-left: 3px solid #dc3545` + fundo `rgba(220,53,69,0.15)`

### Cards de Produto (`.produto-card`)
*   Efeito de brilho shimmer no hover: `::before` com gradiente dourado deslizante.
*   Hover levanta o card (`translateY(-5px)`) e acende a borda dourada.
*   Preço em `#F2C84B` com `text-shadow` dourado sutil.

### Scrollbars personalizadas
Todas as listas (produtos, alertas, carrinho, histórico) têm scrollbar customizada:
*   Track: `#0f0f0f` (invisível no fundo escuro)
*   Thumb: `#F2C84B` (dourado) com `border-radius: 3-4px`

### Tela de Login
*   Card centralizado com `background: #2a2a2a → #1f1f1f`, `border-radius: 15px`, `box-shadow` profundo + glow dourado sutil (`rgba(255,216,77,0.2)`).
*   Logo com `border: 5px solid #F2C84B` e `box-shadow: 0 12px 40px rgba(255,216,77,0.5)` — elemento mais chamativo da tela.
*   Título "Koketsu" em dourado com `text-shadow`.
*   Botão de login com gradiente `#F2C84B → #F2C84B`, `color: #000`.

## 5. Micro-animações e Transições

| Elemento | Animação |
|---|---|
| Menu hover → ativo | `background: #F2C84B` (instantâneo, sem transition) |
| Cards de métrica (hover) | `translateY(-5px)`, borda → `#F2C84B`, sombra dourada |
| Cards de produto (hover) | `translateY(-5px)` + shimmer shimmer dourado + borda acende |
| Imagem do produto (hover interno) | `scale(1.05)` |
| Botão `.btn-gold` (hover) | `translateY(-3px)` |
| Botão login (hover) | `translateY(-2px)` + sombra mais intensa |
| Botão remover carrinho (hover) | `scale(1.1)` |
| Sidebar (mobile open/close) | `transform: translateX` com `transition: 0.3s ease` |
| Loading spinner (login) | `spin 1s linear infinite` |
| Indicador de etapa (recovery) | `.step-dot.active` → fundo `#F2C84B` |

## 6. Diferenças em Relação ao Site (Web)

| Aspecto | Site (Web) | Desktop (Admin) |
|---|---|---|
| **Público** | Clientes finais | Administradores internos |
| **Objetivo** | Vender / Engajar | Gerenciar / Operar |
| **Fonte** | Montserrat + Oswald (Google Fonts) | Arial / Segoe UI (sistema) |
| **Border-radius** | Generoso (xl, full) | Moderado (6px–16px) |
| **Cores de status** | Só dourado | Dourado + verde + azul + laranja + roxo + vermelho |
| **Animações** | Swiper, scroll parallax, hero | Micro-animações de hover e lift |
| **Fundo base** | `#000000` | `#0f0f0f` (quase preto) |
| **Dourado base** | `#F2C84B` / `var(--brand-yellow)` | `#F2C84B` / `#F2C84B` |
| **Layout** | Full-page com seções | Sidebar fixa + área de conteúdo |

## Resumo da Sensação Transmitida (Desktop):
O painel transmite **autoridade operacional com identidade de marca preservada**. É sério e funcional como um sistema de gestão profissional, mas mantém o DNA Koketsu (preto profundo + dourado) para que o administrador nunca esqueça que está dentro do ecossistema da marca. A cor dourada, reservada ao ouro no site de vendas, aqui representa **dados, ações e estados ativos** — transformando-se em ferramenta de usabilidade sem perder o caráter premium.
