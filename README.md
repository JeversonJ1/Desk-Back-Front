<div align="center">

# 🛍️ Koketsu — Plataforma de E-commerce de Moda

**Uma solução completa de e-commerce com vitrine web, painel admin e aplicativo desktop.**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Electron](https://img.shields.io/badge/Electron-39%2B-47848F?style=for-the-badge&logo=electron&logoColor=white)](https://www.electronjs.org)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

</div>

---

## 📖 Sobre o Projeto

O **Koketsu** é uma plataforma de e-commerce de roupas desenvolvida do zero com três camadas integradas:

| Camada | Tecnologia | Descrição |
|--------|-----------|-----------|
| 🌐 **Frontend Web** | HTML5, CSS3, JS, Bootstrap 5 | Vitrine virtual para clientes |
| ⚙️ **Backend** | PHP (MVC customizado), PDO | API REST + painel administrativo web |
| 🖥️ **Desktop** | Electron + Vite | App de gestão para operadores |

O projeto **Desk-Back-Front** unifica os três repositórios em uma estrutura coesa, com sincronização de banco de dados entre o app desktop (SQLite local) e o servidor central (MySQL).

---

## ✨ Funcionalidades

### 🛒 Vitrine Web (Frontend)
- Catálogo de produtos com categorias (camisetas, trocas, etc.)
- Carrinho de compras e fluxo de checkout
- Páginas de produto, sobre, dúvidas, política e fidelidade
- Layout responsivo com Bootstrap 5

### 🔧 Painel Administrativo (Backend PHP)
- Arquitetura MVC customizada com roteamento via `bramus/router`
- CRUD completo de produtos, clientes e pedidos
- Suporte a múltiplos bancos de dados: MySQL, SQLite, SQL Server, PostgreSQL
- Autenticação e controle de acesso

### 🖥️ App Desktop (Electron)
- Dashboard com gráficos de vendas (Chart.js)
- Gestão offline com SQLite local
- Sincronização automática com o servidor
- Autenticação com hash SHA-256 e controle de sessões
- Sistema de logs por arquivo (rotação diária)
- Validação centralizada de dados

---

## 🗂️ Estrutura do Projeto

```
Desk-Back-Front/
├── backend/                  # MVC PHP — API + painel admin
│   ├── Config/               # Configurações de app e banco de dados
│   ├── Controles/            # Controllers (lógica de requisição)
│   ├── Database/             # Classe de conexão PDO
│   ├── Models/               # Modelos de dados
│   ├── Rotas/                # Definição de rotas
│   └── Views/                # Telas do painel admin (PHP/HTML)
│
├── frontend/                 # Vitrine web para clientes
│   ├── pages/                # Páginas HTML (catálogo, carrinho, checkout…)
│   ├── api/                  # Chamadas de API do frontend
│   ├── js/                   # Scripts JavaScript
│   ├── assets/ & img/        # Imagens e assets estáticos
│   └── index.html            # Página principal
│
├── desktop/                  # App Electron de gestão
│   ├── electron/
│   │   ├── main.js           # Processo principal (Electron)
│   │   ├── preload.js        # Bridge de segurança (contextBridge)
│   │   ├── config/           # Configuração centralizada
│   │   ├── services/         # logger, authService, validator
│   │   └── handlers/         # authHandlers, productHandlers, clientHandlers, orderHandlers
│   ├── renderer/             # Interface do usuário (HTML/JS)
│   ├── database/             # Banco de dados SQLite local
│   └── vite.config.js        # Build com Vite
│
├── composer.json             # Dependências PHP
└── README.md
```

---

## 🚀 Como Executar

### Pré-requisitos

- [PHP](https://www.php.net/downloads) 7.4 ou superior
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) 18+ e NPM
- Banco de dados MySQL (ou SQLite para desenvolvimento)

---

### 1️⃣ Backend PHP

```bash
# Na raiz do projeto, instale as dependências PHP
composer install

# Copie e configure as variáveis de ambiente
cp .env.example .env
# Edite .env com as credenciais do banco de dados

# Inicie o servidor de desenvolvimento
php -S localhost:4000
```

Acesse [`http://localhost:4000`](http://localhost:4000) no navegador.

---

### 2️⃣ Frontend Web

```bash
cd frontend
npm install
npm run dev
# Acesse http://localhost:8000
```

---

### 3️⃣ App Desktop (Electron)

```bash
cd desktop
npm install

# Popular o banco de dados SQLite local (primeira vez)
npm run seed:sqlite

# Iniciar o app em modo desenvolvimento
npm start
```

> ⚠️ **Importante:** O app utiliza credenciais padrão apenas para fins de desenvolvimento local.
> Antes de qualquer implantação em produção, altere as credenciais no arquivo
> `desktop/electron/services/authService.js` e configure variáveis de ambiente adequadas.

---

## 🛠️ Stack Tecnológica

| Área | Tecnologias |
|------|------------|
| **Frontend** | HTML5, CSS3, JavaScript (Vanilla), Bootstrap 5, Bootstrap Icons |
| **Backend** | PHP 7.4+, PDO, `bramus/router`, `phpmailer/phpmailer`, `vlucas/phpdotenv` |
| **Desktop** | Electron 39, Vite 7, `better-sqlite3`, Chart.js 4 |
| **Banco de Dados** | MySQL (produção), SQLite (desktop/local) |
| **Autenticação** | Sessões com expiração de 24h (hash SHA-256 — considere bcrypt/Argon2 em produção) |

---

## 🔒 Segurança

- Hash de senhas com SHA-256 (⚠️ considere migrar para bcrypt ou Argon2 para produção)
- Gerenciamento de sessões com expiração automática
- Middleware de autenticação em todos os handlers IPC
- DevTools desabilitado em modo de produção
- Validação centralizada de entradas (produtos, clientes, pedidos)

---

## 📊 Scripts Disponíveis (Desktop)

| Comando | Descrição |
|---------|-----------|
| `npm start` | Inicia o app em modo desenvolvimento |
| `npm run build` | Compila o projeto com Vite |
| `npm run seed:sqlite` | Popula o banco SQLite com dados de exemplo |
| `npm run perf:db` | Benchmark de performance do banco de dados |
| `npm run perf:render` | Benchmark de performance de renderização |

---

## 🗺️ Roadmap

- [x] Vitrine web responsiva
- [x] Backend PHP com MVC customizado
- [x] App Electron com gestão offline
- [x] Sincronização desktop ↔ servidor
- [x] Autenticação com sessões seguras
- [x] Sistema de logs centralizado
- [ ] Testes unitários e de integração
- [ ] Pipeline CI/CD (GitHub Actions)
- [ ] Two-factor authentication (2FA)
- [ ] Backup automático do banco de dados
- [ ] Rate limiting no login

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Siga os passos abaixo:

1. Faça um **fork** do repositório
2. Crie uma branch para sua feature: `git checkout -b feature/minha-feature`
3. Commit suas mudanças: `git commit -m 'feat: adiciona minha feature'`
4. Faça o push: `git push origin feature/minha-feature`
5. Abra um **Pull Request**

---

## 📄 Licença

Distribuído sob a licença **MIT**. Veja [`LICENSE`](LICENSE) para mais detalhes.

---

<div align="center">
  Desenvolvido com ❤️ por <strong>JeversonJ1</strong>
</div>
