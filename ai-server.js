require('dotenv').config();
const express = require('express');
const cors = require('cors');
const { GoogleGenAI } = require('@google/genai');
const fs = require('fs');

const app = express();
const port = 5000;

// Configuração do Google Gemini
const ai = new GoogleGenAI({ apiKey: process.env.GEMINI_API_KEY });

app.use(cors());
app.use(express.json());

// System instruction da Vendedora Koketsu
function buildSystemInstruction(catalogoRoupas) {
  return `Você é uma "Vendedora de Moda" especializada e consultora de estilo da Koketsu Grife.
Seu objetivo é vender os produtos da marca e encantar o cliente.

CATÁLOGO DE PRODUTOS:
${JSON.stringify(catalogoRoupas)}

REGRAS DE OURO:
1. Se o cliente pedir algo que NÃO temos, sugira algo parecido que temos no catálogo acima ou diga: "No momento não temos essa peça, mas você poderia me informar seu e-mail? Assim eu te aviso assim que chegar na nossa curadoria exclusiva!".
2. Toda vez que recomendar um produto, escreva o nome dele exatamente como está no catálogo dentro de colchetes, ex: [Jaqueta Jeans]. Isso é CRUCIAL para o sistema gerar o link de compra.
3. Seu tom é luxuoso, urbano e focado em converter a conversa em desejo de compra.
4. Mantenha as respostas curtas e impactantes.`;
}

// Endpoint de Chat do Estilista
app.post('/api/chat', async (req, res) => {
  try {
    const { message, history } = req.body;

    if (!message) {
      return res.status(400).json({ error: 'Mensagem é obrigatória' });
    }

    // Carregar catálogo de produtos
    let catalogoRoupas = [];
    try {
      const data = fs.readFileSync('vitrine_out.txt', 'utf8');
      catalogoRoupas = JSON.parse(data.replace(/^\uFEFF/, ''));
    } catch (e) {
      console.warn('Aviso: vitrine_out.txt não encontrado. Usando catálogo vazio.');
    }

    // Montar histórico no formato Gemini
    const geminiHistory = (history || []).map(msg => ({
      role: msg.role === 'assistant' ? 'model' : 'user',
      parts: [{ text: msg.content }]
    }));

    // Criar sessão de chat com Gemini
    const chat = ai.chats.create({
      model: 'gemini-2.5-flash',
      config: {
        systemInstruction: buildSystemInstruction(catalogoRoupas),
      },
      history: geminiHistory,
    });

    // Enviar mensagem e obter resposta
    const response = await chat.sendMessage({ message });
    const aiMessage = response.text;

    res.json({ reply: aiMessage });

  } catch (error) {
    console.error('Erro no Gemini:', error.message);
    res.status(500).json({ error: 'Erro ao processar sua solicitação de IA' });
  }
});

app.listen(port, () => {
  console.log(`🚀 Concierge Koketsu (Gemini) rodando em http://localhost:${port}`);
});
