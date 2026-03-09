const Database = require('../database/mysql-database.cjs');
const bcrypt = require('bcryptjs');

async function resetPassword() {
    try {
        const email = process.argv[2];
        const newPassword = process.argv[3];

        if (!email || !newPassword) {
            console.error('Uso: node reset_password.js <email> <nova_senha>');
            return;
        }

        console.log(`Procurando usuário com email: ${email}...`);
        const user = await Database.usuarios.buscarPorEmail(email);

        if (!user) {
            console.error('Usuário não encontrado.');
            return;
        }

        console.log('Criando hash da nova senha...');
        const hashedPassword = await bcrypt.hash(newPassword, 10);

        console.log('Atualizando a senha no banco de dados...');
        await Database.usuarios.atualizarSenha(user.id, hashedPassword);

        console.log('Senha atualizada com sucesso!');
        console.log(`Usuário: ${email}`);
        console.log(`Nova Senha: ${newPassword}`);

    } catch (error) {
        console.error('Erro ao resetar a senha:', error);
    } finally {
        // Encerra a conexão com o banco de dados
        const pool = require('../database/mysql-connection');
        pool.end();
    }
}

resetPassword();
