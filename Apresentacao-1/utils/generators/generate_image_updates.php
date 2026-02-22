<?php
// Conexão direta simples
try {
    $db = new PDO(
        "mysql:host=127.0.0.1;dbname=koketsu;charset=utf8mb4",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Mapear imagens por categoria
    $imagens_por_categoria = [
        'Camiseta' => [
            'img/CAMISETA MARROM.png',
            'img/camiseta branca.png',
            'img/camiseta off white.png',
            'img/camisetaaa branca.png',
            'img/camise preta street.png',
            'img/comp-polo-branca-frente.webp',
            'img/camiseta-jersey-comp-26.webp'
        ],
        'Calças' => [
            'img/calça 2.png',
            'img/calça 3.png',
            'img/calça 4.png'
        ],
        'Jaquetas' => [
            'img/moletom.png',
            'img/moletom 2.png',
            'img/moletom 3.png',
            'img/moletom 4.png'
        ],
        'Tênis' => [
            'img/dn.png',
            'img/dn branco.png',
            'img/dn branco 3232.png',
            'img/dn vermelho.png',
            'img/TN.png'
        ],
        'Acessórios' => [
            'img/bone.jpg',
            'img/meias.jpg'
        ],
        'Bermudas' => [ // Usa imagens de calças
            'img/calça 2.png',
            'img/calça 3.png',
            'img/calça 4.png'
        ],
        'Polo' => [
            'img/polo.png',
            'img/polo 2.png',
            'img/polo 3.png',
            'img/comp-polo-branca-frente.webp'
        ]
    ];
    
    // Mapeamento de nomes de categoria do BD para nosso array
    $mapa_categorias = [
        'Camisetas' => 'Camiseta',
        'Camiseta' => 'Camiseta',
        'Calça' => 'Calças',
        'Calças' => 'Calças',
        'Calçados' => 'Tênis',
        'Jaqueta' => 'Jaquetas',
        'Jaquetas' => 'Jaquetas',
        'Moletom' => 'Jaquetas',
        'Moletons' => 'Jaquetas',
        'Tênis' => 'Tênis',
        'Bermuda' => 'Bermudas',
        'Bermudas' => 'Bermudas',
        'Shorts' => 'Bermudas',
        'Acessórios' => 'Acessórios',
        'Bonés' => 'Acessórios',
        'Meias' => 'Acessórios',
        'Polos' => 'Polo',  // ADICIONADO
        'Polo' => 'Polo',
        'Camisas Sociais' => 'Polo',  // ADICIONADO
        'Camisa Social' => 'Polo'
    ];
    
    // Lista de produtos para deletar (soft delete)
    $produtos_para_deletar = [];

    
    // Buscar produtos com categoria
    $stmt = $db->query("
        SELECT 
            p.id_produto,
            p.nome_produtos,
            p.imagem_produtos,
            c.nome_categorias as nome_categoria
        FROM tbl_produtos p 
        LEFT JOIN tbl_categorias c ON p.id_categoria = c.id_categorias
        WHERE p.excluido_em IS NULL
        ORDER BY c.nome_categorias, p.id_produto
    ");
    
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Agrupar produtos por categoria
    $produtos_por_categoria = [];
    foreach ($produtos as $p) {
        $cat_original = $p['nome_categoria'] ?? 'Outros';
        $nome_produto_lower = strtolower($p['nome_produtos']);
        
        // Identificar produtos para deletar (Vestido, Saia)
        if (stripos($nome_produto_lower, 'vestido') !== false || 
            stripos($nome_produto_lower, 'saia') !== false ||
            $cat_original === 'Saias' ||
            $cat_original === 'Vestidos') {
            $produtos_para_deletar[] = $p;
            continue; // Pula este produto
        }
        
        $cat_mapeada = $mapa_categorias[$cat_original] ?? 'Outros';
        
        if (!isset($produtos_por_categoria[$cat_mapeada])) {
            $produtos_por_categoria[$cat_mapeada] = [];
        }
        $produtos_por_categoria[$cat_mapeada][] = $p;
    }
    
    // Gerar SQL
    $sql_updates = [];
    $sql_updates[] = "-- ===============================================";
    $sql_updates[] = "-- Script de Atualização de Imagens de Produtos";
    $sql_updates[] = "-- Gerado em: " . date('Y-m-d H:i:s');
    $sql_updates[] = "-- ===============================================";
    $sql_updates[] = "";
    
    $total_updates = 0;
    
    foreach ($produtos_por_categoria as $categoria => $produtos_lista) {
        $sql_updates[] = "";
        $sql_updates[] = "-- ============================================";
        $sql_updates[] = "-- Categoria: $categoria (" . count($produtos_lista) . " produtos)";
        $sql_updates[] = "-- ============================================";
        
        // Pegar imagens para esta categoria
        $imagens = $imagens_por_categoria[$categoria] ?? ['img/default.png'];
        $total_imagens = count($imagens);
        
        foreach ($produtos_lista as $index => $produto) {
            // Rotacionar imagens (reutilizar quando acabar)
            $imagem = $imagens[$index % $total_imagens];
            $id = $produto['id_produto'];
            $nome = str_replace("'", "''", $produto['nome_produtos']); // Escapar aspas simples
            
            $sql_updates[] = "UPDATE tbl_produtos SET imagem_produtos = '$imagem' WHERE id_produto = $id; -- $nome";
            $total_updates++;
        }
    }
    
    $sql_updates[] = "";
    $sql_updates[] = "-- ===============================================";
    $sql_updates[] = "-- DELETAR PRODUTOS (SOFT DELETE)";
    $sql_updates[] = "-- ===============================================";
    
    if (count($produtos_para_deletar) > 0) {
        $sql_updates[] = "-- Deletando " . count($produtos_para_deletar) . " produtos indesejados (Vestidos e Saias)";
        foreach ($produtos_para_deletar as $produto) {
            $id = $produto['id_produto'];
            $nome = str_replace("'", "''", $produto['nome_produtos']);
            $sql_updates[] = "UPDATE tbl_produtos SET excluido_em = NOW() WHERE id_produto = $id; -- $nome";
        }
    } else {
        $sql_updates[] = "-- Nenhum produto para deletar";
    }
    
    $sql_updates[] = "";
    $sql_updates[] = "-- ===============================================";
    $sql_updates[] = "-- Total de produtos atualizados: $total_updates";
    $sql_updates[] = "-- Total de produtos deletados: " . count($produtos_para_deletar);
    $sql_updates[] = "-- ===============================================";
    
    // Salvar arquivo SQL
    $sql_content = implode("\n", $sql_updates);
    file_put_contents('update_images.sql', $sql_content);
    
    echo "✓ Script SQL gerado com sucesso!\n";
    echo "✓ Arquivo: update_images.sql\n";
    echo "✓ Total de produtos: " . count($produtos) . "\n";
    echo "✓ Total de UPDATEs: $total_updates\n\n";
    
    echo "═══════════════════════════════════════════\n";
    echo "Como aplicar as mudanças:\n";
    echo "═══════════════════════════════════════════\n";
    echo "1. Abra o MySQL Workbench\n";
    echo "2. Conecte no banco 'koketsu' (127.0.0.1:3306)\n";
    echo "3. Abra o arquivo update_images.sql\n";
    echo "4. Execute todo o script (Ctrl+Shift+Enter)\n";
    echo "5. Reinicie o servidor PHP\n";
    echo "6. Atualize o navegador\n";
    
} catch (PDOException $e) {
    echo "ERRO DE CONEXÃO: " . $e->getMessage() . "\n";
    echo "\nVerifique se:\n";
    echo "- O MySQL está rodando\n";
    echo "- O banco 'koketsu' existe\n";
    echo "- As credenciais estão corretas (user: root, senha: vazia)\n";
}
