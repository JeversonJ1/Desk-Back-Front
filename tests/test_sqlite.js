const db = require('better-sqlite3')('c:/Users/jever/OneDrive/Área de Trabalho/Desk-Back-Front/desktop/database/koketsu.sqlite');
const rows = db.prepare('SELECT id, nome, imagem FROM tbl_produtos LIMIT 5').all();
console.log(JSON.stringify(rows, null, 2));
