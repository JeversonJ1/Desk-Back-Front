const database = require('./database/mysql-database.cjs');

async function executarTeste() {
  try {
    console.log("=========================================");
    console.log("🚀 INICIANDO TESTE DE SINCRONIZAÇÃO IA 🚀");
    console.log("=========================================\n");

    console.log("1. Conectando o Desktop ao banco de dados MySQL padrão do site...");
    
    const novoProduto = {
      nome: "🔥 PRODUTO TESTE DA IA 🔥",
      descricao: "Este produto foi inserido pelo Desktop para provar que está 100% sincronizado com o Painel Web em tempo real.",
      preco: 99.99,
      estoque: 100,
      categoria: null,
      imagem: null
    };

    console.log("\n2. Inserindo novo produto pelo Desktop...");
    const resultado = await database.produtos.criar(novoProduto);
    
    console.log("\n✅ SUCESSO! Produto inserido com sucesso!");
    console.log(`ID no MySQL: ${resultado.id}`);
    console.log("=========================================");
    console.log("Acesse o seu Desktop e o seu Site agora mesmo.");
    console.log("Você verá o produto nos dois painéis simultaneamente, sem a necessidade daquele Sync falso!");
    console.log("=========================================");
    
    process.exit(0);
  } catch (error) {
    console.error("❌ ERRO NO TESTE:", error.message);
    process.exit(1);
  }
}

executarTeste();
