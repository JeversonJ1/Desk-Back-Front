require('dotenv').config();
const express = require('express');
const cors = require('cors');
const OpenAI = require('openai');
const fs = require('fs');

const app = express();
const port = 5000;

// Configuração do OpenAI
const openai = new OpenAI({
  apiKey: process.env.OPENAI_API_KEY,
});

app.use(cors());
app.use(express.json());

// Função chatbotDeModa (conforme solicitado pelo usuário, adaptada para o servidor)
async function chatbotDeModa(perguntaCliente, catalogoRoupas, history) {
  const response = await openai.chat.completions.create({
    model: "gpt-4o-mini",
    messages: [
      { 
        role: "system", 
        content: `Você é uma "Vendedora de Moda" especializada e consultora de estilo da Koketsu Grife.
        Seu objetivo é vender os produtos da marca e encantar o cliente.
        
        CATÁLOGO DE PRODUTOS:
        ${JSON.stringify(catalogoRoupas)}

        REGRAS DE OURO:
        1. Se o cliente pedir algo que NÃO temos, sugira algo parecido que temos no catálogo acima ou diga: "No momento não temos essa peça, mas você poderia me informar seu e-mail? Assim eu te aviso assim que chegar na nossa curadoria exclusiva!".
        2. Toda vez que recomendar um produto, escreva o nome dele exatamente como está no catálogo dentro de colchetes, ex: [Jaqueta Jeans]. Isso é CRUCIAL para o sistema gerar o link de compra.
        3. Seu tom é luxuoso, urbano e focado em converter a conversa em desejo de compra.
        4. Mantenha as respostas curtas e impactantes.` 
      },
      ...history,
      { role: "user", content: perguntaCliente }
    ],
  });

  return response.choices[0].message.content;
}

// Endpoint de Chat do Estilista
app.post('/api/chat', async (req, res) => {
  try {
    const { message, history } = req.body;

    if (!message) {
      return res.status(400).json({ error: 'Mensagem é obrigatória' });
    }

    // Carregar catálogo de produtos do arquivo vitrine_out.txt
    let catalogoRoupas = [];
    try {
      const data = fs.readFileSync('vitrine_out.txt', 'utf8');
      // Remover BOM (Byte Order Mark) se existir e tratar UTF-16LE se necessário
      catalogoRoupas = JSON.parse(data.replace(/^\uFEFF/, ''));
    } catch (e) {
      console.warn('Alerta: Não foi possível carregar o catálogo de vitrine_out.txt. Usando fallback vazio.');
    }

    const aiMessage = await chatbotDeModa(message, catalogoRoupas, history);
    res.json({ reply: aiMessage });

  } catch (error) {
    console.error('Erro na OpenAI:', error.message);
    res.status(500).json({ error: 'Erro ao processar sua solicitação de IA' });
  }
});

app.listen(port, () => {
  console.log(`🚀 Micro-API de IA da Koketsu rodando em http://localhost:${port}`);
});
