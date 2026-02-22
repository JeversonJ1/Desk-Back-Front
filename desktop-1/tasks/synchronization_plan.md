# Plano de Sincronização Local <-> Remoto

Este arquivo detalha os passos para ajustar o cadastro do frontend, salvar localmente e sincronizar com a API remota via POST.

## 1. Análise e Preparação (Atual)
- [x] Analisar `database/sqlite-connection.js` para confirmar esquema das tabelas.
- [x] Mapear rotas de API existentes em `electron/services/apiService.js`.
- [x] Identificar lacunas na criação de dados (Frontend -> Handler -> DB Local).

## 2. Sincronização de Clientes
- [x] Ajustar `renderer/pages/clientes.html` e `clientes.js` para refletir `tbl_clientes` (precisa verificar se tabela existe, pois não estava no dump anterior).
- [x] Criar/Atualizar Handlers de Clientes (`electron/handlers/clientHandlers.js`) para salvar no SQLite.
- [x] Implementar `createCliente` em `apiService.js`.
- [x] Implementar `checkAndSendClientes` em `syncService.js` para enviar novos clientes (sincronizado = 0).

## 3. Sincronização de Produtos
- [x] Ajustar `renderer/pages/produtos.html` e `produtos.js` para refletir `tbl_produtos`.
- [x] Garantir que Handlers de Produtos salvem campos corretos no SQLite.
- [x] Implementar `createProduto` em `apiService.js`.
- [x] Implementar `checkAndSendProdutos` em `syncService.js` para enviar novos produtos.

## 4. Sincronização de Pedidos
- [x] Ajustar `renderer/pages/pedidos.html` e `pedidos.js` para refletir `tbl_pedidos` e `tbl_itens_pedidos`.
- [x] Garantir que Handlers de Pedidos salvem corretamente no SQLite.
- [x] Implementar `createPedido` em `apiService.js`.
- [x] Implementar `checkAndSendPedidos` em `syncService.js`. para enviar novos pedidos.

## 5. Sincronização de Estoque/Movimentação
- [x] Verificar se há tela de lançamento de estoque.
- [x] Implementar sincronização de movimentações se necessário.
- [x] Implementar `checkAndSendEstoque`.

## 6. Extras
- [x] Implementar Soft Delete (coluna `deletado` e lógica de sincronização de exclusão).
