# Sistema de Avaliações Funcional — Koketsu

O sistema de avaliações já possui uma base (tabela no banco, Model, Controller, proxy frontend e JS), mas apresenta **lacunas críticas** que impedem seu funcionamento completo end-to-end. Este plano fecha todas as pontas.

---

## Diagnóstico Atual

| Camada | Estado | Problemas |
|--------|--------|-----------|
| **Banco** (`tbl_avaliacoes`) | ✅ Funcional | Tabela existe, dados de seed presentes, constraint 1-5 ok |
| **Model** ([Avaliacao.php](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/backend/Models/Avaliacao.php)) | ✅ Funcional | CRUD completo, JOINs corretos, stats por produto |
| **Controller** ([PublicApiController.php](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/backend/Controles/PublicApiController.php#L764-L895)) | ⚠️ Parcial | Métodos existem mas `createPublicAvaliacao` não valida duplicatas nem verifica compra |
| **Proxy Frontend** ([avaliacoes.php](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/frontend/api/avaliacoes.php)) | ⚠️ Parcial | Roteamento GET ok, POST falta iniciar sessão para validar autenticação |
| **JS Frontend** ([reviews.js](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/frontend/assets/js/reviews.js)) | ⚠️ Parcial | Lógica de render e submit existem, mas auth check usa `id` como `id_usuarios` sem mapear para `id_perfil` corretamente |
| **Página de Produto** ([produto.html](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/frontend/pages/produto.html#L156-L187)) | ✅ Funcional | Estrutura HTML presente com form, tabs e star rating |
| **Home Reviews** ([index.html](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/frontend/index.html#L219-L261)) | ⚠️ Conflito | Dois sistemas competem: o inline script da home e o `ReviewManager.initHomeCarousel()`. O inline usa array direto, o ReviewManager espera `{status, data}` |

---

## Proposed Changes

### Backend — Model

#### [MODIFY] [Avaliacao.php](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/backend/Models/Avaliacao.php)

- **Adicionar `verificarAvaliacaoExistente($id_produto, $id_cliente)`**: Query que checa se o cliente já avaliou aquele produto (evita duplicatas).
- **Adicionar `verificarCompraConfirmada($id_produto, $id_perfil)`**: JOIN em `tbl_pedidos` + `tbl_itens_pedidos` para garantir que o cliente comprou o produto com status `concluido` ou `pago` antes de permitir avaliação.

---

### Backend — Controller

#### [MODIFY] [PublicApiController.php](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/backend/Controles/PublicApiController.php)

No método `createPublicAvaliacao()` (linha ~850):
- Adicionar validação: **nota entre 1 e 5**
- Adicionar verificação de **avaliação duplicada** (usar novo método do Model)
- Adicionar verificação de **compra confirmada** (usar novo método do Model)
- Sanitizar comentário com `htmlspecialchars` antes de salvar
- Melhorar mensagens de erro para cada caso

---

### Frontend — Proxy PHP

#### [MODIFY] [avaliacoes.php](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/frontend/api/avaliacoes.php)

- Adicionar `session_start()` no topo para que o POST possa acessar sessão do usuário logado (necessário pois o `createPublicAvaliacao` valida `id_usuarios`)
- Adicionar suporte a `OPTIONS` para CORS preflight (método defensivo)

---

### Frontend — JavaScript

#### [MODIFY] [reviews.js](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/frontend/assets/js/reviews.js)

- **Corrigir `initProductReviews`**: Tratar resposta tanto como `{status, data}` quanto array direto (compatibilidade)
- **Corrigir `checkUserAbility`**: Mostrar mensagem quando o usuário está logado mas NÃO comprou o produto
- **Corrigir `setupForm`**: Garantir que o `highlightStars` inicializa com 5 estrelas preenchidas
- **Unificar home reviews**: Remover lógica duplicada no inline script da home, delegar tudo ao `ReviewManager.initHomeCarousel()`

#### [MODIFY] [index.html](file:///c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/frontend/index.html)

- **Remover o inline script** (linhas ~477-541) que compete com `ReviewManager.initHomeCarousel()`, substituindo por uma chamada simples ao `ReviewManager`
- Manter a seção HTML e estilos CSS intocados

---

## Open Questions

> [!IMPORTANT]
> **Restrição de avaliação por compra**: Quer que apenas clientes que compraram o produto (com pedido `pago` ou `concluido`) possam avaliar? Ou qualquer cliente logado pode avaliar qualquer produto?

> [!NOTE]
> **Avaliação duplicada**: Se um cliente já avaliou um produto, o comportamento deve ser bloquear uma segunda avaliação, ou permitir editar a avaliação existente?

---

## Verificação

### Testes Manuais
1. **Fluxo completo sem login**: Acessar página de produto → verificar que mostra "Faça login para avaliar"
2. **Fluxo com login**: Logar → acessar produto → enviar avaliação com nota e comentário → verificar que a avaliação aparece na lista
3. **Home page**: Verificar que as últimas avaliações carregam corretamente na seção da home
4. **Duplicata**: Tentar enviar segunda avaliação para o mesmo produto → verificar que bloqueia
5. **Validação**: Tentar enviar sem nota ou com nota inválida → verificar mensagem de erro
