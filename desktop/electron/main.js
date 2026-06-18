const { app, BrowserWindow, protocol, net } = require('electron');
const path = require('path');
const fs = require('fs');
const Logger = require('./services/logger');
const { registerAuthHandlers } = require('./handlers/authHandlers');
const { registerProductHandlers } = require('./handlers/productHandlers');
const { registerClientHandlers } = require('./handlers/clientHandlers');
const { registerOrderHandlers } = require('./handlers/orderHandlers');
const { registerSizeHandlers } = require('./handlers/sizeHandlers');
const { registerDashboardHandlers } = require('./handlers/dashboardHandlers');
const { registerBannersHandlers } = require('./handlers/bannersHandlers');
const { registerConfigHandlers } = require('./handlers/configHandlers');
const { registerSyncHandlers } = require('./handlers/syncHandlers');
// const { closePool } = require('../database/mysql-connection'); // Removed for SQLite migration

// Variaveis globais
let mainWindow;
const isDev = !app.isPackaged || process.env.NODE_ENV === 'development';

function getWindowSize() {
  const args = process.argv.slice(2);
  const widthArg = args.find(arg => arg.startsWith('--window-width='));
  const heightArg = args.find(arg => arg.startsWith('--window-height='));

  const widthEnv = Number(process.env.WINDOW_WIDTH);
  const heightEnv = Number(process.env.WINDOW_HEIGHT);

  const widthParsed = widthArg ? Number(widthArg.split('=')[1]) : widthEnv;
  const heightParsed = heightArg ? Number(heightArg.split('=')[1]) : heightEnv;

  const width = Number.isFinite(widthParsed) && widthParsed > 0 ? widthParsed : 1280;
  const height = Number.isFinite(heightParsed) && heightParsed > 0 ? heightParsed : 800;

  return { width, height };
}

function createWindow() {
  const { width, height } = getWindowSize();
  mainWindow = new BrowserWindow({
    width,
    height,
    webPreferences: {
      preload: path.join(__dirname, 'preload.js'),
      nodeIntegration: false,
      contextIsolation: true,
      // ✅ webSecurity habilitado (padrão) — imagens locais servidas via protocolo app://
    }
  });

  // Load the index.html of the app.
  if (!app.isPackaged) {
    mainWindow.loadURL('http://localhost:5173/renderer/pages/login.html');
    Logger.log('Carregando URL de desenvolvimento: http://localhost:5173/renderer/pages/login.html');
  } else {
    mainWindow.loadFile(path.join(__dirname, '../dist/renderer/pages/login.html'));
    Logger.log('Carregando arquivo de produção');
  }

  // DevTools desativado — remova o comentário abaixo para reativar ao depurar
  // if (isDev) {
  //   mainWindow.webContents.openDevTools();
  //   Logger.debug('DevTools aberto (modo desenvolvimento)');
  // }

  mainWindow.on('closed', () => {
    mainWindow = null;
  });

  Logger.log('Janela principal criada');
}

// Registrar todos os handlers IPC
function registerHandlers() {
  registerAuthHandlers();
  registerProductHandlers();
  registerClientHandlers();
  registerOrderHandlers();
  registerSizeHandlers();
  registerDashboardHandlers();
  registerBannersHandlers();
  registerConfigHandlers();
  registerSyncHandlers();
  Logger.log('✅ Todos os handlers IPC registrados');
  Logger.log('  ├─ Auth, Products, Clients, Orders');
  Logger.log('  ├─ Sizes, Dashboard, Banners, Config');
  Logger.log('  └─ Sync (converterFileParaBase64, sincronizarProdutoParaApi)');
}

const SyncService = require('./services/syncService');

app.whenReady().then(() => {
  // ✅ Registrar protocolo seguro app:// para servir arquivos locais (imagens etc.)
  protocol.handle('app', (request) => {
    const filePath = decodeURIComponent(request.url.replace('app://local/', ''));
    return net.fetch(`file:///${filePath.replace(/\\/g, '/')}`);
  });

  registerHandlers();
  
  // O SyncService foi desativado porque o Desktop passou a usar acesso direto (MySQL) 
  // e gravar os dados na mesma base do backend Web.

  createWindow();

  app.on('activate', () => {
    if (mainWindow === null) {
      createWindow();
    }
  });
});

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
});

// Fechar o pool de conexões MySQL ao encerrar a aplicação
// app.on('will-quit', async () => {
//   await closePool();
//   Logger.log('Pool MySQL encerrado.');
// });

Logger.log('Aplicação Koketsu Desktop iniciada');