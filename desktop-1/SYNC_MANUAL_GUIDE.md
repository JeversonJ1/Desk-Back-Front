# 🔄 Guia de Implementação - Sincronização Manual de Produtos

## ✅ O que foi criado:

### 1. **Frontend (JavaScript)**
- ✅ Função `sincronizarProdutos()` em `renderer/js/configuracoes.js`
- ✅ Conversão automática de imagens `file://` para Base64
- ✅ Mapeamento de campos para formato da API
- ✅ Interface com progresso e status

### 2. **Backend (Electron Handlers)**
- ✅ `syncHandlers.js` - Handlers de sincronização
- ✅ `converterFileParaBase64` - Converte imagens locais
- ✅ `sincronizarProdutoParaApi` - Envia para API remota
- ✅ `categorias:listar` - Lista categorias para mapeamento

### 3. **Configuração**
- ✅ Handlers registrados em `main.js`
- ✅ Funções expostas em `preload.js`

## 📋 Próximo Passo - Adicionar HTML

Você precisa **adicionar manualmente** o card de sincronização no arquivo:
`renderer/pages/configuracoes.html`

**Localização:** Após o card de Backup (linha ~176)

**HTML para adicionar:**

```html
          <!-- Sincronização com API -->
          <div class="card" style="background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%); border: 1px solid #333; padding: 30px; border-radius: 12px; margin-bottom: 20px;">
            <h3 style="color: #ffc107; margin-bottom: 25px;">
              🔄 Sincronização com Servidor
            </h3>
            
            <div class="mb-4">
              <p style="color: #aaa; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                Sincronize os produtos do banco de dados local com o servidor remoto. As imagens serão convertidas automaticamente para Base64.
              </p>
              
              <div id="syncStatus" style="padding: 15px; border-radius: 8px; background: #0f0f0f; border: 2px solid #333; margin-bottom: 20px; display: none;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                  <div id="syncIcon" style="font-size: 24px;">⏳</div>
                  <div>
                    <div id="syncMessage" style="color: #fff; font-weight: 600;">Preparando sincronização...</div>
                    <div id="syncDetails" style="color: #888; font-size: 13px; margin-top: 4px;"></div>
                  </div>
                </div>
                <div id="syncProgress" style="width: 100%; height: 6px; background: #222; border-radius: 3px; overflow: hidden; display: none; margin-top: 10px;">
                  <div id="syncProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #ffc107, #ff9800); transition: width 0.3s;"></div>
                </div>
              </div>
              
              <button id="btnSincronizar" type="button" class="btn btn-gold w-100" style="padding: 14px; font-weight: 600; font-size: 15px; display: flex; align-items: center; justify-content: center; gap: 10px;" onclick="sincronizarProdutos()">
                <span style="font-size: 20px;">🔄</span>
                <span>Sincronizar Produtos Agora</span>
              </button>
              
              <div style="margin-top: 15px; padding: 12px; background: rgba(255,193,7,0.1); border-left: 3px solid #ffc107; border-radius: 4px;">
                <small style="color: #ffc107; font-size: 12px;">
                  <strong>⚠️ Importante:</strong> Esta ação enviará todos os produtos do banco local para o servidor remoto. Certifique-se de que o servidor está acessível.
                </small>
              </div>
            </div>
          </div>
```

**Onde adicionar:**
- Abra `renderer/pages/configuracoes.html`
- Localize o final do card de Backup (após `</form></div>` da linha ~176)
- Cole o HTML acima **antes** de `</div></div></div>` (fechamento da aba Aplicativo)

## 🎯 Como Usar:

1. **Recarregar a aplicação Electron** (`Ctrl + R`)
2. **Ir para Configurações** → Aba "Aplicativo"
3. **Scroll até o card "Sincronização com Servidor"**
4. **Clicar em "Sincronizar Produtos Agora"**

## 🔄 O que acontece na sincronização:

1. ✅ Lê todos os produtos do banco SQLite local
2. ✅ Para cada produto:
   - Converte imagens `file://` em Base64
   - Mapeia campos locais para formato da API:
     - `nome` → `nome_produtos`
     - `descricao` → `descricao_produtos`
     - `preco` → `preco_produtos`
     - `estoque` → `estoque_produtos`
     - `categoria` (nome) → `id_categoria` (ID numérico)
     - `imagem` → `imagem_produtos` (Base64)
3. ✅ Envia para `http://localhost:8000/backend/api/produtos`
4. ✅ Mostra progresso e resultado

## ⚠️ Observações:

- A API deve estar rodando em `http://localhost:8000/backend/api/produtos`
- Produtos sem categoria terão `id_categoria = null`
- Imagens serão convertidas para Base64 automaticamente
- Timeout de 30 segundos por produto

## 📁 Arquivos do Template:

Eu criei `sync-card.html` com o template HTML caso precise consultar.
