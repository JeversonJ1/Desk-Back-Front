const crypto = require('crypto');
const fs = require('fs');
const path = require('path');
const bcrypt = require('bcryptjs'); // Usando bcryptjs
const Logger = require('./logger');

const configPath = path.join(__dirname, '../../storage/config.json');

// Dados padrão de credenciais (apenas fallback de estrutura, user/pass reais no DB)
const defaultConfig = {
  admin: {
    username: 'admin',
    // Hash bcrypt de 'admin123' — apenas fallback, login real usa o banco SQLite
    passwordHash: '$2b$10$wqgL/0YvjcOMI6NGtYmjheQ6OMxMNsU7g8skW1YAosU4FdrTKzCce'
  },
  sessions: {}
};

function loadConfig() {
  try {
    if (fs.existsSync(configPath)) {
      const data = fs.readFileSync(configPath, 'utf8');
      return JSON.parse(data);
    }
    return defaultConfig;
  } catch (error) {
    Logger.error('Erro ao carregar configuração', error);
    return defaultConfig;
  }
}

function saveConfig(config) {
  try {
    const dir = path.dirname(configPath);
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
    fs.writeFileSync(configPath, JSON.stringify(config, null, 2), 'utf8');
    // Logger.log('Configuração salva com sucesso'); // Verbose
  } catch (error) {
    Logger.error('Erro ao salvar configuração', error);
    throw error;
  }
}

class AuthService {
  static async login(username, password) {
    try {
      if (!username || !password) {
        throw new Error('Usuário e senha são obrigatórios');
      }

      const Database = require('../../database/database');

      // Tenta buscar por email
      let user = await Database.usuarios.buscarPorEmail(username);

      if (!user) {
        Logger.warn(`Usuário não encontrado no banco: ${username}`);
        throw new Error('Usuário ou senha inválidos');
      }

      // Verifica senha com bcrypt
      // Normaliza hash PHP ($2y$) para formato Node.js ($2b$) — algoritmo idêntico
      const hashNormalizado = (user.senha_usuarios || '').replace(/^\$2y\$/, '$2b$');
      const match = await bcrypt.compare(password, hashNormalizado);

      if (!match) {
        Logger.warn(`Senha incorreta (bcrypt) para usuário: ${username}`);
        throw new Error('Usuário ou senha inválidos');
      }

      const config = loadConfig();
      const sessionId = crypto.randomBytes(32).toString('hex');
      const session = {
        sessionId,
        username: user.email_usuarios || user.nome_usuarios,
        userId: user.id_usuarios,
        role: user.nivel_acesso,
        createdAt: new Date(),
        expiresAt: new Date(Date.now() + 24 * 60 * 60 * 1000) // 24 horas
      };

      config.sessions[sessionId] = session;
      saveConfig(config);

      Logger.log(`Login bem-sucedido para usuário: ${username} (ID: ${user.id_usuarios})`);
      return { sessionId, username: session.username, role: session.role };
    } catch (error) {
      Logger.error('Erro no login', error.message);
      throw error;
    }
  }

  static logout(sessionId) {
    try {
      const config = loadConfig();
      if (config.sessions[sessionId]) {
        delete config.sessions[sessionId];
        saveConfig(config);
        Logger.log(`Logout bem-sucedido para sessão: ${sessionId}`);
      }
    } catch (error) {
      Logger.error('Erro no logout', error.message);
      throw error;
    }
  }

  static validateSession(sessionId) {
    try {
      const config = loadConfig();
      const session = config.sessions[sessionId];

      if (!session) {
        return null;
      }

      const now = new Date();

      // ✅ CORRIGIDO: limpar TODAS as sessões expiradas de uma vez
      let hasExpired = false;
      for (const [sid, sess] of Object.entries(config.sessions)) {
        if (new Date(sess.expiresAt) < now) {
          delete config.sessions[sid];
          hasExpired = true;
        }
      }
      if (hasExpired) saveConfig(config);

      // Verificar se a sessão solicitada ainda existe após limpeza
      if (!config.sessions[sessionId]) {
        return null;
      }

      return config.sessions[sessionId];
    } catch (error) {
      Logger.error('Erro ao validar sessão', error.message);
      return null;
    }
  }

  static async resetarSenha(email, novaSenha) {
    try {
      if (!email || !novaSenha) {
        throw new Error('E-mail e nova senha são obrigatórios');
      }
      if (novaSenha.length < 6) {
        throw new Error('A nova senha deve ter pelo menos 6 caracteres');
      }

      const Database = require('../../database/database');
      const user = await Database.usuarios.buscarPorEmail(email);

      if (!user) {
        throw new Error('Nenhuma conta encontrada com este e-mail');
      }

      const novoHash = await bcrypt.hash(novaSenha, 10);
      await Database.usuarios.atualizarSenha(user.id_usuarios, novoHash);

      Logger.log(`Senha redefinida para usuário: ${email}`);
      return { sucesso: true };
    } catch (error) {
      Logger.error('Erro ao redefinir senha', error.message);
      throw error;
    }
  }

  static async changePassword(sessionId, oldPassword, newPassword) {
    try {
      const session = this.validateSession(sessionId);
      if (!session) {
        throw new Error('Sessão inválida ou expirada');
      }

      const Database = require('../../database/database');
      const user = await Database.usuarios.buscarPorId(session.userId);

      if (!user) {
        throw new Error('Usuário não encontrado no banco');
      }

      // Verifica senha antiga
      const hashNormalizado = (user.senha_usuarios || '').replace(/^\$2y\$/, '$2b$');
      const match = await bcrypt.compare(oldPassword, hashNormalizado);
      if (!match) {
        throw new Error('Senha atual inválida');
      }

      if (newPassword.length < 6) {
        throw new Error('Nova senha deve ter pelo menos 6 caracteres');
      }

      // Hash nova senha
      const newHash = await bcrypt.hash(newPassword, 10);

      // Atualiza no banco
      await Database.usuarios.atualizarSenha(user.id_usuarios, newHash);

      Logger.log(`Senha alterada para usuário: ${session.username}`);
      return { sucesso: true };
    } catch (error) {
      Logger.error('Erro ao alterar senha', error.message);
      throw error;
    }
  }
}

module.exports = AuthService;
