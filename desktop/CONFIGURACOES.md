# 🎛️ Tela de Configurações - Koketsu Desktop

## ✅ Funcionalidades Implementadas

A tela de **Configurações** possui **3 abas** com foco em ajustes do aplicativo,
credenciais e banners do site.

---

## 📑 Abas Disponíveis

### 1️⃣ **Aplicativo**
Configurações gerais da interface e comportamento do sistema.

**Campos:**
- **Tema da Interface**: `dark` (padrão) ou `light`
- **Iniciar com Windows**: Auto-start do app
- **Ícone na Bandeja**: Mostrar tray icon
- **Minimizar para Bandeja**: Fechar para tray ao invés de sair

#### Backup (na mesma aba)
- **Habilitar Backups**
- **Backup Automático**
- **Frequência**: `daily`, `weekly`, `monthly`
- **Máximo de Backups**: 1 a 50
- **Caminho**: somente leitura

---

### 2️⃣ **Credenciais**
Alteração de login e senha do administrador.

**Campos:**
- Login atual / Senha atual
- Novo login (mín. 3 caracteres)
- Nova senha (mín. 6 caracteres) + confirmação

**Recursos:**
- Indicador de força da senha
- Toggle para mostrar/ocultar senha
- Validação em tempo real

---

### 3️⃣ **Banners**
Gerenciamento de banners do site.

**Recursos:**
- Upload e preview
- Editar/Excluir banners existentes
- Validação de tamanho (máx. 5MB)
- Compressão automática quando excede o limite

---

## 🔧 Handlers IPC Disponíveis

```javascript
// Configurações
window.api.obterConfig(sessionId)
window.api.atualizarConfigApp(sessionId, dados)
window.api.atualizarConfigBackup(sessionId, dados)
window.api.resetarConfig(sessionId)
window.api.alterarCredenciais(sessionId, dados)

// Banners
window.api.obterBanners(sessionId)
window.api.criarBanner(sessionId, banner)
window.api.atualizarBanner(sessionId, index, dados)
window.api.excluirBanner(sessionId, index)
```

> **Observação:** há handlers para **API** e **Logs** no backend (`configHandlers.js`),
> mas a UI atual não expõe esses formulários.

---

## 📂 Estrutura do `config.json`

```json
{
  "admin": {
    "username": "admin",
    "passwordHash": "..."
  },
  "sessions": { "...": "..." },
  "app": {
    "theme": "dark",
    "autoStart": false,
    "showInTray": true,
    "closeToTray": false,
    "language": "pt-BR"
  },
  "backup": {
    "enabled": true,
    "autoBackup": true,
    "frequency": "daily",
    "maxBackups": 7,
    "path": "C:/Users/.../AppData/Roaming/desk-koketsu/backups"
  }
}
```

---

## 🚀 Como Usar

1. Abra o aplicativo Electron
2. Navegue para **Configurações** no menu lateral
3. Selecione a aba desejada (Aplicativo, Credenciais, Banners)
4. Edite os campos e clique em **Salvar**

### Resetar Configurações

- Na aba **Aplicativo**, role até o final do bloco **Backup**
- Clique em **"Resetar Todas as Configurações"**
- Confirme a ação

---

## 🔐 Segurança

- **Dados sensíveis não são expostos**: `passwordHash` e `sessions` não retornam na API `config:obter`
- **Validações server-side**: Handlers validam dados antes de salvar
- **Logs de auditoria**: Alterações registradas no logger

---

## 📝 Arquivos Relacionados

- `electron/handlers/configHandlers.js`
- `electron/preload.js`
- `renderer/pages/configuracoes.html`
- `renderer/js/configuracoes.js`

---

**Data:** 3 de fevereiro de 2026
