---
name: Koketsu Grife
colors:
  primary: "#F2C84B"
  secondary: "#1A1A1A"
  background: "#000000"
  neutral-light: "#FFFFFF"
  neutral-dim: "#888888"
  accent-copper: "#C47A3A"
  accent-steel: "#4E9EBF"
  accent-amethyst: "#8B5CF6"
  status-success: "#51CF66"
  status-warning: "#FFA94D"
  status-danger: "#DC3545"
typography:
  fontFamily: Montserrat, Oswald, sans-serif
  headings:
    fontFamily: Oswald
    fontWeight: 900
    textTransform: uppercase
  body:
    fontFamily: Montserrat
    fontWeight: 400
rounded:
  sm: 4px
  md: 10px
  lg: 16px
  full: 9999px
spacing:
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 40px
---

# Koketsu Grife - Premium Streetwear Visual Identity

This document defines the design tokens and visual guidelines for the Koketsu Grife e-commerce platform and its administrative panel (dashboard).

## 1. Aesthetic Philosophy
- **Premium Urban Noir**: A dark, mysterious, and high-end street-fashion visual tone.
- **High Contrast**: Pure black background (`#000000`) contrasted with sharp white headings, glowing gold details, and off-white/light gray product imagery.
- **Tension & Energy**: Diagonal lines, sharp outlines, custom neon/gold glows, and clean cards with smooth transitions.

## 2. Component Guidelines
- **Cards**: Background: `#1A1A1A` or dark gray gradients (`#1A1A1A` to `#0F0F0F`). Borders: subtle `1px solid rgba(255, 255, 255, 0.05)`. Hover states should elevate the cards (`translateY(-4px)`) and highlight them with a thin gold border (`#F2C84B`) or glow effect.
- **Buttons (Primary)**: Oval pills (`rounded-full`), solid gold background (`#F2C84B`), uppercase bold black text. Include a subtle golden glow (`box-shadow: 0 4px 12px rgba(242, 200, 75, 0.3)`).
- **Buttons (Secondary/Outline)**: Transparent background with `#F2C84B` gold border and text.
- **Inputs**: Dark background (`#0F0F0F`), subtle borders, focused states highlight in gold with a small shadow glow.
- **Product Images**: Clothes and shoes must have light gray or off-white backgrounds inside the dark cards to create a "pop-out" contrast effect, catching the customer's eyes immediately.

## 3. Brand Accents (Admin Dashboard)
While the storefront uses gold as its main accent, the administrative panel (designed as a functional desktop app) utilizes semantic accents to keep the user interface structured and intuitive:
- 🟡 **Gold (`#F2C84B`)**: Interaction, active navigation menu, key values, active filters, table headers.
- 🟤 **Copper (`#C47A3A`)**: Revenue, financial stats, high-value inventory.
- 🔵 **Steel Blue (`#4E9EBF`)**: General information, active orders count, logistics.
- 🟣 **Amethyst (`#8B5CF6`)**: Admin access, user roles, system metrics.
- 🟢 **Success (`#51CF66`)**: High growth indicators, paid order statuses.

---

## 4. Platform Screen Specifications

### TELA 1 — Homepage (Storefront)
A visually stunning hero page displaying the brand's identity, key collections, benefits, and social reviews.
- **Navbar**: Sticky, transparent glassmorphism background (`backdrop-filter`). Golden Tiger logo left, centered link options (HOME, CATÁLOGO, SOBRE, CONTATO), search & cart icons right.
- **Hero Banner**: Bold uppercase "NOVA COLEÇÃO 2026" headline in Montserrat Black. Dark streetwear model showcase. CTA "VER COLEÇÃO" in gold and outline "VER CATÁLOGO COMPLETO".
- **Benefits Bar**: 4 minimalist dark cards showing gold outline icons for: Free Delivery, Secure Purchase, Easy Exchange, Premium Support.
- **Categories Grid**: 4 columns (Camisetas, Moletons, Calças, Tênis) with dark gradient overlays, uppercase names, and golden arrows on hover.
- **Featured Products**: 4 columns of cards. Light gray image backgrounds, gold price tags, and hover elevation.
- **Reviews**: Gold star ratings, customer comment block, "Compra Verificada" verified badges.

### TELA 2 — Login & Cadastro
A clean, secure, and focused center-card authenticator.
- **Container**: Minimal black canvas, gold tiger badge at the top.
- **Forms**: Dual layout options for returning users and password recovery.
- **Buttons**: Gold primary button with glow, Google sign-in secondary option.
- **Footer Section**: Order lookup link ("Consulte o seu pedido") inside a dark gray card for non-authenticated buyers.

### TELA 3 — Catálogo de Produtos
A highly usable product directory with robust category refinement.
- **Breadcrumb**: Navigation indicator using small golden accents (`HOME / CATÁLOGO`).
- **Sidebar Filters**: Vertical accordion cards containing category checkboxes, size pills (P, M, G, GG), color circles (with gold borders on active state), and a golden price range slider.
- **Product Grid**: 4-column cards with "NOVO" tag badges, hover scaling, gold shopping bag icons, and instant cart additions.

### TELA 4 — Página de Produto
A detailed product catalog view highlighting textures and features.
- **Visuals**: Vertical thumb gallery left, high-contrast centered main showcase (product with off-white backdrop), product details right.
- **Controls**: Quantity selectors (`[-] 1 [+]`), size buttons, color rings, and a full-width golden "ADICIONAR AO CARRINHO" button.
- **Tabs**: Smooth horizontal tab menu (Descrição / Avaliações) with golden underlines on active elements.

### TELA 5 — Carrinho
A clear overview of selected items with a primary action funnel.
- **Disclaimer**: Notice informing that the store works as an exclusive digital showcase, routing final purchases smoothly to personal WhatsApp agents.
- **Table**: Rounded dark card containing item thumbnails, quantities, gold subtitles, and delete triggers.
- **Summary**: Total counter on a sticky sidebar with a bright "FINALIZAR PEDIDO VIA WHATSAPP" call-to-action utilizing WhatsApp green accents combined with brand styling.

### TELA 6 — Checkout
A secure payment/shipping collection form.
- **Steps**: Progress timeline header showing active step with gold checkmarks.
- **Form Layout**: 2 columns. Left: Shipping addresses, contact details, payment selection cards (Credit, Pix, Boleto) highlighting in gold with background dim colors. Right: Coupon entry, items summary, trust Badges.

### TELA 7 — Dashboard Admin (Desktop Management)
A professional desktop electron-style web dashboard for store statistics.
- **Sidebar**: Fixed 260px left column in pure black gradient. Gold branding icon, vertical option links (Dashboard, Produtos, Pedidos, Clientes, Relatórios). Active option has a golden accent stripe.
- **KPI Cards**: Row of 4 gorgeous status boxes showing: Revenue (Gold to Copper gradient), Orders (Steel to dark blue gradient), Products (Amethyst to dark purple), Growth (Green gradient).
- **Recent Orders Table**: Black surface, golden header text, action items (view/edit/delete), and status badges (Pago in green, Pendente in orange, Enviado in steel).
- **Analytics Charts**: Gold line charts showing a 7-day sales graph, and progress bars highlighting top selling products.
