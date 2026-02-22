const Database = require('../database/sqlite-database.cjs');

async function checkUsers() {
    try {
        console.log('Verificando usuários no banco de dados...');
        const users = await Database.usuarios.listar();

        if (users.length === 0) {
            console.log('Nenhum usuário encontrado no banco de dados.');
        } else {
            console.log(`Encontrados ${users.length} usuários:`);
            users.forEach(u => {
                console.log('--------------------------------------------------');
                console.log(`ID: ${u.id_usuarios}`);
                console.log(`Nome: ${u.nome_usuarios}`);
                console.log(`Email: ${u.email_usuarios}`); // This is what auth uses
                console.log(`Nível: ${u.nivel_acesso}`);
                console.log(`Senha (hash): ${u.senha_usuarios}`);
                // Simple check if it looks like a bcrypt hash
                const isBcrypt = u.senha_usuarios && u.senha_usuarios.startsWith('$2');
                console.log(`Formato da senha: ${isBcrypt ? 'Bcrypt (Valido)' : 'Texto Plano/Outro (Inválido para Bcrypt)'}`);
            });
        }
    } catch (error) {
        console.error('Erro ao verificar usuários:', error);
    }
}

checkUsers();
