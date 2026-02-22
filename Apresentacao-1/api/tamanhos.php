<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../backend/Database/database.php';
require_once '../backend/Models/Tamanho.php';

use App\Koketsu\Database\Database;
use App\Koketsu\Models\Tamanho;

$db = Database::getInstance();
$tamanhoModel = new Tamanho($db);

// GET - Buscar tamanhos por produto
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_produto'])) {
        $id_produto = intval($_GET['id_produto']);
        $tamanhos = $tamanhoModel->buscarTamanhosPorIdProduto($id_produto);
        
        if ($tamanhos) {
            echo json_encode([
                'success' => true,
                'data' => $tamanhos
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Nenhum tamanho encontrado para este produto'
            ]);
        }
    } else {
        // Retornar todos os tamanhos
        $tamanhos = $tamanhoModel->buscarTamanhos();
        echo json_encode([
            'success' => true,
            'data' => $tamanhos
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
}
