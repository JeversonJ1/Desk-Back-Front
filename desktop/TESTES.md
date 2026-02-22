# 🧪 Guia de Testes - Koketsu Desktop

Este documento descreve testes manuais rápidos para validar as principais
funcionalidades do app Desktop.

---

## 1) Login

- **Login válido**
  - Usuário: `admin`
  - Senha: `admin123`
  - **Esperado:** redirecionar para `dashboard.html`

- **Login inválido**
  - Usuário: `admin`
  - Senha: `senha123`
  - **Esperado:** mensagem de erro

- **Campos vazios**
  - **Esperado:** alerta de preenchimento obrigatório

---

## 2) Sessão

- **SessionId salvo**
  - Verifique no DevTools: `localStorage.getItem('sessionId')`

- **Página protegida sem sessão**
  - Execute no DevTools: `localStorage.clear(); location.reload();`
  - **Esperado:** redirecionar para login

---

## 3) Produtos

- **Listar produtos**
  - `await window.api.listarProdutos(sessionId)`
  - **Esperado:** lista com produtos cadastrados

- **Criar produto inválido**
  - Nome vazio ou preço negativo
  - **Esperado:** erro com mensagem descritiva

- **Criar produto válido**
  - Exemplo:
    ```javascript
    await window.api.criarProduto(sessionId, {
      nome: 'Camiseta Teste',
      preco: 49.9,
      estoque: 100,
      categoria: 'CAMISETA'
    });
    ```
  - **Esperado:** `{ sucesso: true, id: X, produto: {...} }`

---

## 4) Validações

- **Nome curto**: "A" → **erro**
- **Email inválido**: "email_sem_arroba" → **erro**
- **Telefone inválido**: "123" → **erro**

---

## 5) Logs

- Arquivos em `desktop/storage/logs/`
- Formato: `app-YYYY-MM-DD.log`
- **Esperado:** logs de login e operações CRUD

---

## 6) Configurações

- **Salvar tema** e recarregar
- **Resetar configurações** mantém credenciais

---

## 7) Segurança (Electron)

- `require('fs')` no Renderer deve falhar
- `window.api` deve estar disponível

---

## 8) Performance (opcional)

- Use os scripts em `tools/perf/` para benchmarks
