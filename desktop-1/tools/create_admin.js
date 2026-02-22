const Database = require('../database/sqlite-database.cjs');
const bcrypt = require('bcryptjs');

async function createAdmin() {
    try {
        console.log('Verificando se já existe usuário admin...');
        const existing = await Database.usuarios.buscarPorEmail('sistema.admin@email.com');

        if (existing) {
            console.log('Usuário admin já existe.');
            return;
        }

        console.log('Criando usuário admin...');
        const hashedPassword = await bcrypt.hash('admin123', 10);

        const adminUser = {
            id_usuarios: Date.now(),
            nome_usuarios: 'Administrador',
            email_usuarios: 'sistema.admin@email.com',
            nivel_acesso: 'admin',
            senha_usuarios: hashedPassword,
            foto_usuarios: '',
            sincronizado: 0
        };

        await Database.usuarios.criar(adminUser);
        console.log('Usuário admin criado com sucesso!');
        console.log('Email: sistema.admin@email.com');
        console.log('Senha: admin123');

    } catch (error) {
        console.error('Erro ao criar usuário admin:', error);
    }
}

createAdmin();
