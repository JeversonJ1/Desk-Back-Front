<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao Koketsu!</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #1a1a1a;
            color: #c9a063; /* Cor dourada/premium */
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .content h2 {
            color: #1a1a1a;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #1a1a1a;
            color: #c9a063 !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #e0e0e0;
        }
        .footer a {
            color: #888;
            text-decoration: none;
        }
        /* Mobile responsive */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                border-radius: 0;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Koketsu</h1>
        </div>
        <div class="content">
            <h2>Olá, <?= htmlspecialchars($nome) ?>!</h2>
            <p>Seja muito bem-vindo(a) ao universo <strong>Koketsu</strong>.</p>
            <p>Estamos honrados em tê-lo(a) conosco. Agora você tem acesso exclusivo às nossas coleções e novidades.</p>
            <p>Prepare-se para descobrir um novo padrão de estilo e sofisticação.</p>
            <div style="text-align: center; margin-top: 30px;">
                <a href="#" class="btn">Acessar Site</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Koketsu. Todos os direitos reservados.</p>
            <p>Este é um e-mail automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>
