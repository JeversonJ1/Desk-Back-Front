# 🤖 Guia Completo: Concierge Koketsu (Inteligência Artificial)

Este documento contém todas as informações técnicas, financeiras e operacionais para a manutenção do seu novo assistente pessoal de luxo.

---

## 🏗️ 1. Arquitetura do Sistema
O sistema foi construído usando a **Opção A (Micro-API Node.js)**, garantindo segurança e escalabilidade.

- **Frontend**: Widget flutuante na Home (`index.html` + `ai-assistant.js`).
- **Backend**: Servidor dedicado em Node.js (`ai-server.js`) rodando na **porta 5000**.
- **Segurança**: Sua chave de API fica protegida no arquivo `.env` do servidor, nunca sendo exposta ao cliente final.

---

## 💰 2. Estimativa de Custos (GPT-4o-mini)
Utilizamos o modelo mais eficiente da OpenAI para garantir o menor custo possível.

| Volume de Uso | Mensagens Estimadas | Custo Mensal (R$) |
| :--- | :--- | :--- |
| **Leve** | 3.000 / mês | ~ R$ 2,60 |
| **Moderado** | 30.000 / mês | ~ R$ 26,00 |
| **Intenso** | 150.000 / mês | ~ R$ 130,00 |

*Cálculo baseado em dólar a R$ 5,00. O sistema é **pré-pago** (você controla o quanto gasta).*

---

## 🚀 3. Guia de Ativação (Passo a Passo)

### Passo 1: Recarga de Créditos
1. Acesse: [OpenAI Billing](https://platform.openai.com/settings/organization/billing/overview)
2. Clique em **"Add funds"**.
3. Adicione o valor mínimo ($5 dólares).

### Passo 2: Configuração da Chave
Sua chave já está configurada no seu arquivo `.env`, mas caso precise trocá-la:
- Arquivo: `c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\.env`
- Campo: `OPENAI_API_KEY=sua_chave_aqui`

### Passo 3: Iniciando o Servidor
O servidor precisa estar rodando para o chat funcionar. No terminal, use:
```bash
node ai-server.js
```
*(Eu já deixei ele rodando para você agora!)*

---

## 👗 4. Funcionalidades de Venda
Configurei a IA com as suas "Dicas de Ouro":

- **Persona Vendedora**: Ela não apenas tira dúvidas, ela induz à compra com tom de luxo.
- **Captura de Leads**: Se um produto não existir, ela solicita o e-mail do cliente.
- **Links Diretos**: Quando ela recomenda um produto como `[Jaqueta Jeans]`, o site cria automaticamente um link clicável para o catálogo.
- **Conhecimento do Catálogo**: Ela lê o arquivo `vitrine_out.txt` para saber exatamente o que você tem em estoque.

---

## 🛠️ 5. Suporte Técnico
- **ERRO "Problema Técnico"**: Verifique se você tem saldo na OpenAI.
- **ERRO "Connection Refused"**: Verifique se o comando `node ai-server.js` está ativo no terminal.

---
*Documento gerado em 16/03/2026 para Koketsu Grife.*
