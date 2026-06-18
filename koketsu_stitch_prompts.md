# 🐯 Koketsu Grife — Prompts para Google Stitch

Acesse: **stitch.withgoogle.com/create** → New Project → "Koketsu Grife"

---

## ⚙️ CONFIGURAÇÕES DO PROJETO (antes de gerar telas)

Ao criar o projeto, selecione:
- **Mode:** Dark
- **Font:** Montserrat
- **Roundness:** Full (pill)
- **Color:** `#F2C84B` (dourado)
- **Device:** Desktop

---

## 🖥️ TELA 1 — Homepage

```
Homepage da Koketsu Grife, e-commerce premium de streetwear masculino. Dark mode com fundo #000000, accent dourado #F2C84B, fontes Montserrat + Oswald em caixa alta.

Seções:
1. NAVBAR fixa: Logo "KOKETSU GRIFE" com tigre dourado à esquerda, menu central (HOME, CATÁLOGO, SOBRE, CONTATO), ícones busca/carrinho/conta à direita. Fundo preto com glassmorphism.

2. HERO BANNER: Fundo escuro com modelo masculino streetwear. Título "NOVA COLEÇÃO 2026" em branco, Montserrat Black, all-caps. Botão CTA dourado "VER COLEÇÃO" e botão outline "VER CATÁLOGO COMPLETO".

3. BARRA DE BENEFÍCIOS: 4 cards escuros (#111) horizontais com ícone dourado + título branco bold + texto cinza: Entrega Expressa | Compra Segura | Troca Fácil | Atendimento.

4. NOSSAS CATEGORIAS: Título "NOSSAS CATEGORIAS" com shimmer dourado. Grid 4 colunas: cards com foto + overlay gradiente escuro + nome da categoria uppercase + "Ver Coleção →" dourado no hover. Categorias: Camisetas, Moletons, Calças, Tênis.

5. MAIS VENDIDOS: Grid 4 colunas de product cards escuros (#111): imagem produto em fundo claro (pop-out effect), nome em branco bold, preço em dourado #F2C84B, botão "ADICIONAR AO CARRINHO" dourado.

6. BRAND STORY: Seção escura com frase em itálico: "O essencial é invisível aos olhos". Parágrafo sobre a marca Koketsu.

7. AVALIAÇÕES REAIS: Título "AVALIAÇÕES REAIS" + nota 5.0 em estrelas douradas. Carrossel de review cards com avatar inicial, nome, estrelas douradas, comentário em itálico, badge "Compra Verificada".

8. FOOTER: Fundo #111, logo, links de navegação, redes sociais, copyright Koketsu Grife © 2026.
```

---

## 🔐 TELA 2 — Login / Cadastro

```
Página de Login e Cadastro da Koketsu Grife. Dark mode, fundo #000000, accent dourado #F2C84B, Montserrat.

Layout centralizado vertical e horizontal com fundo preto e textura sutil.

1. NAVBAR: Logo Koketsu + links + ícones. Fundo preto glassmorphism.

2. CARD PRINCIPAL (fundo #111, borda branca/10%, rounded-2xl, sombra intensa):
   - Logo tigre dourado centralizado no topo
   - Título: "Acesse ou crie sua conta" em branco bold
   - Input de E-mail/CPF: fundo preto, borda cinza escura, focus com borda dourada e glow
   - Botão primário "CONTINUAR": fundo dourado #F2C84B, texto preto, uppercase, full width, rounded, sombra dourada
   - Divisor "OU" com linhas
   - Botão Google: fundo branco, texto preto, ícone Google, full width

3. CARD CONSULTA DE PEDIDO (fundo #0a0a0a, borda cinza, rounded-xl):
   - Título "Consulte o seu pedido" em branco bold
   - Subtítulo "Verifique informações detalhadas sobre o seu pedido" em cinza
   - Botão outline dourado "CONSULTAR MEU PEDIDO"

4. Texto legal em cinza xs: Política de Privacidade, reCAPTCHA, etc.
5. Logo Koketsu pequeno + "Desenvolvido por Techalpha" em cinza.

Efeitos: glow dourado no botão principal, hover translateY nos botões.
```

---

## 🛍️ TELA 3 — Catálogo de Produtos

```
Página de Catálogo da Koketsu Grife. Dark mode, #000 fundo, accent dourado #F2C84B.

1. NAVBAR fixa.

2. HERO DA PÁGINA: Fundo #111, título "DESCUBRA SEU ESTILO" em shimmer dourado, subtítulo cinza "Peças exclusivas para quem domina a cidade com elegância e atitude".

3. BREADCRUMB: HOME / CATÁLOGO em uppercase 10px, última parte em dourado.

4. LAYOUT 2 COLUNAS:

SIDEBAR FILTROS (25%):
- Card #111, borda branca/5%, rounded-2xl, sticky
- Header: "FILTROS" com ícone sliders dourado + botão "Limpar Tudo" cinza
- Seção CATEGORIAS (collapsível): checkboxes Camisetas, Moletons, Calças, Tênis, Calçados
- Seção TAMANHOS: grid de tags P/M/G/GG/XG em pill, selecionado = fundo dourado texto preto
- Seção CORES: círculos coloridos (preto, branco, cinza, verde, azul, vinho), selecionado com borda dourada
- Seção FAIXA DE PREÇO: range slider dourado, R$0 até R$1.000

GRADE DE PRODUTOS (75%):
- Toolbar: "EXIBINDO 24 PRODUTOS" uppercase + dropdown "Ordenar: Lançamentos"
- Grid 4 colunas de product cards (#111, rounded-2xl):
  * Imagem produto em fundo claro (efeito pop-out)
  * Badge "NOVO" outline dourado
  * Nome do produto branco bold uppercase
  * Preço atual dourado grande
  * Preço original riscado cinza
  * Botão "Adicionar" dourado cheio
  * Hover: borda dourada + translateY(-4px)
- 8 cards com produtos variados

5. PAGINAÇÃO: Números com página ativa em dourado, prev/next com borda cinza.
6. FOOTER.
```

---

## 👕 TELA 4 — Página de Produto

```
Página de Produto da Koketsu Grife. Dark mode, #000 fundo, accent dourado #F2C84B.

1. NAVBAR fixa.

2. BREADCRUMB: HOME / CAMISETAS / CAMISETA KOKETSU OVERSIZED PREMIUM

3. LAYOUT 3 COLUNAS:

MINIATURAS VERTICAIS (esquerda, 64px): 4 thumbs empilhadas, borda dourada na ativa.

IMAGEM PRINCIPAL (centro): Container aspect 4/5, fundo #0a0a0a, imagem de camiseta oversized preta em fundo branco (efeito pop-out). Cursor zoom-in. Miniaturas horizontais embaixo (mobile).

INFORMAÇÕES DO PRODUTO (direita, 320px):
- Nome: "CAMISETA KOKETSU OVERSIZED PREMIUM" Montserrat Black, branco, all-caps
- Estrelas: 4.8 estrelas douradas + "(42 avaliações)" cinza, link para reviews
- Card de preço (#0a0a0a, borda branca/5%):
  * Preço: R$ 189,90 em dourado 3xl bold
  * Preço original riscado: R$ 239,90 cinza
- Seção COR: label "Cor:", 3 círculos (preto selecionado com borda dourada, branco, cinza)
- Seção TAMANHO: grid P/M/G/GG/XG — selecionado = fundo dourado, texto preto, glow
- Botões:
  * Contador quantidade: [−] [1] [+] com borda cinza
  * "ADICIONAR AO CARRINHO" dourado cheio, ícone bag-plus, uppercase, full width, glow dourado

4. TABS DE INFORMAÇÕES:
- "Descrição" (ativa, sublinha dourada) | "Avaliações (42)"
- Conteúdo: texto de descrição cinza/branco

5. PRODUTOS RELACIONADOS: "QUEM VIU, TAMBÉM VIU" + linha dourada. Grid 4 mini cards.

6. FOOTER.
```

---

## 🛒 TELA 5 — Carrinho

```
Página de Carrinho da Koketsu Grife. Dark mode, #000 fundo, accent dourado #F2C84B.

1. NAVBAR fixa.

2. HEADER:
- Título "MEU CARRINHO" em dourado #F2C84B, Montserrat ExtraBold, uppercase, text-glow dourado
- Linha divisora dourada (pill)
- Texto informativo cinza: "A Koketsu funciona como uma vitrine visual. Selecione seus itens e finalize via WhatsApp para atendimento exclusivo."

3. LAYOUT 2 COLUNAS:

TABELA DE ITENS (70%):
Card #111, rounded-2xl, borda branca/5%:
- Header: PRODUTO | PREÇO | QUANTIDADE | SUBTOTAL | (ação) — cinza uppercase 11px
- 3 linhas de itens:
  * Imagem 64x80px arredondada + nome clicável + "Tamanho: M" cinza
  * Preço unitário branco
  * Contador quantidade [-][n][+]
  * Subtotal em dourado bold
  * Botão X vermelho no hover

RESUMO DO PEDIDO (30%):
Card #111, sticky, rounded-2xl:
- Título "Resumo do Pedido" branco bold
- Subtotal | R$ 569,70
- Frete | A Calcular (cinza)
- Divisor
- TOTAL | R$ 569,70 em dourado 3xl
- Botão "FINALIZAR PEDIDO" com ícone WhatsApp verde, fundo dourado, texto preto, uppercase, glow
- Link "← Continuar Comprando" cinza centralizado

4. ESTADO VAZIO: Ícone bag-x grande cinza, "Seu carrinho está vazio", botão "VOLTAR À LOJA" dourado.

5. FOOTER.
```

---

## 💳 TELA 6 — Checkout

```
Página de Checkout da Koketsu Grife. Dark mode, #000 fundo, accent dourado #F2C84B.

1. NAVBAR fixa.

2. PROGRESS BAR: CARRINHO → DADOS → PAGAMENTO → CONFIRMAÇÃO
Etapa atual "DADOS" em dourado, etapas anteriores com ícone check dourado.

3. LAYOUT 2 COLUNAS:

FORMULÁRIO (60%):
Card #111, rounded-2xl:

Seção "DADOS PESSOAIS":
- Input: Nome completo
- Input: E-mail
- Input: CPF/CNPJ
- Input: Telefone/WhatsApp
Todos: fundo #0f0f0f, borda #222, focus borda dourada + glow dourado

Seção "ENDEREÇO DE ENTREGA":
- Input: CEP + botão "BUSCAR" outline dourado
- Input: Rua
- Row: Número | Complemento
- Row: Bairro | Cidade | Estado

Seção "FORMA DE PAGAMENTO":
3 cards selecionáveis: Cartão de Crédito | PIX | Boleto
Selecionado: borda dourada, fundo rgba(242,200,75,0.07)
Form cartão: Número, Nome, Validade, CVV

Botão "FINALIZAR PEDIDO" dourado cheio, uppercase, glow, full width.

RESUMO DO PEDIDO (40%):
Card sticky #111, rounded-2xl:
- Título "Resumo do Pedido"
- 3 itens: thumb + nome + tamanho + preço dourado
- Divisor
- Subtotal | Frete | Desconto | TOTAL em dourado 2xl
- Input cupom escuro + botão "APLICAR" outline dourado
- Selos: SSL + bandeiras de pagamento

4. FOOTER.
```

---

## 🖥️ TELA 7 — Dashboard Admin (Painel Desktop)

```
Painel Administrativo da Koketsu Grife. Dark mode profissional, fundo #0f0f0f, accent dourado #F2C84B, fontes Arial/Segoe UI. Este é o sistema de gestão interno (app desktop Electron).

1. SIDEBAR FIXA (260px, gradiente vertical #000 → #111, borda direita #222):
- Logo: tigre dourado + "KOKETSU" bold + "ADMIN" em dourado pequeno
- Menu vertical:
  * Dashboard (ATIVO: texto dourado, fundo rgba(242,200,75,0.07), borda esquerda 3px dourada)
  * Produtos | Pedidos | Clientes | Categorias | Relatórios | Configurações
  * Inativos: texto #888, hover texto branco
- Rodapé sidebar: avatar + nome admin + botão sair

2. ÁREA PRINCIPAL (fundo #0f0f0f, padding 40px):

HEADER:
- Título "Dashboard" branco bold + data atual
- Barra de busca escura + botões de ação rápida

KPI CARDS (4 em linha):
Card 1 RECEITA: gradiente dourado→cobre (#F2C84B→#C47A3A), "R$ 48.290", +12% verde
Card 2 PEDIDOS: gradiente azul aço (#4E9EBF→azul escuro), "247", +8% verde
Card 3 PRODUTOS: gradiente ametista (#8B5CF6→roxo), "89 ativos"
Card 4 USUÁRIOS: gradiente verde (#51cf66→verde escuro), "1.247", +23% verde
Cada card: borda 2px #222, rounded-2xl, gradiente #1a1a1a→#0f0f0f, sombra 0 4px 12px rgba(0,0,0,0.3)

TABELA PEDIDOS RECENTES:
Título + badge count em dourado
Thead: fundo #1a1a1a, texto dourado uppercase, letter-spacing
Colunas: # | Cliente | Produto | Valor | Status | Data | Ações
5 linhas com badges: "PAGO" verde | "PENDENTE" laranja | "ENVIADO" azul aço
Botões ação: olho/editar/excluir
Hover: background rgba(242,200,75,0.07)

GRID INFERIOR (2 colunas):
- Gráfico linha vendas 7 dias (linha dourada, área preenchida sutil)
- Top 5 produtos mais vendidos com barra progresso dourada
```

---

## 💡 Dica

No Stitch, ao criar cada tela:
1. Clique em **"New Screen"** ou **"Generate from text"**
2. Cole o prompt da tela desejada
3. Selecione **Desktop** como device
4. Aguarde a geração (~2 min por tela)
