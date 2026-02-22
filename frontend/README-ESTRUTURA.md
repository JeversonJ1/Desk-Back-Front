# 📚 Documentação da Estrutura do Projeto Koketsu

> Sistema de E-commerce desenvolvido em PHP com arquitetura MVC

## 📋 Índice

- [Visão Geral](#-visão-geral)
- [Árvore Completa de Diretórios](#-árvore-completa-de-diretórios)
- [Arquitetura do Sistema](#-arquitetura-do-sistema)
- [Fluxo de Dados](#-fluxo-de-dados)
- [Estrutura Detalhada](#-estrutura-detalhada)
- [Padrões e Convenções](#-padrões-e-convenções)
- [Configuração e Ambiente](#-configuração-e-ambiente)

---

## 🎯 Visão Geral

O **Koketsu** é um sistema de e-commerce completo desenvolvido em PHP puro seguindo o padrão **MVC (Model-View-Controller)**. O projeto utiliza:

- **PHP** (Namespace PSR-4)
- **MySQL** (Banco de dados relacional)
- **Composer** (Gerenciamento de dependências)
- **Bramus Router** (Roteamento)
- **PHPMailer** (Envio de emails)
- **Dotenv** (Variáveis de ambiente)

---

## 🌳 Árvore Completa de Diretórios

```
ApresentacaoArthur/
│
├── .env                              # Variáveis de ambiente (DB credentials)
├── .gitignore                        # Arquivos ignorados pelo Git
├── .vscode/                          # Configurações do VS Code
├── composer.json                     # Dependências PHP (autoload PSR-4)
├── composer.lock                     # Lock de versões das dependências
├── package.json                      # Dependências Node.js
├── package-lock.json                 # Lock de versões Node
├── Apresentacao.code-workspace       # Workspace do VS Code
├── README.md                         # Documentação original
│
├── backend/                          # 🔧 NÚCLEO DO SISTEMA (MVC)
│   │
│   ├── index.php                     # ⚡ ENTRY POINT - Inicializa app e router
│   │
│   ├── Config/                       # Configurações do sistema
│   │   └── Mail.php                  # Configuração de email
│   │
│   ├── Database/                     # Camada de banco de dados
│   │   ├── Config.php                # Configurações de conexão
│   │   └── database.php              # Classe Database (Singleton pattern)
│   │
│   ├── Rotas/                        # Sistema de roteamento
│   │   └── rotas.php                 # Definição de todas as rotas (GET/POST)
│   │
│   ├── Controles/                    # 🎮 CONTROLLERS (21 arquivos)
│   │   ├── Admin/                    # Controllers administrativos
│   │   │   ├── AdminController.php           # Controller base admin
│   │   │   ├── AuthenticatedController.php   # Autenticação base
│   │   │   ├── DashboardController.php       # Dashboard admin
│   │   │   └── RelatoriosController.php      # Relatórios gerenciais
│   │   │
│   │   ├── Cliente/                  # Controllers de cliente
│   │   │   ├── DashboardController.php       # Dashboard cliente
│   │   │   └── PedidosController.php         # Pedidos do cliente
│   │   │
│   │   ├── APIUsuarioController.php          # API de usuários
│   │   ├── AuthController.php                # Login/Registro/Logout
│   │   ├── AvaliacaoController.php           # Avaliações de produtos
│   │   ├── CarrinhoController.php            # Carrinho de compras
│   │   ├── CategoriasController.php          # CRUD Categorias
│   │   ├── CoresController.php               # CRUD Cores
│   │   ├── EstoqueController.php             # Movimentação de estoque
│   │   ├── ImagensController.php             # Upload/Gerenciamento imagens
│   │   ├── ItensPedidosController.php        # Itens de pedidos
│   │   ├── PedidosController.php             # CRUD Pedidos
│   │   ├── PerfilController.php              # CRUD Perfis de usuário
│   │   ├── ProdutosController.php            # CRUD Produtos
│   │   ├── PublicApiController.php           # API REST pública (34KB)
│   │   ├── TamanhoController.php             # CRUD Tamanhos
│   │   └── UsuarioController.php             # CRUD Usuários
│   │
│   ├── Models/                       # 📊 MODELS (12 arquivos)
│   │   ├── Avaliacao.php             # Model de avaliações
│   │   ├── Carrinho.php              # Model de carrinho
│   │   ├── Categoria.php             # Model de categorias
│   │   ├── Cor.php                   # Model de cores
│   │   ├── EstoqueMovimentacao.php   # Model de movimentação estoque
│   │   ├── Imagem.php                # Model de imagens
│   │   ├── ItensPedidos.php          # Model de itens de pedidos
│   │   ├── Pedidos.php               # Model de pedidos
│   │   ├── Perfil.php                # Model de perfis
│   │   ├── Produtos.php              # Model de produtos
│   │   ├── Tamanho.php               # Model de tamanhos
│   │   └── Usuario.php               # Model de usuários
│   │
│   ├── Core/                         # 🔐 MÓDULOS CORE (8 arquivos)
│   │   ├── Csrf.php                  # Proteção CSRF
│   │   ├── EmailService.php          # Serviço de envio de emails
│   │   ├── FileManager.php           # Gerenciamento de arquivos/uploads
│   │   ├── Flash.php                 # Mensagens flash (sessão)
│   │   ├── NotificacaoEmail.php      # Notificações por email
│   │   ├── Redirect.php              # Redirecionamentos
│   │   ├── Session.php               # Gerenciamento de sessão
│   │   └── View.php                  # Motor de renderização de views
│   │
│   ├── Validadores/                  # Validações customizadas
│   │   └── (1 arquivo)
│   │
│   ├── Views/                        # 🎨 VIEWS (Templates PHP)
│   │   └── Templates/                # (53 arquivos organizados)
│   │       ├── admin/                # Templates administrativos
│   │       │   ├── dashboard.php
│   │       │   ├── relatorios/
│   │       │   └── ... (6 arquivos)
│   │       │
│   │       ├── auth/                 # Login/Registro
│   │       │   ├── login.php
│   │       │   └── register.php
│   │       │
│   │       ├── categoria/            # Views de categorias (4 arquivos)
│   │       │   ├── index.php
│   │       │   ├── create.php
│   │       │   ├── edit.php
│   │       │   └── delete.php
│   │       │
│   │       ├── cliente/              # Views cliente (4 arquivos)
│   │       │   ├── dashboard.php
│   │       │   ├── editar.php
│   │       │   └── pedidos/
│   │       │
│   │       ├── cores/                # Views de cores (4 arquivos)
│   │       ├── emails/               # Templates de email (1 arquivo)
│   │       ├── itenspedidos/         # Views itens pedidos (5 arquivos)
│   │       ├── partials/             # Componentes reutilizáveis
│   │       │   ├── header.php
│   │       │   ├── footer.php
│   │       │   └── menu.php
│   │       │
│   │       ├── pedidos/              # Views de pedidos (5 arquivos)
│   │       │   ├── index.php
│   │       │   ├── create.php
│   │       │   ├── edit.php
│   │       │   ├── delete.php
│   │       │   └── detalhes.php
│   │       │
│   │       ├── perfil/               # Views de perfis (4 arquivos)
│   │       ├── produtos/             # Views de produtos (6 arquivos)
│   │       │   ├── index.php
│   │       │   ├── create.php
│   │       │   ├── edit.php
│   │       │   ├── delete.php
│   │       │   ├── ativar.php
│   │       │   └── detalhes.php
│   │       │
│   │       ├── tamanho/              # Views de tamanhos (4 arquivos)
│   │       └── usuario/              # Views de usuários (5 arquivos)
│   │
│   └── upload/                       # Diretório de uploads
│       └── (1 subdiretório)
│
├── vendor/                           # Dependências Composer (autoload)
│
├── node_modules/                     # Dependências Node.js
│
├── js/                               # Scripts JavaScript (4 arquivos)
│   ├── carrinho.js                   # Lógica do carrinho
│   ├── main.js                       # Scripts principais
│   ├── pedidos.js                    # Lógica de pedidos
│   └── produtos.js                   # Lógica de produtos
│
├── img/                              # Imagens do frontend (4 arquivos)
│   ├── VID-20250609-WA0056.mp4
│   ├── vestido.jpg
│   ├── TN.png
│   └── ... (várias imagens de produtos)
│
├── src/                              # Source adicional
│
└── [FRONTEND PAGES - HTML/CSS]       # Páginas estáticas do frontend
    ├── index.html                    # Página inicial
    ├── style.css                     # Estilos principais
    ├── script.js                     # Script principal
    ├── carrinho.html + carrinho.css  # Página de carrinho
    ├── produto.html                  # Página de produto
    ├── sobre.html + sobre.css        # Sobre a empresa
    ├── Trocas.html + Trocas.css      # Política de trocas
    ├── duvidas.html + duvidas.css    # FAQ/Dúvidas
    ├── fidelidade.html + fidelidade.css  # Programa fidelidade
    ├── frete.html + frete.css        # Informações de frete
    ├── politica.html + politica.css  # Política de privacidade
    ├── footer.css                    # Estilos do rodapé
    └── conteudo-do-trocas.css        # Estilos adicionais
```

---

## 🏛️ Arquitetura do Sistema

### Padrão MVC Implementado

```
┌─────────────────────────────────────────────────────────────┐
│                     📱 CLIENTE (Browser)                     │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
                    HTTP Request
                          │
┌─────────────────────────┴───────────────────────────────────┐
│                 🚪 ENTRY POINT (backend/index.php)           │
│  • Inicializa sessão                                         │
│  • Carrega .env (Dotenv)                                     │
│  • Sanitiza $_POST e $_GET                                   │
│  • Instancia Bramus Router                                   │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│              🛣️ ROUTER (Rotas/rotas.php)                    │
│  • Define rotas GET e POST                                   │
│  • Mapeia URI → Controller@método                            │
│  • Exemplos:                                                 │
│    GET  /produtos/listar → ProdutosController@viewListarProduto │
│    POST /produtos/salvar → ProdutosController@salvarProduto  │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│           🎮 CONTROLLER (Controles/*Controller.php)          │
│  • Recebe requisição                                         │
│  • Valida dados (CSRF, sanitização)                          │
│  • Chama Model para operações de dados                       │
│  • Processa lógica de negócio                                │
│  • Chama View ou retorna JSON (API)                          │
│                                                              │
│  Hierarquia:                                                 │
│  AdminController → Controles específicos                     │
│  (Autenticação, permissões, validações)                      │
└─────────────────────────┬───────────────────────────────────┘
                          │
           ┌──────────────┴────────────────┐
           ▼                               ▼
┌──────────────────────┐      ┌────────────────────────────┐
│   📊 MODEL            │      │   🎨 VIEW                  │
│  (Models/*.php)       │      │  (Views/Templates/*.php)   │
│                       │      │                            │
│ • Acessa Database     │      │ • Renderiza HTML           │
│ • Queries SQL (PDO)   │      │ • Usa dados do Controller  │
│ • Validações de BD    │      │ • Header + Content + Footer│
│ • Retorna dados       │      │ • Templates organizados    │
│                       │      │   por entidade             │
└──────┬────────────────┘      └────────────────────────────┘
       │
       ▼
┌────────────────────────────────────────────┐
│     🗄️ DATABASE (Database/database.php)    │
│  • Singleton Pattern                       │
│  • Conexão PDO persistente                 │
│  • Suporta: MySQL, SQLite, PGSQL, SQLSRV   │
│  • Configuração via Config.php             │
└────────────────────────────────────────────┘
```

### Namespace e Autoload

- **Namespace raiz**: `App\Koketsu\`
- **PSR-4 Autoload**: `"App\\Koketsu\\": "backend/"`
- Todos os arquivos backend seguem namespace estruturado

```php
// Exemplo de estrutura de namespace
App\Koketsu\Controles\ProdutosController
App\Koketsu\Models\Produtos
App\Koketsu\Core\View
App\Koketsu\Database\Database
```

---

## 🔄 Fluxo de Dados

### 1️⃣ Requisição GET - Listar Produtos

```
┌─────────┐
│ Browser │──── GET /produtos/listar ────▶┐
└─────────┘                                │
                                           ▼
                            ┌──────────────────────────┐
                            │ index.php (Entry Point)  │
                            │ • Sanitiza $_GET         │
                            │ • Carrega Router         │
                            └───────────┬──────────────┘
                                        ▼
                            ┌──────────────────────────┐
                            │ Rotas/rotas.php          │
                            │ GET route mapping:       │
                            │ "/produtos/listar"       │
                            │ → ProdutosController     │
                            │   @viewListarProduto     │
                            └───────────┬──────────────┘
                                        ▼
              ┌──────────────────────────────────────────┐
              │ ProdutosController::viewListarProduto()  │
              │ 1. Instancia Model (Produtos)            │
              │ 2. Chama $produtos->paginacao(1, 50)     │
              └──────┬────────────────────┬──────────────┘
                     │                    │
                     ▼                    ▼
         ┌──────────────────┐   ┌──────────────────────┐
         │ Produtos Model   │   │ View::render()       │
         │ • paginacao()    │   │ produtos/index.php   │
         │ • SQL Query      │   │ • Recebe $produtos   │
         │ • Retorna array  │   │ • Renderiza HTML     │
         │   [data, total   │   │ • Header + Content   │
         │    paginacao]    │   │   + Footer           │
         └──────────────────┘   └──────┬───────────────┘
                                        │
                                        ▼
                                  ┌──────────┐
                                  │ Browser  │
                                  │ (HTML)   │
                                  └──────────┘
```

### 2️⃣ Requisição POST - Salvar Produto

```
┌─────────┐
│ Browser │──── POST /produtos/salvar ────▶┐
│ (Form)  │      + $_POST + $_FILES         │
└─────────┘                                 ▼
                            ┌──────────────────────────┐
                            │ index.php                │
                            │ • Sanitiza $_POST        │
                            │ • Valida CSRF (opcional) │
                            └───────────┬──────────────┘
                                        ▼
                            ┌──────────────────────────┐
                            │ Rotas/rotas.php          │
                            │ POST route:              │
                            │ "/produtos/salvar"       │
                            │ → ProdutosController     │
                            │   @salvarProduto         │
                            └───────────┬──────────────┘
                                        ▼
              ┌──────────────────────────────────────────┐
              │ ProdutosController::salvarProduto()      │
              │ 1. Valida campos obrigatórios            │
              │ 2. Usa FileManager p/ upload imagem      │
              │ 3. Chama Model->inserirProduto()         │
              │ 4. Redireciona com mensagem flash        │
              └──────┬────────────────────┬──────────────┘
                     │                    │
                     ▼                    ▼
         ┌──────────────────┐   ┌──────────────────────┐
         │ FileManager      │   │ Produtos Model       │
         │ • salvarArquivo()│   │ • inserirProduto()   │
         │ • Move upload    │   │ • INSERT SQL         │
         │ • Retorna path   │   │ • Retorna bool/ID    │
         └──────────────────┘   └──────────────────────┘
                                        │
                                        ▼
                            ┌──────────────────────────┐
                            │ Redirect::               │
                            │ redirecionarComMensagem()│
                            │ • Flash message          │
                            │ • Header Location        │
                            └───────────┬──────────────┘
                                        ▼
                                  ┌──────────┐
                                  │ Browser  │
                                  │ (Redirect)│
                                  └──────────┘
```

### 3️⃣ API REST - Obter Produtos (JSON)

```
┌─────────────┐
│ Cliente API │──── GET /api/produtos ────▶┐
│ (JS/Mobile) │                             │
└─────────────┘                             ▼
                            ┌──────────────────────────┐
                            │ index.php                │
                            └───────────┬──────────────┘
                                        ▼
                            ┌──────────────────────────┐
                            │ Rotas/rotas.php          │
                            │ GET: "/api/produtos"     │
                            │ → PublicApiController    │
                            │   @getProdutos           │
                            └───────────┬──────────────┘
                                        ▼
              ┌──────────────────────────────────────────┐
              │ PublicApiController::getProdutos()       │
              │ • Acessa Model                           │
              │ • Formata dados                          │
              │ • header('Content-Type: application/json')│
              │ • echo json_encode($data)                │
              └──────────────────┬───────────────────────┘
                                 ▼
                    ┌──────────────────────┐
                    │ JSON Response        │
                    │ {                    │
                    │   "status": "success"│
                    │   "data": [...]      │
                    │ }                    │
                    └──────────┬───────────┘
                               ▼
                         ┌──────────┐
                         │ Cliente  │
                         │ (JSON)   │
                         └──────────┘
```

---

## 📁 Estrutura Detalhada

### 🎮 Controllers (Controles/)

**Função**: Intermediar Model e View, processar lógica de negócio

**Hierarquia de Herança**:
```
AdminController (base para admin)
    ↓
├── CategoriasController
├── CoresController
├── ProdutosController
├── UsuarioController
├── PedidosController
├── Admin/DashboardController
└── ... (outros controllers administrativos)
```

**Padrão de Nomenclatura**:
- Nome: `*Controller.php`
- Métodos views: `view*()` - Ex: `viewListarProduto()`
- Métodos actions: `salvar*()`, `atualizar*()`, `deletar*()`
- Todos possuem `__construct()` que:
  - Chama `parent::__construct()`
  - Instancia Database (Singleton)
  - Instancia Model correspondente

**Exemplo de Controller**:
```php
class ProdutosController extends AdminController {
    public $produtos;  // Model
    public $db;        // Database
    
    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->produtos = new Produtos($this->db);
    }
    
    // View method - renderiza página
    public function viewListarProduto($pagina = 1) {
        $dados = $this->produtos->paginacao($pagina, 50);
        View::render("produtos/index", ["produtos" => $dados]);
    }
    
    // Action method - processa formulário
    public function salvarProduto() {
        // Validação
        // Chamada ao Model
        // Redirect com mensagem
    }
}
```

### 📊 Models (Models/)

**Função**: Interagir com banco de dados, executar queries SQL

**Características**:
- Usam **PDO** (PHP Data Objects)
- Prepared statements (segurança contra SQL Injection)
- Métodos CRUD completos:
  - `buscar*()` - SELECT
  - `inserir*()` - INSERT
  - `atualizar*()` - UPDATE
  - `deletar*()` / `excluir*()` - Soft delete (marca campo `excluido_em`)
  - `paginacao()` - Paginação de resultados
- **Soft Delete**: Não remove do banco, marca `excluido_em` com timestamp

**Exemplo de Model**:
```php
class Produtos {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function buscarProdutosAtivos() {
        $sql = "SELECT * FROM tbl_produtos WHERE excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function inserirProduto($nome, $descricao, $imagem) {
        $sql = "INSERT INTO tbl_produtos (nome_produtos, descricao_produtos, imagem_produtos, criado_em) 
                VALUES (:nome, :descricao, :imagem, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':imagem', $imagem);
        return $stmt->execute() ? $this->db->lastInsertId() : false;
    }
    
    // Soft delete
    public function deletarProdutos($id) {
        $agora = date("Y-m-d H:i:s");
        $sql = "UPDATE tbl_produtos SET excluido_em = :excluido_em WHERE id_produto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':excluido_em', $agora);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function paginacao($pagina = 1, $porPagina = 50, $nomeBusca = null) {
        $offset = ($pagina - 1) * $porPagina;
        // ... SQL com LIMIT e OFFSET
        return [
            'data' => $dados,
            'total' => $total,
            'pagina_atual' => $pagina,
            'total_paginas' => ceil($total / $porPagina)
        ];
    }
}
```

### 🎨 Views (Views/Templates/)

**Função**: Renderizar HTML com dados do Controller

**Sistema de Renderização** (`Core/View.php`):
```php
View::render($nomeView, $dados = []);
// Exemplo: View::render("produtos/index", ["produtos" => $listaProdutos]);
```

**Estrutura**:
1. **Header** (`partials/header.php`) - HTML, CSS, menu
2. **Content** (`{entidade}/{view}.php`) - Conteúdo específico
3. **Footer** (`partials/footer.php`) - Rodapé, scripts

**Organização por Entidade**:
- `produtos/` - index, create, edit, delete, ativar, detalhes
- `pedidos/` - index, create, edit, delete, detalhes
- `usuario/` - index, create, edit, delete, ativar
- `admin/` - dashboard, relatórios
- `cliente/` - dashboard, pedidos
- `auth/` - login, register

**Exemplo de View** (produtos/index.php):
```php
<div class="container">
    <h1>Lista de Produtos</h1>
    
    <table>
        <thead>
            <tr><th>Nome</th><th>Preço</th><th>Ações</th></tr>
        </thead>
        <tbody>
            <?php foreach($produtos['data'] as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['nome_produtos']) ?></td>
                <td>R$ <?= number_format($produto['preco_produtos'], 2, ',', '.') ?></td>
                <td>
                    <a href="/produtos/editar/<?= $produto['id_produto'] ?>">Editar</a>
                    <a href="/produtos/excluir/<?= $produto['id_produto'] ?>">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php include 'paginacao.php'; ?>
</div>
```

### 🔐 Core (Core/)

**Módulos utilitários essenciais**:

1. **View.php** - Motor de renderização
   ```php
   View::render($template, $dados)
   ```

2. **Redirect.php** - Redirecionamentos
   ```php
   Redirect::redirecionarComMensagem($url, $tipo, $mensagem)
   ```

3. **Session.php** - Gerenciamento de sessão
   ```php
   Session::set($chave, $valor)
   Session::get($chave)
   ```

4. **Flash.php** - Mensagens temporárias (success/error)
   ```php
   Flash::set('success', 'Produto salvo!')
   Flash::get('success')
   ```

5. **Csrf.php** - Proteção CSRF
   ```php
   Csrf::gerarToken()
   Csrf::validarToken($token)
   ```

6. **FileManager.php** - Upload de arquivos
   ```php
   FileManager::salvarArquivo($_FILES['imagem'], 'produtos')
   ```

7. **EmailService.php** - Envio de emails
   ```php
   EmailService::enviar($destinatario, $assunto, $corpo)
   ```

8. **NotificacaoEmail.php** - Notificações automáticas

### 🛣️ Rotas (Rotas/rotas.php)

**Sistema de Roteamento**:
- Usa **Bramus Router**
- Array associativo: `HTTP_METHOD => [URI => Controller@método]`
- Processado em `index.php`

**Estrutura**:
```php
return [
    "GET" => [
        "/produtos/listar" => "ProdutosController@viewListarProduto",
        "/produtos/editar/{id}" => "ProdutosController@viewEditarProdutos",
        "/api/produtos" => "PublicApiController@getProdutos",
    ],
    "POST" => [
        "/produtos/salvar" => "ProdutosController@salvarProduto",
        "/produtos/atualizar/{id}" => "ProdutosController@atualizarProdutos",
        "/produtos/deletar/{id}" => "ProdutosController@deletarProdutos",
    ]
];
```

**Tipos de Rotas**:
- **Admin**: `/usuarios`, `/categorias`, `/cores`, `/tamanhos`, `/perfis`
- **CRUD Produtos**: `/produtos/*`
- **CRUD Pedidos**: `/pedido/*`, `/itenspedidos/*`
- **Cliente**: `cliente/dashboard`, `cliente/pedidos`
- **Auth**: `/login`, `/register`, `/logout`
- **API REST**: `/api/{entidade}`, `/api/{entidade}/{id}`
- **Relatórios**: `/relatorios/*`

### 🗄️ Database

**Classe Database** (`Database/database.php`):
- **Padrão**: Singleton
- **Conexão**: PDO persistente
- **Suporte**: MySQL, SQLite, PostgreSQL, SQL Server
- **Configuração**: `Database/Config.php` + `.env`

**Método de uso**:
```php
$db = Database::getInstance(); // Retorna PDO connection
```

**Config.php**:
```php
return [
    'database' => [
        'driver' => 'mysql',
        'mysql' => [
            'host' => 'localhost',
            'db_name' => 'koketsu',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'port' => '3306'
        ]
    ]
];
```

**.env**:
```
DB_HOST=localhost
DB_NAME=koketsu
DB_USER=root
DB_PASS=
```

### 📤 API REST (PublicApiController.php)

**Características**:
- **34KB** de código
- Endpoints para todas as entidades
- Retorna JSON
- Sem autenticação (pública)

**Endpoints Disponíveis**:
```
GET /api/produtos          - Lista todos produtos
GET /api/produtos/{id}     - Produto específico
GET /api/pedidos           - Lista todos pedidos
GET /api/pedidos/{id}      - Pedido específico
GET /api/usuarios          - Lista usuários
GET /api/categorias        - Lista categorias
GET /api/cores             - Lista cores
GET /api/tamanhos          - Lista tamanhos
GET /api/perfis            - Lista perfis
GET /api/avaliacoes        - Lista avaliações
GET /api/imagens           - Lista imagens
GET /api/carrinho          - Lista carrinhos
GET /api/estoque           - Lista movimentações estoque
POST /api/pedidos          - Criar pedido
```

---

## 🎯 Padrões e Convenções

### Nomenclatura de Arquivos

| Tipo | Padrão | Exemplo |
|------|--------|---------|
| Controller | `*Controller.php` | `ProdutosController.php` |
| Model | `*.php` (nome entidade) | `Produtos.php` |
| View | `*.php` (lowercase) | `index.php`, `create.php` |
| Core | `*.php` (PascalCase) | `FileManager.php` |
| Config | `*.php` (PascalCase) | `Config.php` |

### Nomenclatura de Métodos

| Tipo | Prefixo | Exemplo |
|------|---------|---------|
| Renderizar view | `view*` | `viewListarProduto()` |
| Salvar dados | `salvar*` | `salvarProduto()` |
| Atualizar dados | `atualizar*` | `atualizarProdutos()` |
| Deletar dados | `deletar*` | `deletarProdutos()` |
| Buscar dados | `buscar*` | `buscarPorID()` |
| Paginação | `paginacao()` | `paginacao($pagina, $porPagina)` |

### Nomenclatura de Tabelas

**Convenção**: `tbl_{entidade_plural}`

Exemplos:
- `tbl_produtos`
- `tbl_pedidos`
- `tbl_usuarios`
- `tbl_categorias`
- `tbl_cores`

### Soft Delete

Todas as tabelas possuem:
- `criado_em` - Timestamp de criação
- `atualizado_em` - Timestamp de última atualização
- `excluido_em` - NULL (ativo) ou timestamp (inativo)

```sql
-- Ativar
UPDATE tbl_produtos SET excluido_em = NULL WHERE id_produto = ?

-- Inativar (soft delete)
UPDATE tbl_produtos SET excluido_em = NOW() WHERE id_produto = ?

-- Buscar apenas ativos
SELECT * FROM tbl_produtos WHERE excluido_em IS NULL
```

### Segurança

1. **Sanitização Global** (`index.php`):
   ```php
   array_walk_recursive($_POST, function(&$item) {
       $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
   });
   ```

2. **Prepared Statements** (Models):
   ```php
   $stmt = $this->db->prepare($sql);
   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
   ```

3. **CSRF Protection** (`Core/Csrf.php`)

4. **Validações** em Controllers antes de chamar Models

### Hierarquia de Controllers

```
AdminController (src: Controles/Admin/AdminController.php)
├── ProdutosController
├── CategoriasController
├── CoresController
├── UsuarioController
├── PedidosController
└── ... (todos controllers administrativos)

AuthenticatedController
└── Cliente/DashboardController
```

---

## ⚙️ Configuração e Ambiente

### Requisitos

- PHP 7.4+
- MySQL 5.7+ / MariaDB
- Composer
- Extensões PHP: PDO, PDO_MySQL, mbstring

### Instalação

1. **Clone o projeto**
   ```bash
   git clone <repo-url>
   cd ApresentacaoArthur
   ```

2. **Instale dependências**
   ```bash
   composer install
   ```

3. **Configure .env**
   ```env
   DB_HOST=localhost
   DB_NAME=koketsu
   DB_USER=root
   DB_PASS=
   ```

4. **Configure banco de dados** em `backend/Database/Config.php`

5. **Importe schema SQL** (se disponível)

6. **Configure webserver**
   - Apache: DocumentRoot → `backend/`
   - Nginx: root → `backend/`, index → `index.php`

### Estrutura de Pastas Obrigatória

```
backend/
├── Config/
├── Controles/
├── Core/
├── Database/
├── Models/
├── Rotas/
├── Validadores/
├── Views/
│   └── Templates/
└── upload/         # WRITE PERMISSIONS NECESSÁRIAS
```

### Dependências (composer.json)

```json
{
    "require": {
        "vlucas/phpdotenv": "^5.6",      // Variáveis de ambiente
        "bramus/router": "^1.6",         // Roteamento
        "phpmailer/phpmailer": "^7.0"    // Envio de emails
    },
    "autoload": {
        "psr-4": {
            "App\\Koketsu\\": "backend/"
        }
    }
}
```

---

## 📝 Convenções para Replicação

### Para criar projeto similar seguindo este padrão:

1. **Estrutura de Diretórios**:
   ```
   projeto/
   ├── backend/
   │   ├── Config/
   │   ├── Controles/
   │   ├── Core/
   │   ├── Database/
   │   ├── Models/
   │   ├── Rotas/
   │   ├── Views/Templates/
   │   └── index.php
   ├── composer.json
   └── .env
   ```

2. **Namespace PSR-4**: `App\{ProjetoName}\`

3. **Entry Point** (`backend/index.php`):
   - Carregar autoload
   - Iniciar sessão
   - Sanitizar inputs
   - Instanciar Router
   - Processar rotas

4. **Database Singleton**:
   ```php
   $db = Database::getInstance();
   ```

5. **Roteamento centralizado** em `Rotas/rotas.php`

6. **Controllers herdam de `AdminController`**

7. **Models recebem `$db` no construtor**

8. **Views via `View::render()`**

9. **Soft Delete** em todas tabelas

10. **API REST** em controller separado

---

## 🔍 Entidades do Sistema

| Entidade | Controller | Model | Views | Função |
|----------|------------|-------|-------|--------|
| Produtos | ProdutosController | Produtos | 6 arquivos | Gerenciar produtos |
| Categorias | CategoriasController | Categoria | 4 arquivos | Categorias de produtos |
| Cores | CoresController | Cor | 4 arquivos | Cores disponíveis |
| Tamanhos | TamanhoController | Tamanho | 4 arquivos | Tamanhos disponíveis |
| Usuários | UsuarioController | Usuario | 5 arquivos | Gestão de usuários |
| Perfis | PerfilController | Perfil | 4 arquivos | Perfis de acesso |
| Pedidos | PedidosController | Pedidos | 5 arquivos | Gestão de pedidos |
| Itens Pedidos | ItensPedidosController | ItensPedidos | 5 arquivos | Itens de cada pedido |
| Carrinho | CarrinhoController | Carrinho | 3 arquivos | Carrinho de compras |
| Avaliação | AvaliacaoController | Avaliacao | 4 arquivos | Avaliações de produtos |
| Imagens | ImagensController | Imagem | 4 arquivos | Galeria de imagens |
| Estoque | EstoqueController | EstoqueMovimentacao | 4 arquivos | Movimentação estoque |

---

## 📊 Diagrama de Relacionamentos

```
Usuario (1) ──┬── (*) Pedidos
              └── (*) Avaliacao

Pedidos (1) ──── (*) ItensPedidos

ItensPedidos (*) ──── (1) Produtos

Produtos (*) ──┬── (1) Categoria
               ├── (1) Cor
               ├── (1) Tamanho
               └── (*) Imagem

Usuario (1) ──── (1) Perfil

Usuario (1) ──── (1) Carrinho

EstoqueMovimentacao (*) ──── (1) Produtos
```

---

## 🚀 Exemplos de Uso

### Criar nova entidade "Fornecedores"

1. **Criar Model** (`backend/Models/Fornecedor.php`)
2. **Criar Controller** (`backend/Controles/FornecedorController.php`)
3. **Criar Views** (`backend/Views/Templates/fornecedor/`)
4. **Adicionar Rotas** em `backend/Rotas/rotas.php`
5. **Criar tabela** `tbl_fornecedores` no banco

### Exemplo de rota:

```php
// Rotas/rotas.php
"GET" => [
    "/fornecedores" => "FornecedorController@index",
    "/fornecedor/criar" => "FornecedorController@viewCriarFornecedor",
],
"POST" => [
    "/fornecedor/salvar" => "FornecedorController@salvarFornecedor",
]
```

---

## 📞 Suporte e Manutenção

- **Autor**: Koketsu
- **Namespace**: `App\Koketsu\`
- **Versão PHP**: 7.4+
- **Database**: MySQL (koketsu)

---

## 🎓 Boas Práticas Implementadas

✅ Padrão MVC bem definido  
✅ Namespace PSR-4  
✅ Singleton para Database  
✅ Prepared Statements (PDO)  
✅ Soft Delete  
✅ Sanitização de inputs  
✅ CSRF Protection  
✅ Separação de responsabilidades  
✅ Roteamento centralizado  
✅ Sistema de views componentizado  
✅ API REST padronizada  
✅ Upload de arquivos gerenciado  
✅ Sistema de mensagens flash  
✅ Paginação de resultados  

---

**Documentação gerada em**: <?= date('d/m/Y H:i:s') ?>  
**Versão**: 1.0
