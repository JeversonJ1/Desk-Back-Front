<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manutenção - Koketsu Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Outfit:wght@400;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/img/logo2026.png">
    <style>
        :root {
            --bg-dark: #050505;
            --accent: #c5a02d;
            --text-main: #ffffff;
            --text-muted: #888888;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            text-align: center;
            overflow: hidden;
        }

        /* Background Glow */
        body::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(197, 160, 45, 0.1) 0%, transparent 70%);
            top: 10%;
            left: 10%;
            z-index: -1;
        }

        .container {
            max-width: 550px;
            padding: 60px 40px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 32px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(197, 160, 45, 0.1);
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
            position: relative;
        }

        .logo {
            height: 60px;
            margin-bottom: 40px;
            filter: drop-shadow(0 0 10px rgba(197, 160, 45, 0.3));
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: rgba(197, 160, 45, 0.1);
            border: 1px solid rgba(197, 160, 45, 0.2);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite ease-in-out;
        }

        .icon {
            font-size: 40px;
            color: var(--accent);
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5em;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            letter-spacing: -1px;
        }

        p {
            font-size: 1.1em;
            color: var(--text-muted);
            line-height: 1.8;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .footer {
            font-size: 0.85em;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 700;
            opacity: 0.5;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(197, 160, 45, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(197, 160, 45, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(197, 160, 45, 0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/assets/img/logo2026.png" alt="Koketsu" class="logo">
        <div class="icon-wrapper">
            <i class="fa fa-screwdriver-wrench icon"></i>
        </div>
        <h1>Estamos Evoluindo</h1>
        <p>A Koketsu Store está passando por uma atualização estética premium para melhor atendê-lo. Voltaremos em instantes com uma experiência renovada.</p>
        <div class="footer">
            &copy; <?= date('Y') ?> Koketsu Store • Estilo & Excelência
        </div>
    </div>
</body>
</html>
