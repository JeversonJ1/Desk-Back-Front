<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$flash = null;
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Koketsu Grife</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="/assets/img/logo2026.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #f2cc7d; /* Dourado Premium */
            --accent-hover: #d4af37;
            --bg-dark: #0a0a0a;
            --card-bg: #161616;
            --text-main: #FFFFFF;
            --text-muted: #888888;
        }

        body {
            background-color: var(--bg-dark);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Removido o overlay anterior para focar no preto puro */
        
        .reg-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
            animation: fadeUp 0.8s ease-out;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reg-card {
            background: var(--card-bg);
            border: 1px solid rgba(242, 204, 125, 0.1);
            border-radius: 24px;
            padding: 45px 35px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .reg-card:hover {
            border-color: rgba(242, 204, 125, 0.3);
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.8);
        }

        .logo-area {
            margin-bottom: 20px;
        }

        .logo-area img {
            height: 65px;
            filter: drop-shadow(0 0 10px rgba(242, 204, 125, 0.2));
        }

        h1 {
            font-family: 'Oswald', sans-serif;
            font-size: 1.8rem;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }

        p.subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 35px;
            letter-spacing: 1px;
        }

        .form-group {
            position: relative;
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #444;
            transition: 0.3s;
            font-size: 1rem;
        }

        .form-group input {
            width: 100%;
            padding: 16px 16px 16px 52px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input::placeholder {
            color: #555;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(242, 204, 125, 0.02);
        }

        .form-group input:focus + i {
            color: var(--accent);
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--accent);
            border: none;
            border-radius: 14px;
            color: #000;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }
        
        .btn-submit:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(242, 204, 125, 0.2);
        }

        .links-area {
            margin-top: 30px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .links-area a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .links-area a:hover {
            color: #fff;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.7rem;
            color: #444;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Toast Notification */
        .toast-balloon {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 14px;
            background: #1a1a1a;
            border-radius: 16px;
            padding: 18px 24px;
            min-width: 300px;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.7);
            border-left: 4px solid;
            animation: toastIn 0.5s cubic-bezier(0.2,0.8,0.2,1) forwards;
            opacity: 0;
        }

        .toast-balloon.success { border-color: #4ade80; }
        .toast-balloon.error   { border-color: #f87171; }
        .toast-balloon.erros   { border-color: #fb923c; }

        .toast-icon {
            font-size: 1.6rem;
            flex-shrink: 0;
        }
        .toast-balloon.success .toast-icon { color: #4ade80; }
        .toast-balloon.error   .toast-icon { color: #f87171; }
        .toast-balloon.erros   .toast-icon { color: #fb923c; }

        .toast-body p {
            margin: 0;
            color: #fff;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .toast-body strong {
            display: block;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }
        .toast-balloon.success .toast-body strong { color: #4ade80; }
        .toast-balloon.error   .toast-body strong { color: #f87171; }
        .toast-balloon.erros   .toast-body strong { color: #fb923c; }

        .toast-close {
            background: none;
            border: none;
            color: #555;
            font-size: 1.2rem;
            cursor: pointer;
            margin-left: auto;
            flex-shrink: 0;
            transition: color 0.2s;
        }
        .toast-close:hover { color: #fff; }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(50px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(50px); }
        }
    </style>
</head>
<body>

<?php if ($flash): ?>
    <?php
        $type  = htmlspecialchars($flash['type']);
        $msg   = $flash['message'];
        $icon  = ($type === 'success') ? '&#10003;' : (($type === 'erros') ? '&#9888;' : '&#10005;');
        $label = ($type === 'success') ? 'Sucesso' : (($type === 'erros') ? 'Atenção' : 'Erro');
    ?>
    <div class="toast-balloon <?= $type ?>" id="toastMsg">
        <div class="toast-icon"><?= $icon ?></div>
        <div class="toast-body">
            <strong><?= $label ?></strong>
            <p><?= $msg ?></p>
        </div>
        <button class="toast-close" onclick="dismissToast()">&times;</button>
    </div>
    <script>
        function dismissToast() {
            const t = document.getElementById('toastMsg');
            t.style.animation = 'toastOut 0.4s ease forwards';
            setTimeout(() => t.remove(), 400);
        }
        setTimeout(dismissToast, 5000);
    </script>
<?php endif; ?>

    <div class="reg-wrapper">
        <div class="reg-card">
            <div class="logo-area">
                 <a href="/"><img src="/assets/img/logo2026.png" alt="Koketsu Logo"></a>
            </div>
            
            <h1>CRIAR CONTA</h1>
            <p class="subtitle">EXPERIMENTE A EXCELÊNCIA KOKETSU</p>

            <form action="/backend/register" method="POST">
                <div class="form-group">
                    <input type="text" name="nome_usuarios" placeholder="Nome Completo" required>
                    <i class="fa fa-user"></i>
                </div>

                <div class="form-group">
                    <input type="email" name="email_usuarios" placeholder="E-mail" required>
                    <i class="fa fa-envelope"></i>
                </div>

                <div class="form-group">
                    <input type="password" name="senha_usuarios" placeholder="Senha" required>
                    <i class="fa fa-lock"></i>
                </div>
                
                <div class="form-group">
                    <input type="password" name="senha_confirm" placeholder="Confirmar Senha" required>
                    <i class="fa fa-shield-halved"></i>
                </div>

                <button type="submit" class="btn-submit">Registrar Agora</button>
            </form>

            <div class="links-area">
                Já possui uma conta? <a href="/backend/login">Fazer Login</a>
            </div>
        </div>
        
        <div class="footer">
            KOKETSU STORE &copy; <?= date('Y') ?>
        </div>
    </div>

</body>
</html>