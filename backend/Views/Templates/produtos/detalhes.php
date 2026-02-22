<?php 
// Esta View recebe um único produto na variável $produto, que é um array associativo.
// Se o produto não for encontrado, exibe uma mensagem de erro.
if (empty($produto)): ?>
    <div class="w3-panel w3-red w3-round-large" style="margin: 20px;">
        <h3>Erro!</h3>
        <p>Produto não encontrado ou ID inválido. Verifique a URL.</p>
    </div>
<?php else: 
    // Se o produto for encontrado, exibimos os detalhes.
?>

<header class="w3-container" style="padding-top:22px">
 <h5><b><i class="fa fa-info-circle"></i> Detalhes do Produto</b></h5>
</header>

<div class="w3-container w3-margin-bottom" style="max-width: 800px;">
    <h2 class="w3-border-bottom w3-padding-16">
        <?php echo htmlspecialchars($produto['nome_produtos']); ?>
    </h2>

    <div class="w3-card-4 w3-white w3-padding">
        <div class="w3-row-padding">
            
            <!-- Coluna de Informações -->
            <div class="w3-half">
                <p><strong>ID:</strong> <?= htmlspecialchars($produto['id_produto']) ?></p>
                <p><strong>Descrição:</strong> <?= htmlspecialchars($produto['descricao_produtos']) ?></p>
                <p><strong>Preço:</strong> R$ <?= number_format($produto['preco_produtos'], 2, ',', '.') ?></p>
                <p><strong>Estoque:</strong> <?= htmlspecialchars($produto['estoque_produtos']) ?> unidades</p>
                <p><strong>Criado em:</strong> <?= htmlspecialchars($produto['criado_em']) ?></p>
            </div>

            <!-- Coluna da Imagem (Exemplo) -->
            <div class="w3-half w3-center">
                <!-- Se você tiver o link da imagem no campo 'imagem_produtos', use-o aqui. Caso contrário, este é um placeholder. -->
                <img src="https://placehold.co/250x250/007bff/white?text=PRODUTO+<?= htmlspecialchars($produto['id_produto']) ?>" 
                     alt="Imagem do Produto" 
                     class="w3-round" 
                     style="width:100%; max-width: 250px;">
                <p><small class="w3-text-grey">Imagem de Exemplo</small></p>
            </div>
        </div>

        <div class="w3-section w3-border-top w3-padding-small w3-right-align">
            <a href="/backend/produtos/editar/<?= htmlspecialchars($produto['id_produto']) ?>"
               class="w3-button w3-round w3-blue w3-hover-dark-grey w3-margin-right">
                <i class="fa fa-edit"></i> Editar
            </a>
            <a href="/backend/produtos/listar"
               class="w3-button w3-round w3-light-grey w3-hover-dark-grey">
                <i class="fa fa-arrow-left"></i> Voltar à Lista
            </a>
        </div>
    </div>
</div>

<?php endif; ?>