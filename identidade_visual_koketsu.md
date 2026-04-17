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

# Identidade Visual: Koketsu Desktop (Painel Admin)

O painel administrativo compartilha o DNA dark/dourado da marca, mas com uma linguagem visual mais funcional e técnica — voltada para produtividade operacional, não para conversão de vendas.

## 1. Paleta de Cores

A paleta do painel admin é composta por **um tom base escuro + quatro accents cromáticos com funções semânticas definidas**. O dourado continua como cor de interação principal, mas não é o único elemento de cor — cada métrica e contexto tem sua própria identidade cromática.

### Cores de Fundo e Estrutura

| Papel | Cor | Hex |
|---|---|---|
| Fundo base (body) | Preto quase puro | `#0f0f0f` |
| Fundo de cards e painéis | Gradiente escuro | `#1a1a1a → #0f0f0f` |
| Sidebar | Gradiente vertical | `#000000 → #111111` |
| Borda padrão | Cinza muito escuro | `#222` / `#333` |
| Texto primário | Branco | `#ffffff` |
| Texto secundário / labels | Cinza médio | `#888` / `#aaa` |

### 🟡 Accent 1 — Dourado (Primário / Interação)

| Variável CSS | Valor |
|---|---|
| `--accent` | `#F2C84B` |
| `--accent-glow` | `rgba(242, 200, 75, 0.25)` |
| `--accent-dim` | `rgba(242, 200, 75, 0.07)` |

**Aplicações:** menu ativo, `thead th`, números grandes, botão primário, scrollbar, borda de card ativo, foco de input.

### 🟤 Accent 2 — Cobre / Bronze (Financeiro / Inventário)

| Variável CSS | Valor |
|---|---|
| `--copper` | `#C47A3A` |
| `--copper-glow` | `rgba(196, 122, 58, 0.25)` |
| `--copper-dim` | `rgba(196, 122, 58, 0.08)` |

**Aplicações:** card de Valor de Inventário, receita em gradiente (Dourado → Cobre), métricas financeiras premium.

### 🔵 Accent 3 — Azul Aço (Informação / Pedidos / Estoque)

| Variável CSS | Valor |
|---|---|
| `--steel` | `#4E9EBF` |
| `--steel-glow` | `rgba(78, 158, 191, 0.22)` |
| `--steel-dim` | `rgba(78, 158, 191, 0.07)` |

**Aplicações:** card de Pedidos, card de Estoque, links de informação, histórico de pedido.

### 🟣 Accent 4 — Ametista / Roxo (Premium / Administrativo)

| Variável CSS | Valor |
|---|---|
| `--amethyst` | `#8B5CF6` |
| `--amethyst-glow` | `rgba(139, 92, 246, 0.22)` |
| `--amethyst-dim` | `rgba(139, 92, 246, 0.07)` |

**Aplicações:** card de Produtos Ativos, card de Administradores, níveis de acesso elevados.

### Cores de Status Semântico

| Status | Cor | Hex |
|---|---|---|
| **Sucesso / Ativo** | Verde | `#51cf66` |
| **Alerta / Baixo Estoque** | Laranja | `#ffa94d` |
| **Perigo / Inativo / Excluir** | Vermelho | `#dc3545` |
| **Informação / Pedidos** | Azul claro | `#64c8ff` |

> **Regra fundamental:** Dourado = interação ativa. Cobre = valor financeiro. Azul Aço = fluxo de informação. Ametista = roles elevados. Vermelho = ações destrutivas. Verde = sucesso e crescimento.

### Gradientes de KPI Cards

| Card | Gradiente | CSS |
|---|---|---|
| Receita | Dourado → Cobre | `--kpi-revenue` |
| Pedidos | Azul Aço → Azul escuro | `--kpi-orders` |
| Produtos | Ametista → Roxo escuro | `--kpi-products` |
| Usuários | Verde médio → Verde escuro | `--kpi-users` |

## 2. Tipografia

*   **Fonte global:** `Arial, sans-serif` — escolha utilitária, clara e universalmente disponível, sem dependência de CDN externo.
*   **Fonte de login:** `'Segoe UI', Tahoma, Geneva, Verdana, sans-serif` — mais humanista e amigável para a tela de autenticação.
*   **Pesos usados:**
    *   `800` (Extra-Bold) — Títulos de dashboard, números de métricas, botão de login, nome da marca no header.
    *   `700` (Bold) — Nomes de produtos, resumo do carrinho, rótulos de seção.
    *   `600` (Semi-Bold) — Labels de tabela, subtítulos de cards.
    *   `400` (Regular) — Corpo de texto, descrições, metadados.
*   **Caixa alta (uppercase):** Usada em labels de tabela (`thead th`) e botões de ação com `text-transform: uppercase`.
*   **Letter-spacing:** `1px` em labels de `thead`; `0.5px` em botões de ação.

## 3. Layout e Estrutura (UI)

*   **Sidebar:** largura fixa de `260px`, gradiente vertical `#000 → #111`, borda direita `1px solid #222`, posição `fixed`.
*   **Área de conteúdo:** `margin-left: 260px`, `padding: 40px`, scroll interno.
*   **Dashboard Header:** gradiente `#1a1a1a → #0f0f0f`, `border-radius: 16px`, `border: 2px solid #222`.
*   **Grid de métricas:** 4 cards em linha (`repeat(4, 1fr)`), cada um com barra de cor lateral (3px top) diferenciada por categoria.

## 4. Componentes e Padrões Visuais

### Cards
```css
background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
border-radius: 16px;
border: 2px solid #222;
box-shadow: 0 4px 12px rgba(0,0,0,0.3);
```
Hover: `border-color: var(--accent)`, `box-shadow` mais intenso + `translateY(-3px)`.

### Botão Primário (`.btn-gold`)
```css
background: linear-gradient(135deg, #F2C84B, #d4a800);
color: #000;
border-radius: 10px;
box-shadow: 0 4px 12px rgba(242,200,75,0.3);
font-weight: 700;
text-transform: uppercase;
```

### Inputs
```css
background: #0f0f0f;
border: 2px solid #222;
border-radius: 10px;
color: #fff;
/* Focus: */
border-color: #F2C84B;
box-shadow: 0 0 0 3px rgba(242,200,75,0.25);
```

### Tabelas
```css
thead th {
  color: #F2C84B;
  background-color: #1a1a1a;
  border-bottom: 2px solid #222;
  letter-spacing: 1px;
  font-weight: 800;
  text-transform: uppercase;
}
tbody tr:hover { background: rgba(242,200,75,0.07); }
```

## 5. Filosofia da Paleta Expandida

O painel admin **não deve ser todo amarelo/dourado**. O dourado é sagrado — representa interação ativa. Para que ele mantenha seu impacto, as demais áreas de cor seguem uma lógica funcional:

- **Cobre `#C47A3A`**: Quente e metálico, complementa o dourado sem competir. Sugere valor material e riqueza.
- **Azul Aço `#4E9EBF`**: Frio e neutro, cria contraste cromático elegante com o dourado. Representa fluxo de informação.
- **Ametista `#8B5CF6`**: Premium e exclusivo, reservado para roles administrativos. Sugere hierarquia e raridade.
- **Verde `#51cf66`**: Status positivo universal, usado com moderação para métricas de crescimento.

Todos os quatro accents funcionam sobre fundo escuro (`#0f0f0f / #1a1a1a`) sem criar ruído visual. A identidade Koketsu permanece: **escuro + metálico premium**.

## 6. Diferenças em Relação ao Site (Web)

| Aspecto | Site (Web) | Painel Admin |
|---|---|---|
| **Público** | Clientes finais | Administradores internos |
| **Objetivo** | Vender / Engajar | Gerenciar / Operar |
| **Fonte** | Montserrat + Oswald | Arial / Segoe UI |
| **Border-radius** | Generoso (xl, full) | Moderado (6px–16px) |
| **Cores de accent** | Só dourado | Dourado + Cobre + Azul Aço + Ametista |
| **Animações** | Swiper, parallax, hero | Micro-animações de hover e lift |
| **Fundo base** | `#000000` | `#0f0f0f` |
| **Layout** | Full-page com seções | Sidebar fixa + área de conteúdo |

## Resumo da Sensação Transmitida (Admin):
O painel transmite **autoridade operacional com identidade de marca preservada**. É sério e funcional como um sistema de gestão profissional, mas mantém o DNA Koketsu. O dourado representa **dados, ações e estados ativos** — e agora compartilha espaço com Cobre, Azul Aço e Ametista que enriquecem a interface sem diluir o impacto da cor de marca.
