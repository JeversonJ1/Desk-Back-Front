import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Script de login com autenticação real
class LoginManager {
  constructor() {
    this.form = document.getElementById('loginForm');
    this.usernameInput = document.getElementById('username');
    this.passwordInput = document.getElementById('password');
    this.loginBtn = document.getElementById('loginBtn');
    this.btnText = document.getElementById('btnText');
    this.alertContainer = document.getElementById('alertContainer');

    this.init();
  }

  init() {
    this.form.addEventListener('submit', (e) => this.handleLogin(e));
    this.usernameInput.focus();
  }

  async handleLogin(e) {
    e.preventDefault();

    const username = this.usernameInput.value.trim();
    const password = this.passwordInput.value;

    if (!username || !password) {
      this.showAlert('Preencha todos os campos', 'warning');
      return;
    }

    this.setLoading(true);
    this.clearAlert();

    try {
      const result = await window.api.login(username, password);

      if (result.sessionId) {
        // Salvar sessionId no localStorage
        localStorage.setItem('sessionId', result.sessionId);
        localStorage.setItem('username', result.username);
        if (result.role) localStorage.setItem('role', result.role);

        // Redirecionar para dashboard
        window.location.href = 'dashboard.html';
      }
    } catch (error) {
      console.error('Erro no login:', error);
      this.showAlert(error.message || 'Erro ao fazer login', 'danger');
      this.passwordInput.value = '';
      this.passwordInput.focus();
    } finally {
      this.setLoading(false);
    }
  }

  setLoading(loading) {
    this.loginBtn.disabled = loading;
    if (loading) {
      this.btnText.innerHTML = '<span class="loading-spinner"></span>Entrando...';
    } else {
      this.btnText.textContent = 'Entrar';
    }
  }

  showAlert(message, type = 'info') {
    const alertHtml = `
      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    `;
    this.alertContainer.innerHTML = alertHtml;
  }

  clearAlert() {
    this.alertContainer.innerHTML = '';
  }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  new LoginManager();
  new PasswordRecovery();
});

// ─── Alternância de visibilidade da senha ─────────────────
function togglePw(inputId, btn) {
  const input = document.getElementById(inputId);
  if (input.type === 'password') {
    input.type = 'text';
    btn.textContent = '🙈';
  } else {
    input.type = 'password';
    btn.textContent = '👁';
  }
}

// ─── Sistema de Recuperação de Senha ─────────────────────
class PasswordRecovery {
  constructor() {
    this.modal = null;
    this.step = 1;
    this.emailConfirmado = '';

    this.init();
  }

  init() {
    const link = document.getElementById('forgotPasswordLink');
    if (!link) return;

    link.addEventListener('click', () => this.abrir());

    const nextBtn = document.getElementById('recoveryNextBtn');
    if (nextBtn) nextBtn.addEventListener('click', () => this.avancar());

    // Enter nos campos
    document.getElementById('recoveryEmail')?.addEventListener('keydown', e => {
      if (e.key === 'Enter') this.avancar();
    });
    document.getElementById('confirmPassword')?.addEventListener('keydown', e => {
      if (e.key === 'Enter') this.avancar();
    });
  }

  abrir() {
    this.reset();
    const el = document.getElementById('recoveryModal');
    this.modal = new bootstrap.Modal(el);
    el.addEventListener('hidden.bs.modal', () => this.reset(), { once: true });
    this.modal.show();
    setTimeout(() => document.getElementById('recoveryEmail')?.focus(), 400);
  }

  reset() {
    this.step = 1;
    this.emailConfirmado = '';
    this._irParaStep(1);
    document.getElementById('recoveryEmail').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
    this._setAlert1('');
    this._setAlert2('');
    document.getElementById('recoveryBtnText').textContent = 'Verificar e-mail';
    document.getElementById('recoveryNextBtn').disabled = false;
  }

  async avancar() {
    if (this.step === 1) {
      await this._verificarEmail();
    } else {
      await this._salvarNovaSenha();
    }
  }

  async _verificarEmail() {
    const email = document.getElementById('recoveryEmail').value.trim();
    if (!email) {
      this._setAlert1('Informe seu e-mail.', 'warning');
      return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      this._setAlert1('E-mail inválido.', 'warning');
      return;
    }

    this._setBtnLoading(true, 'Verificando...');
    this._setAlert1('');

    try {
      // Verifica se o e-mail existe localmente chamando resetPassword com senha vazia
      // (o backend valida antes de alterar, então usamos apenas como verificação)
      await window.api.resetPassword(email, '__CHECK_ONLY__');
    } catch (err) {
      // Se o erro for sobre a senha, o email existe
      if (err.message && err.message.includes('pelo menos')) {
        this.emailConfirmado = email;
        this._irParaStep(2);
        this._setBtnLoading(false, 'Redefinir Senha');
        setTimeout(() => document.getElementById('newPassword')?.focus(), 100);
        return;
      }
      // Se email não existe
      this._setAlert1(err.message || 'E-mail não encontrado.', 'danger');
      this._setBtnLoading(false, 'Verificar e-mail');
      return;
    }
  }

  async _salvarNovaSenha() {
    const nova = document.getElementById('newPassword').value;
    const confirma = document.getElementById('confirmPassword').value;

    if (!nova || !confirma) {
      this._setAlert2('Preencha os dois campos de senha.', 'warning');
      return;
    }
    if (nova.length < 6) {
      this._setAlert2('A senha deve ter pelo menos 6 caracteres.', 'warning');
      return;
    }
    if (nova !== confirma) {
      this._setAlert2('As senhas não coincidem.', 'warning');
      return;
    }

    this._setBtnLoading(true, 'Salvando...');
    this._setAlert2('');

    try {
      await window.api.resetPassword(this.emailConfirmado, nova);
      this._setAlert2('Senha redefinida com sucesso! Faça login.', 'success');
      document.getElementById('recoveryNextBtn').style.display = 'none';
      document.getElementById('recoveryBack').style.display = 'block';
      document.getElementById('recoveryBack').textContent = 'Fechar';
      // Preencher o campo de e-mail no login automaticamente
      document.getElementById('username').value = this.emailConfirmado;
    } catch (err) {
      this._setAlert2(err.message || 'Erro ao redefinir senha.', 'danger');
      this._setBtnLoading(false, 'Redefinir Senha');
    }
  }

  _irParaStep(step) {
    this.step = step;
    document.getElementById('recoveryStep1').style.display = step === 1 ? 'block' : 'none';
    document.getElementById('recoveryStep2').style.display = step === 2 ? 'block' : 'none';
    document.getElementById('dot1').className = 'step-dot active';
    document.getElementById('dot2').className = step === 2 ? 'step-dot active' : 'step-dot';
    document.getElementById('recoveryBack').style.display = step === 2 ? 'block' : 'none';
  }

  _setBtnLoading(loading, text) {
    const btn = document.getElementById('recoveryNextBtn');
    btn.disabled = loading;
    document.getElementById('recoveryBtnText').textContent = text;
  }

  _setAlert1(msg, type = 'info') {
    const el = document.getElementById('recoveryAlert1');
    el.innerHTML = msg ? `<div class="alert alert-${type} py-2 px-3" style="font-size:13px">${msg}</div>` : '';
  }

  _setAlert2(msg, type = 'info') {
    const el = document.getElementById('recoveryAlert2');
    el.innerHTML = msg ? `<div class="alert alert-${type} py-2 px-3" style="font-size:13px">${msg}</div>` : '';
  }
}
