# Projeto Apresentacao / Koketsu

Este projeto é uma plataforma de apresentação de produtos (Vitrine Virtual) com um backend em PHP e uma interface administrativa desktop desenvolvida com Electron.

## 📁 Estrutura de Arquivos

A estrutura simplificada do projeto é a seguinte:

```
Apresentacao/
├── api-vitrine.php       # API Endpoint para fornecer produtos ao frontend
├── backend/              # Framework MVC customizado em PHP (API + Admin)
│   ├── Config/           # Configurações (Banco de dados, App)
│   ├── Controles/        # Controladores (Lógica de requisição)
│   ├── Database/         # Classe de conexão com DB
│   ├── Models/           # Modelos de dados
│   ├── Rotas/            # Definição de rotas do backend
│   └── Views/            # Telas do painel administrativo (PHP/HTML)
├── koketsu/              # Aplicação Desktop (Electron + Vite)
│   ├── package.json      # Dependências do Electron
│   └── src/              # Código fonte do app desktop
├── vendor/               # Dependências PHP (Composer)
├── img/                  # Imagens dos produtos e assets
├── css/ & styles/        # Estilos CSS do frontend
├── script.js             # Lógica principal do frontend (Carrossel, Fetch dados)
├── composer.json         # Definição de dependências PHP
└── *.html                # Páginas do site (index, catalogo, carrinho, etc.)
```

## 🚀 Como Executar

### Pré-requisitos
*   PHP 7.4 ou superior.
*   Composer (para dependências do backend).
*   Node.js & NPM (para o app Electron).
*   Banco de Dados (MySQL configurado em `backend/Config`).

### Passos
1.  **Instalar Dependências PHP:**
    ```bash
    composer install
    ```

2.  **Iniciar o Servidor Web:**
    Na raiz do projeto, execute:
    ```bash
    php -S localhost:4000
    ```
    Acesse [`http://localhost:4000`](http://localhost:4000) no navegador.

3.  **Executar o App Desktop (Koketsu):**
    Entre na pasta `koketsu`:
    ```bash
    cd koketsu
    npm install
    npm start
    ```

## 🛠️ Tecnologias Utilizadas

*   **Frontend Web:** HTML5, CSS3, JavaScript (Vanilla), Bootstrap 5.
*   **Backend:** PHP (MVC Customizado), PDO, library `bramus/router`.
*   **Desktop:** Electron, Vite.
*   **Banco de Dados:** MySQL (suporte a SQLite, SQL Server, Postgre configurável).

## 📝 Documentação Técnica e Análise

Para uma visão detalhada sobre a arquitetura, segurança e pontos de atenção do código, consulte o arquivo [TECHNICAL_ANALYSIS.md](./TECHNICAL_ANALYSIS.md).

### Principais Funcionalidades
*   **Vitrine Dinâmica:** Os produtos são carregados via API (`/api/vitrine`), com fallback para dados locais em caso de falha.
*   **Categorização:** Agrupamento automático de produtos por categoria.
*   **Admin Panel:** O diretório `backend` contém a lógica para gerenciar usuários e produtos (acessível via rotas administrativas).

## 💡 Sugestões de Melhoria

1.  **Centralização de Rotas:** Redirecionar todo o tráfego para um router central para evitar endpoints soltos na raiz.
2.  **Segurança:** Restringir o acesso direto a pastas como `backend/` e arquivos de configuração.
3.  **Refatoração do Frontend:** Mover o array de fallback do `script.js` para um arquivo JSON separado para facilitar a manutenção.

---
**Desenvolvido por:** Gregz747
