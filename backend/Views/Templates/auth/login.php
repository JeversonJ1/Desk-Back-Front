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
    <title>Login - Koketsu Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/img/logo2026.png">
    <style>
        :root {
            --bg-dark: #050505;
            --card-bg: #0f0f0f;
            --accent: #f2cc7d; /* Dourado Elite */
            --accent-bright: #ffea00;
            --text-main: #FFFFFF;
            --text-muted: #777777;
            --input-bg: rgba(255, 255, 255, 0.03);
            --border-gold: rgba(242, 204, 125, 0.15);
        }

        body {
            background-color: var(--bg-dark);
            background-image: radial-gradient(circle at center, #111 0%, #000 100%);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-main);
            overflow: hidden;
        }

        /* Aura Dourada atrás do Card */
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-wrapper::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            background: var(--accent);
            filter: blur(150px);
            opacity: 0.1;
            z-index: -1;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeIn 1s ease;
        }

        .logo-area img {
            height: 90px;
            filter: drop-shadow(0 0 20px rgba(242, 204, 125, 0.2));
            transition: 0.5s;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.9);
            backdrop-filter: blur(10px);
            animation: slideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card h1 {
            font-family: 'Oswald', sans-serif;
            color: var(--accent);
            font-size: 2.2rem;
            margin: 0 0 10px 0;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 4px;
        }

        .login-card p.subtitle {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 40px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #444;
            transition: 0.3s;
        }

        .form-group input {
            width: 100%;
            height: 60px;
            background: var(--input-bg);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 0 20px 0 55px;
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: var(--accent);
            background: rgba(242, 204, 125, 0.05);
            outline: none;
            box-shadow: 0 0 15px rgba(242, 204, 125, 0.1);
        }

        .form-group input:focus + i {
            color: var(--accent);
        }

        .btn-submit {
            width: 100%;
            height: 60px;
            background: linear-gradient(135deg, #f2cc7d 0%, #b8860b 100%);
            color: #000;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.4s ease;
            margin-top: 15px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(184, 134, 11, 0.4);
            filter: brightness(1.1);
        }

        .links-area {
            margin-top: 35px;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .links-area a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            margin-left: 5px;
        }

        .links-area a:hover {
            color: #fff;
            text-shadow: 0 0 10px var(--accent);
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.7rem;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 3px;
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
        .toast-icon { font-size: 1.6rem; flex-shrink: 0; }
        .toast-balloon.success .toast-icon { color: #4ade80; }
        .toast-balloon.error   .toast-icon { color: #f87171; }
        .toast-balloon.erros   .toast-icon { color: #fb923c; }
        .toast-body p { margin: 0; color: #fff; font-size: 0.9rem; line-height: 1.5; }
        .toast-body strong {
            display: block; font-size: 0.78rem; text-transform: uppercase;
            letter-spacing: 1.5px; margin-bottom: 4px;
        }
        .toast-balloon.success .toast-body strong { color: #4ade80; }
        .toast-balloon.error   .toast-body strong { color: #f87171; }
        .toast-balloon.erros   .toast-body strong { color: #fb923c; }
        .toast-close {
            background: none; border: none; color: #555; font-size: 1.2rem;
            cursor: pointer; margin-left: auto; flex-shrink: 0; transition: color 0.2s;
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

    <div class="login-wrapper">

        <div class="login-card">
            <div class="logo-area">
            <a href="/"><img src="/assets/img/logo2026.png" alt="Koketsu Logo"></a>
        </div>
            <h1>BEM-VINDO</h1>
            <p class="subtitle">Acesse sua área exclusiva</p>

            <form action="/backend/login" method="POST">
                <div class="form-group">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email_usuarios" placeholder="E-mail de acesso" required>
                </div>

                <div class="form-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="senha_usuarios" placeholder="Sua senha secreta" required>
                </div>

                <button type="submit" class="btn-submit">Entrar no Painel</button>
            </form>

            <div class="links-area">
                Não faz parte da elite? <a href="/backend/register">Cadastre-se</a>
            </div>
        </div>

        <div class="footer">
            &copy; <?= date('Y') ?> KOKETSU STORE &bull; LUXURY GRIFE
        </div>
    </div>
</body>
</html>