<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;

try {
    $db = Database::getInstance();
    
    $products = [
        // Camisetas (ID 1)
        ["Camiseta Oversized Street", "Conforto e estilo urbano para o dia a dia.", 129.90, 100, "img/camiseta branca.png", 1],
        ["Camiseta Logo Minimalista", "Algodão premium com toque macio.", 119.90, 80, "img/CAMISETA MARROM.png", 1],
        ["Camiseta Essential Off-White", "Peça chave para qualquer guarda-roupa.", 139.90, 60, "img/camiseta off white.png", 1],
        ["Camiseta Graphic Back", "Estampa exclusiva nas costas.", 149.90, 50, "img/comp-polo-branca-frente.webp", 1], // Usando polo image como placeholder visual
        
        // Calças (ID 2)
        ["Calça Cargo Preta", "Utilitária e cheia de atitude.", 249.90, 40, "img/calça 4.png", 2],
        ["Calça Jeans Loose", "Corte amplo para máximo conforto.", 229.90, 45, "img/calça 4.png", 2],
        ["Calça Moletom Confort", "Para relaxar com estilo.", 189.90, 70, "img/calça 4.png", 2],
        ["Calça Alfaiataria Street", "Elegância com pegada moderna.", 279.90, 30, "img/calça 4.png", 2],
        
        // Tênis (ID 3)
        ["Sneaker Chunky White", "Design robusto e moderno.", 399.90, 20, "img/dn branco 3232.png", 3],
        ["Tênis Sport Runner", "Leveza para seus corres.", 349.90, 25, "img/dn vermelho.png", 3],
        ["Tênis Retro Basket", "Clássico que nunca sai de moda.", 429.90, 15, "img/dn.png", 3],
        ["Slide Koketsu", "O chinelo mais hypado do momento.", 149.90, 100, "img/TN.png", 3],
        
        // Jaquetas/Moletons (ID 4)
        ["Moletom Canguru Basic", "Aquecimento com estilo.", 299.90, 40, "img/moletom.png", 4],
        ["Jaqueta Puffer Preta", "Proteção contra o frio intenso.", 499.90, 10, "img/moletom 2.png", 4],
        ["Corta-Vento Tech", "Tecnologia e design funcional.", 329.90, 35, "img/moletom 3.png", 4],
        ["Hoodie Oversized", "O caimento perfeito.", 319.90, 50, "img/moletom 4.png", 4],

        // Acessórios (ID 5)
        ["Boné Trucker Logo", "O toque final no look.", 99.90, 60, "img/COMP1.png", 5], // Placeholder
        ["Shoulder Bag Black", "Praticidade para levar o essencial.", 159.90, 30, "img/COMP2.png", 5], // Placeholder
        ["Pack Meias Cano Alto", "Kit com 3 pares essenciais.", 59.90, 200, "img/COMP3.png", 5], // Placeholder
    ];

    $sql = "INSERT INTO tbl_produtos (nome_produtos, descricao_produtos, preco_produtos, estoque_produtos, imagem_produtos, id_categoria, criado_em, atualizado_em) VALUES (:nome, :desc, :preco, :estoque, :img, :cat, NOW(), NOW())";
    
    $stmt = $db->prepare($sql);
    
    echo "Inserindo produtos...\n";
    foreach ($products as $p) {
        $stmt->execute([
            ':nome' => $p[0],
            ':desc' => $p[1],
            ':preco' => $p[2],
            ':estoque' => $p[3],
            ':img' => $p[4],
            ':cat' => $p[5]
        ]);
        echo "Adicionado: {$p[0]}\n";
    }
    
    echo "Concluído! " . count($products) . " produtos adicionados.";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
