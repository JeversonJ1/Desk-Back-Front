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
    <title>Recuperar Senha - Koketsu Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/img/logo2026.png">
    <style>
        :root {
            --bg-dark: #050505;
            --card-bg: #0f0f0f;
            --accent: #f2cc7d;
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
        }
        .wrapper {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        .wrapper::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 300px; height: 300px;
            background: var(--accent);
            filter: blur(150px);
            opacity: 0.08;
            z-index: -1;
        }
        .logo-area { text-align: center; margin-bottom: 36px; }
        .logo-area img { height: 80px; filter: drop-shadow(0 0 20px rgba(242,204,125,0.2)); }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.9);
            animation: slideUp 0.7s cubic-bezier(0.2,0.8,0.2,1);
        }
        @keyframes slideUp {
            from { opacity:0; transform:translateY(30px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .card h1 {
            font-family: 'Oswald', sans-serif;
            color: var(--accent);
            font-size: 1.9rem;
            margin: 0 0 8px;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 3px;
        }
        .card .subtitle {
            color: var(--text-muted);
            font-size: 0.82rem;
            margin-bottom: 36px;
            text-align: center;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            line-height: 1.6;
        }
        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px; height: 70px;
            background: rgba(242,204,125,0.08);
            border: 1px solid rgba(242,204,125,0.2);
            border-radius: 50%;
            margin: 0 auto 28px;
            font-size: 28px;
            color: var(--accent);
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group i.field-icon {
            position: absolute;
            left: 20px; top: 50%;
            transform: translateY(-50%);
            color: #444; transition: 0.3s;
        }
        .form-group input {
            width: 100%; height: 58px;
            background: var(--input-bg);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 0 20px 0 52px;
            color: #fff; font-size: 0.93rem;
            transition: all 0.3s; box-sizing: border-box;
            outline: none;
        }
        .form-group input:focus {
            border-color: var(--accent);
            background: rgba(242,204,125,0.04);
            box-shadow: 0 0 12px rgba(242,204,125,0.08);
        }
        .btn-submit {
            width: 100%; height: 58px;
            background: linear-gradient(135deg, #f2cc7d 0%, #b8860b 100%);
            color: #000; border: none; border-radius: 14px;
            font-size: 0.95rem;
            font-family: 'Oswald', sans-serif;
            font-weight: 700; text-transform: uppercase;
            letter-spacing: 2px; cursor: pointer;
            transition: all 0.3s; margin-top: 10px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(184,134,11,0.4);
            filter: brightness(1.08);
        }
        .links-area {
            margin-top: 30px; text-align: center;
            font-size: 0.83rem; color: var(--text-muted);
        }
        .links-area a {
            color: var(--accent); text-decoration: none;
            font-weight: 600; transition: 0.3s; margin-left: 4px;
        }
        .links-area a:hover { color: #fff; }

        /* Toast */
        .toast-balloon {
            position: fixed; top: 24px; right: 24px; z-index: 9999;
            display: flex; align-items: center; gap: 14px;
            background: #1a1a1a; border-radius: 16px;
            padding: 18px 24px; min-width: 300px; max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.7); border-left: 4px solid;
            animation: toastIn 0.5s cubic-bezier(0.2,0.8,0.2,1) forwards; opacity: 0;
        }
        .toast-balloon.success { border-color:#4ade80; }
        .toast-balloon.error   { border-color:#f87171; }
        .toast-balloon.erros   { border-color:#fb923c; }
        .toast-icon { font-size:1.6rem; flex-shrink:0; }
        .toast-balloon.success .toast-icon { color:#4ade80; }
        .toast-balloon.error   .toast-icon { color:#f87171; }
        .toast-balloon.erros   .toast-icon { color:#fb923c; }
        .toast-body p { margin:0; color:#fff; font-size:0.9rem; line-height:1.5; }
        .toast-body strong { display:block; font-size:0.78rem; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:4px; }
        .toast-balloon.success .toast-body strong { color:#4ade80; }
        .toast-balloon.error   .toast-body strong { color:#f87171; }
        .toast-balloon.erros   .toast-body strong { color:#fb923c; }
        .toast-close { background:none; border:none; color:#555; font-size:1.2rem; cursor:pointer; margin-left:auto; flex-shrink:0; transition:color 0.2s; }
        .toast-close:hover { color:#fff; }
        @keyframes toastIn { from{opacity:0;transform:translateX(50px);} to{opacity:1;transform:translateX(0);} }
        @keyframes toastOut { from{opacity:1;transform:translateX(0);} to{opacity:0;transform:translateX(50px);} }

        .step { display:none; }
        .step.active { display:block; }
        .success-box {
            text-align: center; padding: 20px 0;
        }
        .success-box .check-icon {
            width: 80px; height: 80px;
            background: rgba(74,222,128,0.1);
            border: 2px solid rgba(74,222,128,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            font-size: 36px; color: #4ade80;
        }
        .success-box h2 { color: #4ade80; font-family:'Oswald',sans-serif; letter-spacing:2px; text-transform:uppercase; margin-bottom:10px; }
        .success-box p { color: var(--text-muted); font-size: 0.88rem; line-height: 1.7; }
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

<div class="wrapper">
    <div class="logo-area">
        <a href="/"><img src="/assets/img/logo2026.png" alt="Koketsu Logo"></a>
    </div>

    <div class="card">
        <!-- STEP 1: Solicitar e-mail -->
        <div class="step active" id="step1">
            <div class="icon-box">
                <i class="fas fa-key"></i>
            </div>
            <h1>Recuperar Senha</h1>
            <p class="subtitle">Informe seu e-mail cadastrado<br>e enviaremos um link de redefinição</p>

            <form action="/recuperar-senha" method="POST" id="formRecuperar">
                <div class="form-group">
                    <i class="fas fa-envelope field-icon"></i>
                    <input type="email" name="email_recuperar" placeholder="Seu e-mail de cadastro" required autocomplete="email">
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane" style="margin-right:8px;"></i> Enviar Link
                </button>
            </form>

            <div class="links-area">
                Lembrou a senha? <a href="/login">Fazer login</a>
            </div>
        </div>
    </div>

    <div style="margin-top:30px;text-align:center;font-size:0.7rem;color:#333;text-transform:uppercase;letter-spacing:3px;">
        &copy; <?= date('Y') ?> KOKETSU STORE &bull; LUXURY GRIFE
    </div>
</div>
</body>
</html>
