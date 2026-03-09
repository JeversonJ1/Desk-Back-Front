const pool = require('./database/mysql-connection.js');
const fs = require('fs');

async function fixAutoIncrement() {
    const tableQueries = [
        "ALTER TABLE `tbl_avaliacoes` MODIFY `id_avaliacoes` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_carrinho` MODIFY `id_carrinho` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_categorias` MODIFY `id_categorias` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_cores` MODIFY `id_cores` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_estoque_movimentacao` MODIFY `id_estoque_movimentacao` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_imagem` MODIFY `id_imagem` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_imagem_carrossel` MODIFY `id_carrossel` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_itens_pedidos` MODIFY `id_itens_pedidos` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_pedidos` MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_perfil` MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_preferencias` MODIFY `id_preferencia` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_produtos` MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_tamanhos` MODIFY `id_tamanhos` int(11) NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `tbl_usuarios` MODIFY `id_usuarios` int(11) NOT NULL AUTO_INCREMENT;"
    ];

    const results = [];
    for (const q of tableQueries) {
        try {
            await pool.query(q);
            results.push({query: q, status: "success"});
        } catch (e) {
            results.push({query: q, status: "error", message: e.message});
        }
    }
    
    fs.writeFileSync('fix_result.json', JSON.stringify(results, null, 2), 'utf8');
    process.exit(0);
}

fixAutoIncrement();
