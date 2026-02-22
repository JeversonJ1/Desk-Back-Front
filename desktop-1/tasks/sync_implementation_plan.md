# Plano de Implementação: Sincronização API -> Desktop (SQLite)

Este plano descreve as etapas para implementar a sincronização de dados entre a API (`localhost:8000`) e o banco de dados local SQLite, com foco em Usuários, Estoque e Pedidos.

## Objetivos
- Sincronizar dados ao iniciar/acessar módulos.
- Endpoint Base: `http://localhost:8000/backend/api/`
- Entidades: `estoque` (Movimentação), `usuarios`, `pedidos`.
- Evitar duplicação de dados (Upsert).

## Passo 1: Configuração do Banco de Dados (SQLite)
A estrutura de banco de dados deve suportar as novas tabelas/campos retornados pela API.

- [ ] **Verificar/Criar Pasta Database**: Garantir que `desktop/database/` exista.
- [ ] **Configurar Conexão**: Criar/Atualizar `database/database.js` e `sqlite-database.cjs`.
- [ ] **Definir Schemas**:
    - `tbl_usuarios`: id_usuarios, nome_usuarios, email_usuarios, nivel_acesso, foto_usuarios.
    - `tbl_estoque_movimentacao`: id_estoque_movimentacao, id_produto, descricao..., quantidade..., data...
    - `tbl_pedidos`: id_pedido, id_perfil, data_pedido, total_pedido, status_pedido, criado_em, atualizado_em, id_usuarios.

## Passo 2: Implementação da Lógica de Sincronização (Database Layer)
Adicionar métodos de sincronização (UPSERT) na camada de dados (`sqlite-database.cjs`).

- [ ] **Método `usuarios.sincronizar(usuario)`**: Inserir ou atualizar usuário.
- [ ] **Método `estoque.sincronizar(movimentacao)`**: Inserir ou atualizar movimentação.
- [ ] **Método `pedidos.sincronizar(pedido)`**: Inserir ou atualizar pedido.

## Passo 3: Atualização dos Handlers (Electron Main Process)
Atualizar os handlers IPC para chamar a API e sincronizar antes de listar.

### 3.1 Usuários (`handlers/clientHandlers.js` ou `authHandlers.js`)
- [ ] **Rota API**: `http://localhost:8000/backend/api/usuarios`
- [ ] **Ação**: Ao carregar lista de usuários ou no login, buscar da API e salvar no SQLite.

### 3.2 Estoque (`handlers/productHandlers.js`)
- [ ] **Rota API**: `http://localhost:8000/backend/api/estoque`
- [ ] **Ação**: Atualizar a lógica existente (que aponta para `estoque_movimentacao` na porta 4000) para a nova rota e estrutura.

### 3.3 Pedidos (`handlers/orderHandlers.js`)
- [ ] **Rota API**: `http://localhost:8000/backend/api/pedidos`
- [ ] **Ação**: Ao listar pedidos, buscar da API e salvar no SQLite.

## Passo 4: Integração Frontend
Garantir que as páginas chamem os handlers corretos.

- [ ] Validar chamadas nas páginas `clientes.html` (ou usuários), `produtos.html`, `pedidos.html`.

---
**Observação**: O sistema deve priorizar a exibição de dados locais caso a API esteja offline, mas tentar a sincronização sempre que possível (Network First ou Stale-While-Revalidate logic adaptada para Desktop).
