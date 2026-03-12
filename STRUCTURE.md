# Desk-Back-Front 🏗️
## Guia de Estrutura e Arquitetura do Projeto

Este documento mapeia a arquitetura tripla (Backend PHP, Frontend Web, Desktop Electron) e serve como a **fonte da verdade** para regras de desenvolvimento e alocação de novos arquivos.

---

### 🗺️ Mapa de Diretórios

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
│                  # Use esta pasta ao invés da raiz
│
└── tests/         # 🧪 Testes de unidade e scripts de debug (Conexão, SQLite, etc)
```

---

### 📋 Regras de Desenvolvimento (Para IA e Humanos)

Para manter a consistência do código, **respeite estritamente** as seguintes regras em sessões futuras:

#### 1. Backend (PHP API)
* **Novo Endpoint**: Sempre que adicionar uma nova rota na API, declare a URL em `backend/Rotas/rotas.php`.
* **Lógica do Endpoint**: Todo endpoint DEVE apontar para um método dentro de uma classe em `backend/Controles/`. **Nunca codifique regras de negócio diretamente no arquivo de rotas.**
* **Consultas SQL**: Devem ficar encapsuladas em classes dentro de `backend/Models/`. Os Controllers pedem dados aos Models, e os Models falam com o Banco.
* **Conexão**: A conexão principal reside em `backend/Database/`.

#### 2. Frontend (Web)
* **Páginas Novas**: Crie em `frontend/pages/` e roteie corretamente.
* **Estilização**: Preserve as variáveis de core no `style.css` e componha em componentes, se necessário. Se for injetar CSS dinâmico, faça no cabeçalho ou crie arquivos separados na pasta `css` mapeada e chame pelo index.

#### 3. Desktop (Electron)
* **Comunicação Segura**: **NÃO** use variáveis nativas do Node (`fs`, `path`, etc.) nos arquivos JS dentro do `renderer/`. Toda comunicação avançada de SO deve passar pelo `ipcRenderer` através do script `electron/preload.js` e comunicando ao `electron/main.js`.
* **Componentes**: Mantenha a separação limpa com HTML renderizando nas Views, acessando `renderer/js/` para dinâmica.

#### 4. Geral (Higiene da Raiz)
* **Nunca** coloque arquivos temporários, logs ou scripts locais (ex: `meu_teste.js`) diretamente na raiz do projeto. 
* Se for um script temporário de debug -> Use `tests/`.
* Se for um Worker ou Rotina Administrativa -> Use `scripts/`.
