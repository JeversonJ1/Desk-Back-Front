<?php
// Usar o autoloader do Composer para carregar todas as classes
require_once __DIR__ . '/vendor/autoload.php';

use App\Koketsu\Database\Database;

try {
    $db = Database::getInstance();

    // Listar para depuração
    $stmt = $db->query("SELECT * FROM tbl_categorias");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Categorias atuais:\n";
    foreach ($categories as $cat) {
        echo "- ID: {$cat['id_categorias']} | Nome: {$cat['nome_categorias']}\n";
    }

    // Renomear "Acessórios" para "Chapéus e Bonés"
    $stmt = $db->prepare("UPDATE tbl_categorias SET nome_categorias = 'Chapéus e Bonés' WHERE nome_categorias LIKE '%Acessórios%'");
    $stmt->execute();
    $affectedAcessorios = $stmt->rowCount();

    // Renomear "Polos" para "Chapéus e Bonés" (se for o caso de querer unificar)
    // O usuário disse: "tire isso [acessorios] e coloque chapeus e bones, ai coloca la os bones e chapeus etc"
    // Geralmente Polos é outra coisa, mas na grid de home eles estão juntos.
    // Vamos renomear ambos para garantir que os produtos apareçam na nova categoria.
    $stmt = $db->prepare("UPDATE tbl_categorias SET nome_categorias = 'Chapéus e Bonés' WHERE nome_categorias = 'Polos'");
    $stmt->execute();
    $affectedPolos = $stmt->rowCount();

    echo "\nAtualização concluída:\n";
    echo "- Acessórios atualizados: $affectedAcessorios\n";
    echo "- Polos atualizados: $affectedPolos\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
