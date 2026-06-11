<?php

namespace App\Koketsu\Rotas;

class Rotas
{
   public static function get()
   {
      return [
         "GET" => [
            "/" => "Admin\DashboardController@index",
            // Usuarios
            "/usuarios" => "UsuarioController@index",
            "/usuario/criar" => "UsuarioController@viewCriarUsuarios",
            "/backend/usuario/criar" => "UsuarioController@viewCriarUsuarios",
            "/usuario/listar" => "UsuarioController@viewListarUsuarios",
            "/backend/usuario/listar" => "UsuarioController@viewListarUsuarios",
            "/usuario/editar/{id}" => "UsuarioController@viewEditarUsuarios",
            "/backend/usuario/editar/{id}" => "UsuarioController@viewEditarUsuarios",
            "/usuario/excluir/{id}" => "UsuarioController@viewExcluirUsuarios",
            "/backend/usuario/excluir/{id}" => "UsuarioController@viewExcluirUsuarios",
            "/usuario/ativar/{id}" => "UsuarioController@viewAtivarUsuarios",
            "/backend/usuario/ativar/{id}" => "UsuarioController@viewAtivarUsuarios",
            "/usuario/{id}/relatorio/{data1}/{data2}" => "UsuarioController@relatorioUsuario",
            // Categorias
            "/categorias" => "CategoriasController@index",
            "/categoria/criar" => "CategoriasController@viewCriarCategoria",
            "/categoria/listar/{pagina}" => "CategoriasController@viewListarCategoria",
            "/categoria/editar/{id}" => "CategoriasController@viewEditarCategoria",
            "/categoria/excluir/{id}" => "CategoriasController@viewExcluirCategoria",
            "/categoria/{id}/relatorio/{data1}/{data2}" => "CategoriasController@relatorioCategoria",
            // Cor
            "/cores" => "CoresController@index",
            "/cor/criar" => "CoresController@viewCriarCor",
            "/cor/listar/{pagina}" => "CoresController@viewListarCores",
            "/cor/editar/{id}" => "CoresController@viewEditarCor",
            "/cor/excluir/{id}" => "CoresController@viewExcluirCor",
            "/cor/{id}/relatorio/{data1}/{data2}" => "CoresController@relatorioCores",
            // Clientes
            "/clientes" => "ClientesController@index",
            "/cliente/listar" => "ClientesController@index",
            "/cliente/listar/{pagina}" => "ClientesController@index",
            "/cliente/editar/{id}" => "ClientesController@viewEditarCliente",
            "/cliente/excluir/{id}" => "ClientesController@viewExcluirCliente",
            "/cliente/dashboard" => "Cliente\DashboardController@index",
            "/backend/cliente/dashboard" => "Cliente\DashboardController@index",
            "/cliente/meu-perfil/{id}" => "Cliente\DashboardController@viewEditarCliente",
            "/backend/cliente/meu-perfil/{id}" => "Cliente\DashboardController@viewEditarCliente",
            "/cliente/pedidos" => "Cliente\PedidosController@index",
            "/backend/cliente/pedidos" => "Cliente\PedidosController@index",
            // --- API REST UNIVERSAL ---
            // Health Check
            '/api/health' => 'PublicApiController@healthCheck',
            // Produtos (geral antes de específico para evitar conflito de ordem)
            '/api/produtos' => 'PublicApiController@getProdutos',
            '/api/produtos/{id}' => 'PublicApiController@getProdutoById',
            // Clientes
            '/api/clientes' => 'PublicApiController@getClientes',
            '/api/clientes/{id}' => 'PublicApiController@getClienteById',
            // Pedidos
            '/api/pedidos/{id}' => 'PublicApiController@getPedidoById',
            // Usuarios
            '/api/usuarios' => 'PublicApiController@getUsuarios',
            '/api/usuarios/{id}' => 'PublicApiController@getUsuarioById',
            // Categorias
            '/api/categorias' => 'PublicApiController@getCategorias',
            '/api/categorias/{id}' => 'PublicApiController@getCategoriaById',
            // Cores
            '/api/cores' => 'PublicApiController@getCores',
            '/api/cores/{id}' => 'PublicApiController@getCorById',
            // Perfis
            '/api/perfis' => 'PublicApiController@getPerfis',
            '/api/perfis/{id}' => 'PublicApiController@getPerfilById',
            // Tamanhos
            '/api/tamanhos' => 'PublicApiController@getTamanhos',
            '/api/tamanhos/{id}' => 'PublicApiController@getTamanhoById',
            // ItensPedidos
            '/api/itenspedidos/{id}' => 'PublicApiController@getItemPedidoById',
            // Avaliacao
            '/api/avaliacoes' => 'PublicApiController@getAvaliacoes',
            '/api/avaliacoes/{id}' => 'PublicApiController@getAvaliacaoById',
            // Imagens
            '/api/imagens' => 'PublicApiController@getImagens',
            '/api/imagens/{id}' => 'PublicApiController@getImagemById',
            // Carrinho
            '/api/carrinho' => 'PublicApiController@getCarrinho',
            '/api/carrinho/{id}' => 'PublicApiController@getCarrinhoById',
            // Estoque
            '/api/estoque' => 'PublicApiController@getEstoque',
            '/api/estoque/{id}' => 'PublicApiController@getEstoqueById',
            // Banners
            '/api/banners' => 'PublicApiController@getBanners',
            '/api/banners/{id}' => 'PublicApiController@getBannerById',
            "/cliente/pedidos/detalhes/{id}" => "Cliente\PedidosController@detalhes",
            "/cliente/avaliacoes" => "Cliente\AvaliacoesController@index",
            "/backend/cliente/avaliacoes" => "Cliente\AvaliacoesController@index",
            // Perfil
            "/perfis" => "PerfilController@index",
            "/perfil/criar" => "PerfilController@viewCriarPerfil",
            "/perfil/listar" => "PerfilController@viewListarPerfis",
            "/perfil/listar/{pagina}" => "PerfilController@viewListarPerfis",
            "/perfil/editar/{id}" => "PerfilController@viewEditarPerfil",
            "/perfil/excluir/{id}" => "PerfilController@viewExcluirPerfil",
            // Tamanhos
            "/tamanhos" => "TamanhoController@index",
            "/tamanho/criar" => "TamanhoController@viewCriarTamanho",
            "/tamanho/listar" => "TamanhoController@viewListarTamanhos",
            "/tamanho/editar/{id}" => "TamanhoController@viewEditarTamanho",
            "/tamanho/excluir/{id}" => "TamanhoController@viewExcluirTamanho",
            '/api/itenspedidos' => 'PublicApiController@getItenspedidos',
            //Pedidos
            "/pedido" => "PedidosController@index",
            "/pedido/criar" => "PedidosController@viewCriarPedidos",
            "/backend/pedido/criar" => "PedidosController@viewCriarPedidos",
            "/pedido/listar" => "PedidosController@viewListarPedido",
            "/backend/pedido/listar" => "PedidosController@viewListarPedido",
            "/pedido/listar/{id}" => "PedidosController@viewPedidoUnico",
            "/pedido/detalhes/{id}" => "PedidosController@viewPedidoUnico",
            "/backend/pedido/detalhes/{id}" => "PedidosController@viewPedidoUnico",
            "/pedido/editar/{id}" => "PedidosController@viewEditarPedido",
            "/backend/pedido/editar/{id}" => "PedidosController@viewEditarPedido",
            "/pedido/excluir/{id}" => "PedidosController@viewExcluirPedido",
            "/backend/pedido/excluir/{id}" => "PedidosController@viewExcluirPedido",
            "/pedido/{id}/relatorio/{data1}/{data2}" => "PedidosController@relatorioPedido",
            "/pedido/ativar/{id}" => "PedidosController@viewAtivarPedido",
            "/backend/pedido/ativar/{id}" => "PedidosController@viewAtivarPedido",
            '/api/pedidos' => 'PublicApiController@getPedidos',
            //Produtos
            "/produtos/listar" => "ProdutosController@viewListarProduto",
            "/backend/produtos/listar" => "ProdutosController@viewListarProduto",
            "/produtos/criar" => "ProdutosController@viewCriarProduto",
            "/backend/produtos/criar" => "ProdutosController@viewCriarProduto",
            "/produtos/listar/{pagina}" => "ProdutosController@viewlistarProduto",
            "/backend/produtos/listar/{pagina}" => "ProdutosController@viewlistarProduto",
            "/produtos/editar/{id}" => "ProdutosController@viewEditarProdutos",
            "/backend/produtos/editar/{id}" => "ProdutosController@viewEditarProdutos",
            "/produtos/excluir/{id}" => "ProdutosController@viewExcluirProduto",
            "/backend/produtos/excluir/{id}" => "ProdutosController@viewExcluirProduto",
            "/produtos/ativar/{id}" => "ProdutosController@viewAtivarProdutos",
            "/backend/produtos/ativar/{id}" => "ProdutosController@viewAtivarProdutos",
            "/produtos/{id}/relatorio/{data1}/{data2}" => "ProdutosController@relatorioProduto",
            //Avaliação
            "/avaliacao" => "AvaliacaoController@index",
            "/avaliacao/criar" => "AvaliacaoController@viewCriarAvaliacoes",
            "/backend/avaliacao/criar" => "AvaliacaoController@viewCriarAvaliacoes",
            "/avaliacao/listar" => "AvaliacaoController@viewListarAvaliacoes",
            "/backend/avaliacao/listar" => "AvaliacaoController@viewListarAvaliacoes",
            "/avaliacao/excluir/{id}" => "AvaliacaoController@viewExcluirAvaliacoes",
            "/backend/avaliacao/excluir/{id}" => "AvaliacaoController@viewExcluirAvaliacoes",
            //Relatórios
            "/relatorios" => "Admin\RelatoriosController@index",
            "/relatorios/detalhado" => "Admin\RelatoriosController@relatorioDetalhado",
            "/relatorios/financeiro" => "Admin\RelatoriosController@relatorioFinanceiro",
            "/relatorios/produtos" => "Admin\RelatoriosController@relatorioProdutos",
            //Carrinho
            "/backend/carrinho" => "CarrinhoController@index",
            "/backend/carrinho/criar" => "CarrinhoController@viewCriarCarrinho",
            "/backend/carrinho/listar" => "CarrinhoController@viewListarCarrinho",
            "/backend/carrinho/editar/{id}" => "CarrinhoController@viewEditarCarrinho",
            "/backend/carrinho/excluir/{id}" => "CarrinhoController@viewExcluirCarrinho",
            //Estoque_Movimentação
            "/backend/EstoqueMovimentacao" => "EstoqueController@index",
            "/backend/EstoqueMovimentacao/criar" => "EstoqueController@viewCriarEstoque_Movimentacao",
            "/backend/EstoqueMovimentacao/listar" => "EstoqueController@viewListarEstoque_Movimentacao",
            "/backend/EstoqueMovimentacao/editar/{id}" => "EstoqueController@viewEditarEstoque_Movimentacao",
            "/backend/EstoqueMovimentacao/excluir/{id}" => "EstoqueController@viewExcluirEstoque_Movimentacao",
            //Imagens
            "/backend/Imagens" => "ImagensController@index",
            "/backend/Imagens/criar" => "ImagensController@viewCriarImagem",
            "/backend/Imagens/listar" => "ImagensController@viewListarImagem",
            "/backend/Imagens/editar/{id}" => "ImagensController@viewEditarImagem",
            "/backend/Imagens/excluir/{id}" => "ImagensController@viewExcluirImagem",
            "/backend/cliente/listar" => "ClientesController@index",
            "/backend/cliente/listar/{pagina}" => "ClientesController@index",


            // Login
            '/register' => 'AuthController@register',
            '/admin' => 'AuthController@loginadmin',
            '/login' => 'AuthController@login',
            '/logout' => 'AuthController@logout',
            '/recuperar-senha' => 'RecuperacaoSenhaController@viewRecuperarSenha',
            '/nova-senha' => 'RecuperacaoSenhaController@viewNovaSenha',
            // Aliases legados (URLs antigas — redirecionam para as rotas corretas)
            '/backend/register' => 'AuthController@register',
            '/backend/login' => 'AuthController@login',
            '/backend/admin' => 'AuthController@loginadmin',
            '/backend/logout' => 'AuthController@logout',
            '/admin/dashboard' => 'Admin\DashboardController@index',
            '/backend/admin/dashboard' => 'Admin\DashboardController@index',
            '/configuracoes' => 'ConfiguracoesController@index',
            '/backend/configuracoes' => 'ConfiguracoesController@index',
            '/configuracoes/exportar' => 'ConfiguracoesController@exportarDados',
            '/configuracoes/excluir' => 'ConfiguracoesController@excluirConta',
            '/configuracoes/banners' => 'ConfiguracoesController@listarBannersJson',
            '/backend/configuracoes/banners' => 'ConfiguracoesController@listarBannersJson',
            '/manutencao' => 'PublicApiController@viewManutencao',
            '/admin/newsletter' => 'NewsletterController@listar',
            '/backend/admin/newsletter' => 'NewsletterController@listar',
            '/admin/newsletter/excluir/{id}' => 'NewsletterController@excluir',
            '/backend/admin/newsletter/excluir/{id}' => 'NewsletterController@excluir',
            '/admin/newsletter/exportar' => 'NewsletterController@exportar',
            '/backend/admin/newsletter/exportar' => 'NewsletterController@exportar',
         ],

         "POST" => [
            "/api/pedidos" => 'PublicApiController@salvarPedido',
            "/api/produtos" => 'PublicApiController@createProduto',
            "/api/clientes" => 'PublicApiController@createUsuario',
            "/api/estoque" => 'PublicApiController@createEstoque',
            // Auth Desktop (JSON, sem session)
            "/api/auth/desktop" => 'PublicApiController@authDesktop',
            // Banners
            "/api/banners" => 'PublicApiController@createBanner',
            "/api/banners/{id}/deletar" => 'PublicApiController@deleteBanner',
            "/configuracoes/manutencao" => 'ConfiguracoesController@salvarManutencao',
            "/configuracoes/salvar" => 'ConfiguracoesController@salvar',
            // Clientes
            "/cliente/perfil-atualizar/{id}" => "Cliente\DashboardController@atualizarCliente",
            "/backend/cliente/perfil-atualizar/{id}" => "Cliente\DashboardController@atualizarCliente",
            // Usuarios 
            "/usuario/salvar" => "UsuarioController@salvarUsuario",
            "/backend/usuario/salvar" => "UsuarioController@salvarUsuario",
            "/usuario/atualizar" => "UsuarioController@atualizarUsuario",
            "/backend/usuario/atualizar" => "UsuarioController@atualizarUsuario",
            "/usuario/deletar" => "UsuarioController@deletarUsuario",
            "/backend/usuario/deletar" => "UsuarioController@deletarUsuario",
            "/usuario/ativar" => "UsuarioController@ativarUsuario",
            "/backend/usuario/ativar" => "UsuarioController@ativarUsuario",
            "/usuario/atualizar/{id}" => "UsuarioController@atualizarUsuario",
            "/backend/usuario/atualizar/{id}" => "UsuarioController@atualizarUsuario",
            "/usuario/deletar/{id}" => "UsuarioController@deletarUsuario",
            "/backend/usuario/deletar/{id}" => "UsuarioController@deletarUsuario",
            // Categorias
            "/categoria/salvar" => "CategoriasController@salvarCategoria",
            "/backend/categoria/salvar" => "CategoriasController@salvarCategoria",
            "/categoria/atualizar/{id}" => "CategoriasController@atualizarCategoria",
            "/backend/categoria/atualizar/{id}" => "CategoriasController@atualizarCategoria",
            "/categoria/deletar/{id}" => "CategoriasController@deletarCategoria",
            "/backend/categoria/deletar/{id}" => "CategoriasController@deletarCategoria",
            // Cor
            "/cor/salvar" => "CoresController@salvarCor",
            "/backend/cor/salvar" => "CoresController@salvarCor",
            "/cor/atualizar/{id}" => "CoresController@atualizarCor",
            "/backend/cor/atualizar/{id}" => "CoresController@atualizarCor",
            "/cor/deletar/{id}" => "CoresController@deletarCor",
            "/backend/cor/deletar/{id}" => "CoresController@deletarCor",
            // Perfil
            "/perfil/salvar" => "PerfilController@salvarPerfil",
            "/backend/perfil/salvar" => "PerfilController@salvarPerfil",
            "/perfil/atualizar/{id}" => "PerfilController@atualizarPerfil",
            "/backend/perfil/atualizar/{id}" => "PerfilController@atualizarPerfil",
            "/perfil/deletar/{id}" => "PerfilController@deletarPerfil",
            "/backend/perfil/deletar/{id}" => "PerfilController@deletarPerfil",
            // Tamanhos
            "/tamanho/salvar" => "TamanhoController@salvarTamanho",
            "/backend/tamanho/salvar" => "TamanhoController@salvarTamanho",
            "/tamanho/atualizar" => "TamanhoController@atualizarTamanho",
            "/backend/tamanho/atualizar" => "TamanhoController@atualizarTamanho",
            "/tamanho/atualizar/{id}" => "TamanhoController@atualizarTamanho",
            "/backend/tamanho/atualizar/{id}" => "TamanhoController@atualizarTamanho",
            "/tamanho/deletar/{id}" => "TamanhoController@deletarTamanho",
            "/backend/tamanho/deletar/{id}" => "TamanhoController@deletarTamanho",
            //Pedidos
            "/pedido/salvar" => "PedidosController@salvarPedido",
            "/backend/pedido/salvar" => "PedidosController@salvarPedido",
            "/pedido/atualizar/{id}" => "PedidosController@atualizarPedidos",
            "/backend/pedido/atualizar/{id}" => "PedidosController@atualizarPedidos",
            "/pedido/deletar" => "PedidosController@deletarPedido",
            "/backend/pedido/deletar" => "PedidosController@deletarPedido",
            "/pedido/ativar" => "PedidosController@ativarPedido",
            "/backend/pedido/ativar" => "PedidosController@ativarPedido",
            "/pedido/mudar-status" => "PedidosController@mudarStatusRapido",
            "/backend/pedido/mudar-status" => "PedidosController@mudarStatusRapido",
            //produtos
            "/produtos/salvar" => "ProdutosController@salvarProduto",
            "/backend/produtos/salvar" => "ProdutosController@salvarProduto",
            "/produtos/atualizar" => "ProdutosController@atualizarProdutos",
            "/backend/produtos/atualizar" => "ProdutosController@atualizarProdutos",
            "/produtos/deletar" => "ProdutosController@deletarProdutos",
            "/backend/produtos/deletar" => "ProdutosController@deletarProdutos",
            "/produtos/ativar" => "ProdutosController@ativarProduto",
            "/backend/produtos/ativar" => "ProdutosController@ativarProduto",
            //avaliacao
            "/avaliacao/salvar" => "AvaliacaoController@salvarAvaliacao",
            "/backend/avaliacao/salvar" => "AvaliacaoController@salvarAvaliacao",
            "/avaliacao/deletar/{id}" => "AvaliacaoController@deletarAvaliacao",
            "/backend/avaliacao/deletar/{id}" => "AvaliacaoController@deletarAvaliacao",
            //avaliacao cliente
            "/cliente/avaliacao/salvar" => "Cliente\AvaliacoesController@salvar",
            "/cliente/avaliacao/atualizar/{id}" => "Cliente\AvaliacoesController@atualizar",
            "/cliente/avaliacao/excluir/{id}" => "Cliente\AvaliacoesController@excluir",
            //carrinho
            "/backend/carrinho/salvar" => "CarrinhoController@salvarCarrinho",
            "/backend/carrinho/atualizar/{id}" => "CarrinhoController@atualizarCarrinho",
            "/backend/carrinho/deletar/{id}" => "CarrinhoController@deletarCarrinho",
            //EstoqueMovimentacao
            "/backend/EstoqueMovimentacao/salvar" => "EstoqueController@salvarEstoque_Movimentacao",
            "/backend/EstoqueMovimentacao/atualizar/{id}" => "EstoqueController@atualizarEstoque_Movimentacao",
            "/backend/EstoqueMovimentacao/deletar/{id}" => "EstoqueController@deletarEstoque_Movimentacao",
            //imagens
            "/backend/Imagem/salvar" => "ImagensController@salvarImagens",
            "/backend/Imagem/atualizar/{id}" => "ImagensController@atualizarImagens",
            "/backend/Imagem/deletar/{id}" => "ImagensController@deletarImagens",

            // Login
            '/register' => 'AuthController@cadastrarUsuario',
            '/backend/register' => 'AuthController@cadastrarUsuario',
            '/login' => 'AuthController@authenticarUnificado',
            '/backend/login' => 'AuthController@authenticarUnificado',
            '/adminlogin' => 'AuthController@authenticaradmin',
            '/backend/adminlogin' => 'AuthController@authenticaradmin',
            // Recuperação de Senha
            '/recuperar-senha' => 'RecuperacaoSenhaController@solicitarRecuperacao',
            '/nova-senha' => 'RecuperacaoSenhaController@redefinirSenha',
            '/api/newsletter/inscrever' => 'NewsletterController@inscrever',
            '/admin/newsletter/enviar' => 'NewsletterController@enviarFila',
            '/backend/admin/newsletter/enviar' => 'NewsletterController@enviarFila',
            '/configuracoes/whatsapp' => 'ConfiguracoesController@salvarWhatsapp',
            '/backend/configuracoes/whatsapp' => 'ConfiguracoesController@salvarWhatsapp',
            '/configuracoes/salvar-gerais' => 'ConfiguracoesController@salvarGerais',
            '/backend/configuracoes/salvar-gerais' => 'ConfiguracoesController@salvarGerais',
            '/configuracoes/banners/salvar' => 'ConfiguracoesController@salvarBanner',
            '/backend/configuracoes/banners/salvar' => 'ConfiguracoesController@salvarBanner',
            '/configuracoes/banners/excluir/{id}' => 'ConfiguracoesController@excluirBanner',
            '/backend/configuracoes/banners/excluir/{id}' => 'ConfiguracoesController@excluirBanner',
            '/configuracoes/banners/toggle/{id}' => 'ConfiguracoesController@toggleBanner',
            '/backend/configuracoes/banners/toggle/{id}' => 'ConfiguracoesController@toggleBanner',
            '/configuracoes/banners/editar/{id}' => 'ConfiguracoesController@editarBanner',
            '/backend/configuracoes/banners/editar/{id}' => 'ConfiguracoesController@editarBanner',
         ]
      ];
   }
}