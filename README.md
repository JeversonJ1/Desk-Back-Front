# Koketsu (Desk-Back-Front) 🏗️

Este projeto é um Sistema E-commerce completo com uma arquitetura tripla: uma **plataforma de apresentação de produtos (Vitrine Virtual Web)**, um **backend robusto em PHP MVC** e uma **interface administrativa Desktop** desenvolvida em Electron.

Este documento mapeia a arquitetura e serve como a **fonte da verdade** para regras de desenvolvimento.

---

## 🗺️ Mapa de Diretórios e Estrutura

```text
Desk-Back-Front/
├── backend/       # ⚙️ API REST em PHP (Core do Sistema)
│   ├── Config/    # Configurações de Banco de Dados, Email e Variáveis
│   ├── Controles/ # Controllers: Lógica de Negócio e roteamento de requisições
│   ├── Core/      # Classes base do framework e roteador customizado
│   ├── Database/  # Conexões e migrações do banco
│   ├── Models/    # Representação abstrata das Tabelas do DB
│   ├── Rotas/     # `rotas.php` que direcionam endpoints para os Controles
│   ├── Validadores/# Classes isoladas de validação de dados
│   ├── Views/     # Templates em formato PHP (caso haja render SSR)
│   └── index.php  # Ponto de Entrada da API
│
├── frontend/      # 🌐 Aplicação Web (E-commerce / Painel Admin)
│   ├── api/       # Chamadores AJAX e formatadores para comunicação com o Backend
│   ├── assets/    # Ícones, Fontes, CSS Global
│   ├── img/       # Imagens locais do site
│   ├── js/        # Scripts JS globais
│   ├── pages/     # Páginas HTML moduladas do site
│   ├── koketsu/   # Aplicação Electron paralela (Estrutura dependente do Vite)
│   ├── index.html # Ponto principal do WebApp
│   └── style.css  # Folha de estilos base
│
├── desktop/       # 💻 Aplicação Electron (Interface para Desk)
│   ├── electron/  # Scripts Main Process e Preload (Acesso ao SO)
│   ├── renderer/  # Scripts locais do frontend da aplicação Desktop
│   ├── css/       # Estilização
│   └── index.html # Arquivo matriz do Desktop
│
├── database/      # 🗄️ Modelos e Dumps de Banco de Dados
│   └── koketsu.sql# Exportação do estado e estrutura do Banco Relacional
│
├── scripts/       # 🛠️ Scripts isolados para manutenção (Limpar DB, Fixes, etc)
│
└── tests/         # 🧪 Testes de unidade e scripts de debug (Conexão, SQLite, etc)
```

---

## 🚀 Como Executar

### Pré-requisitos
* PHP 7.4 ou superior
* Composer
* Node.js & NPM
* Banco de Dados MySQL configurado via `.env`

### Passos
1. **Instalar Dependências PHP:**
   ```bash
   composer install
   ```
2. **Iniciar o Backend PHP:**
   Na raiz do projeto (importante estar na raiz):
   ```bash
   php -S localhost:8000 server.php
   ```
3. **Executar a Aplicação Desktop (Koketsu - Electron):**
   ```bash
   npm run dev
   ```

---

## 🏛️ Arquitetura e Fluxo (PHP MVC)

O núcleo do sistema opera em um padrão robusto de MVC.

### Fluxo de Requisição
1. **Entry Point (`backend/index.php`):** Inicializa sessão, carrega `.env` (Dotenv) e instancia o Router.
2. **Router (`backend/Rotas/rotas.php`):** Mapeia a URI para um `Controller@metodo` usando o Bramus Router.
3. **Controller (`backend/Controles/`):** Valida a requisição, chama o *Model* para persistência/captura e devolve dados para a *View* ou em formato JSON para as APIs (`PublicApiController.php`).
4. **Model (`backend/Models/`):** Executa queries em PDO no Banco de Dados (`Database/database.php`). Utiliza *Soft Delete* nativamente.

**Segurança:** Utiliza validação global de CSRF, Sanitização Global em index.php e *Prepared Statements* via PDO.

---

## 📋 Regras de Desenvolvimento

Para manter a consistência do código, **respeite estritamente** as seguintes regras em sessões futuras:

### 1. Backend (PHP API)
* **Novo Endpoint**: Sempre que adicionar uma nova rota na API, declare a URL em `backend/Rotas/rotas.php`.
* **Lógica do Endpoint**: Todo endpoint DEVE apontar para um método dentro de uma classe Controller. **Nunca codifique regras de negócio diretamente no arquivo de rotas.**
* **Consultas SQL**: Devem ficar encapsuladas em classes dentro de `backend/Models/`.

### 2. Frontend (Web)
* **Páginas Novas**: Crie em `frontend/pages/` e roteie corretamente. Consuma APIs com `/api/...`.
* **Estilização**: Preserve as variáveis de core no `style.css`.

### 3. Desktop (Electron)
* **Comunicação Segura**: **NÃO** use variáveis nativas do Node (`fs`, `path`, etc.) nos arquivos JS dentro do `renderer/`. Toda comunicação avançada de SO deve passar pelo `ipcRenderer` através do script `electron/preload.js`.

### 4. Higiene da Raiz
* **Nunca** coloque arquivos temporários, logs ou scripts soltos (`testes.php`) na raiz do projeto. 
* Utilize `tests/` para debugs ou `scripts/` para rotinas e correções automáticas no banco.

---

## 📝 Lista de Tarefas / Débitos Técnicos e Design

Esta é a consolidação das melhorias sugeridas (antigo `task.md` e `TECHNICAL_ANALYSIS`):

### 🎨 Design e UX
- [ ] **Tipografia Global**: Importar fontes `Montserrat` e `Oswald` no index público para não depender de fontes legadas de SO.
- [ ] **Hero Section (Carrossel)**: Adicionar overlay escuro e Call-to-actions nos banners do carrossel principal.
- [ ] **Integração Real na Home**: Atualmente a Home usa produtos mockados e hardcoded no `script.js`. Precisa buscar de `/api/produtos` nativamente.
- [ ] **Barra de Atendimento**: Analisar a barra sólida no topo para versão menos ruidosa visualmente.

### 🚨 Segurança e Backend 
- [ ] **Sanitização de Saída**: Garantir `htmlspecialchars()` em dados abertos (Ex: comentários, buscas).
- [ ] **Criptografia**: Confirmar o processo universal de `password_hash()` no cadastro e verificar `/api/usuarios`.
- [ ] **Centralização Frontend**: Criar pedaços globais em HTML ou Renderizar via PHP as `headers` e `footers` públicas.

---
**Desenvolvido sob padrões MVC PHP / Node.js.**
