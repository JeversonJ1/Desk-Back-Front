# Relatório Completo de Melhorias - Projeto Koketsu

Este documento consolida a auditoria técnica, de segurança e de design realizada em 10/02/2026.

## 🎨 Design e Experiência do Usuário (Para ficar "Profissional e Bonito")

- [ ] **Tipografia Premium Global**
    - **Problema:** A página inicial (`index.php`) não está importando as fontes `Montserrat` e `Oswald`, dependendo de fontes do sistema.
    - **Ação:** Adicionar `<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">` no `<head>` de **todas** as páginas (ou criar um `header.php` para o site público).
- [ ] **Hero Section (Carrossel) de Impacto**
    - **Problema:** O carrossel atual mostra apenas imagens sem contexto.
    - **Ação:** Adicionar um **Overlay de Texto** e Botões (Call-to-Action) sobre as imagens (ex: "NOVA COLEÇÃO VERÃO - [VER AGORA]"). Usar gradiente preto transparente para garantir leitura do texto sobre a imagem.
- [ ] **Vitrine Dinâmica na Home**
    - **Problema:** A Vitrine atual (`index.php`) usa dados falsos e fixos no arquivo `script.js`. Se você mudar o preço no banco, a Home continua mostrando o preço antigo.
    - **Ação:** Reescrever `script.js` para buscar produtos da API (`/api/produtos`) ou renderizar via PHP, garantindo que a Home mostre produtos reais do banco de dados.
- [ ] **Cards de Produto Unificados**
    - **Problema:** O design dos cards na Home (`script.js`) é diferente e mais simples que o da página de Produtos (`produto.php`).
    - **Ação:** Padronizar tudo usando o design "Premium" de `produto.php` (com sombra neon e efeito de zoom ao passar o mouse).
- [ ] **Barra de Atendimento (Top Bar)**
    - **Sugestão:** A barra amarela sólida pode ser visualmente cansativa. Experimente torná-la preta com ícones e textos amarelos, ou reduzir sua altura para deixá-la mais elegante.

## 🚨 Segurança e Backend (Prioridade Máxima)

- [ ] **Proteger API de Usuários (`PublicApiController.php`)**
    - **CRÍTICO:** A rota `/api/usuarios` expõe senhas e emails de todos os usuários. **Remover ou proteger imediatamente.**
- [ ] **Sanitização de Saída**
    - **Ação:** Garantir que todos os dados exibidos (nome de produto, comentários) passem por `htmlspecialchars()` para evitar ataques XSS.
- [ ] **Criptografia de Senhas**
    - **Verificação:** Confirmar se o cadastro de usuários está usando `password_hash()` e o login `password_verify()`. Nunca salvar senhas em texto puro.

## 🏗️ Arquitetura e Organização

- [ ] **Modularização do Frontend**
    - **Ação:** Criar arquivos `includes/header.php`, `includes/footer.php` e `includes/head.php` para o site público. Atualmente, se você quiser mudar o link do Instagram no rodapé, terá que editar arquivo por arquivo.
- [ ] **Padronização de Rotas**
    - **Ação:** Manter consistência no `rotas.php`. Rotas internas não devem ter o prefixo `/backend` na definição da chave, pois o roteador interno já trata isso.
- [ ] **Feedback de Ações**
    - **Ação:** Implementar "Toasts" (pequenos avisos flutuantes) em vez de `alert()` do navegador quando o cliente adiciona um item ao carrinho. É muito mais profissional.

## 📝 Próximos Passos Sugeridos

1.  **Imediato:** Corrigir a falha de segurança na API de usuários.
2.  **Design:** Atualizar o `index.php` para importar as fontes e modularizar o `footer.php`.
3.  **Funcionalidade:** Conectar a Home (`script.js`) à API real para mostrar produtos verdadeiros.
