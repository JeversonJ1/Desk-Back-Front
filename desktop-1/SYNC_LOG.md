# Registro de Implementação de Sincronização API -> Local

Data: 2026-02-04
Status: Concluído

## Visão Geral
Foi implementada uma camada robusta de sincronização de dados entre a API externa (`http://localhost:8000/backend/api/...`) e o banco de dados local SQLite (`koketsu.db`). Isso permite que a aplicação Electron funcione com dados atualizados e mantenha cache offline.

## Módulos Sincronizados

### 1. Clientes (`clientHandlers.js`)
- **Fluxo**: Ao listar clientes.
- **API Usuários**: `GET /usuarios` -> Atualiza/Insere na `tbl_clientes` (Dados básicos: nome, email).
- **API Perfis**: `GET /perfis` -> Enriquece `tbl_clientes` com o endereço (`endereco_clientes`) baseado no `id_usuarios`.

### 2. Produtos e Relacionados (`productHandlers.js`)
- **Fluxo**: Ao listar produtos. A ordem de execução respeita dependências (Foreign Keys).
1.  **Categorias**: `GET /categorias` -> `tbl_categorias`. (Garante que categorias existam para os produtos).
2.  **Produtos**: `GET /produtos` -> `tbl_produtos`. (Dados principais, preços, estoque).
3.  **Imagens**: `GET /imagens` -> `tbl_imagem`. (Insere imagens extras, tratando URLs relativas).
4.  **Cores**: `GET /cores` -> `tbl_cores`. (Insere catálogo de cores disponíveis).
5.  **Estoque Movimentação**: `GET /estoque_movimentacao` -> `tbl_estoque_movimentacao`. (Histórico de movimentações).

### 3. Pedidos (`orderHandlers.js`)
- **Fluxo**: Ao listar pedidos.
1.  **Pedidos**: `GET /pedidos` -> `tbl_pedidos`. (Cabeçalhos dos pedidos, status, totais e datas).
2.  **Itens de Pedido**: `GET /itens_pedidos` -> `tbl_itens_pedidos`. (Detalhes de cada produto no pedido: quantidade, preço unitário).

## Estrutura de Banco de Dados (`sqlite-database.cjs`)
Novos métodos `sincronizar(objeto)` foram adicionados às APIs de todas as entidades acima.
- **Lógica UPSERT**: Verifica se o registro existe pelo ID original da API. Se existir, atualiza (`UPDATE`); se não, insere (`INSERT`).
- **Tratamento de Falhas**:
    - Campos obrigatórios faltantes na API (ex: `id_produto` em Cores) recebem valores padrão seguros (`1`, `0`, ou strings vazias) para evitar erros de integridade no banco local.
    - URLs de imagens relativas são convertidas para absolutas (`http://localhost:8000/...`).
    - Enums ausentes (ex: `tipo_estoque_movimentacao`) recebem valor default (`'disponivel'`).

## Frontend (`renderer/js`)
- **Timeouts**: Ajustados (ex: `pedidos.js`) para aguardar o tempo extra necessário para a sincronização da rede (15s).

---
**Observação**: O sistema segue o paradigma *Offline-First* com sincronização *Just-in-Time* (ao acessar a listagem). Se a API estiver offline, o sistema exibe os dados salvos localmente na última sincronização bem-sucedida, registrando o erro no log sem travar a aplicação.
