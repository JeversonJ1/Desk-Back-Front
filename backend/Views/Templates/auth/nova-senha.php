<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$flash = null;
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
// Token vem pela URL
$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha - Koketsu Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/img/logo2026.png">
    <style>
        :root {
            --bg-dark:#050505; --card-bg:#0f0f0f; --accent:#f2cc7d;
            --text-main:#FFFFFF; --text-muted:#777;
            --input-bg:rgba(255,255,255,0.03); --border-gold:rgba(242,204,125,0.15);
        }
        body { background-color:var(--bg-dark); background-image:radial-gradient(circle at center,#111 0%,#000 100%); font-family:'Montserrat',sans-serif; margin:0; display:flex; align-items:center; justify-content:center; min-height:100vh; color:var(--text-main); }
        .wrapper { width:100%; max-width:440px; padding:20px; position:relative; z-index:1; }
        .wrapper::after { content:''; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:300px; height:300px; background:var(--accent); filter:blur(150px); opacity:0.08; z-index:-1; }
        .logo-area { text-align:center; margin-bottom:36px; }
        .logo-area img { height:80px; filter:drop-shadow(0 0 20px rgba(242,204,125,0.2)); }
        .card { background:var(--card-bg); border:1px solid var(--border-gold); border-radius:24px; padding:50px 40px; box-shadow:0 40px 80px rgba(0,0,0,0.9); animation:slideUp 0.7s cubic-bezier(0.2,0.8,0.2,1); }
        @keyframes slideUp { from{opacity:0;transform:translateY(30px);} to{opacity:1;transform:translateY(0);} }
        .icon-box { display:flex; align-items:center; justify-content:center; width:70px; height:70px; background:rgba(242,204,125,0.08); border:1px solid rgba(242,204,125,0.2); border-radius:50%; margin:0 auto 28px; font-size:28px; color:var(--accent); }
        h1 { font-family:'Oswald',sans-serif; color:var(--accent); font-size:1.9rem; margin:0 0 8px; text-transform:uppercase; text-align:center; letter-spacing:3px; }
        .subtitle { color:var(--text-muted); font-size:0.82rem; margin-bottom:36px; text-align:center; letter-spacing:1.5px; text-transform:uppercase; line-height:1.6; }
        .form-group { margin-bottom:20px; position:relative; }
        .form-group i.field-icon { position:absolute; left:20px; top:50%; transform:translateY(-50%); color:#444; transition:0.3s; pointer-events:none; }
        .form-group input { width:100%; height:58px; background:var(--input-bg); border:1px solid rgba(255,255,255,0.06); border-radius:14px; padding:0 50px 0 52px; color:#fff; font-size:0.93rem; transition:all 0.3s; box-sizing:border-box; outline:none; }
        .form-group input:focus { border-color:var(--accent); background:rgba(242,204,125,0.04); box-shadow:0 0 12px rgba(242,204,125,0.08); }
        .toggle-pwd { position:absolute; right:18px; top:50%; transform:translateY(-50%); background:none; border:none; color:#555; font-size:16px; cursor:pointer; transition:color 0.2s; padding:4px; }
        .toggle-pwd:hover { color:var(--accent); }
        .strength-bar { height:4px; border-radius:4px; margin-top:8px; background:#222; overflow:hidden; }
        .strength-bar-inner { height:100%; width:0; border-radius:4px; transition:width 0.4s, background 0.4s; }
        .strength-label { font-size:10px; color:var(--text-muted); margin-top:4px; text-align:right; letter-spacing:1px; text-transform:uppercase; }
        .btn-submit { width:100%; height:58px; background:linear-gradient(135deg,#f2cc7d 0%,#b8860b 100%); color:#000; border:none; border-radius:14px; font-size:0.95rem; font-family:'Oswald',sans-serif; font-weight:700; text-transform:uppercase; letter-spacing:2px; cursor:pointer; transition:all 0.3s; margin-top:10px; }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(184,134,11,0.4); filter:brightness(1.08); }
        .links-area { margin-top:28px; text-align:center; font-size:0.83rem; color:var(--text-muted); }
        .links-area a { color:var(--accent); text-decoration:none; font-weight:600; }
        .toast-balloon { position:fixed; top:24px; right:24px; z-index:9999; display:flex; align-items:center; gap:14px; background:#1a1a1a; border-radius:16px; padding:18px 24px; min-width:300px; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.7); border-left:4px solid; animation:toastIn 0.5s cubic-bezier(0.2,0.8,0.2,1) forwards; opacity:0; }
        .toast-balloon.success{border-color:#4ade80;} .toast-balloon.error{border-color:#f87171;}
        .toast-icon{font-size:1.6rem;flex-shrink:0;}
        .toast-balloon.success .toast-icon{color:#4ade80;} .toast-balloon.error .toast-icon{color:#f87171;}
        .toast-body p{margin:0;color:#fff;font-size:0.9rem;line-height:1.5;}
        .toast-body strong{display:block;font-size:0.78rem;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:4px;}
        .toast-balloon.success .toast-body strong{color:#4ade80;} .toast-balloon.error .toast-body strong{color:#f87171;}
        .toast-close{background:none;border:none;color:#555;font-size:1.2rem;cursor:pointer;margin-left:auto;}
        @keyframes toastIn{from{opacity:0;transform:translateX(50px);}to{opacity:1;transform:translateX(0);}}
        @keyframes toastOut{from{opacity:1;transform:translateX(0);}to{opacity:0;transform:translateX(50px);}}
        .error-box { text-align:center; padding:20px 0; }
        .error-box .err-icon { width:70px;height:70px;background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:30px;color:#f87171; }
        .error-box h2{color:#f87171;font-family:'Oswald',sans-serif;letter-spacing:2px;text-transform:uppercase;margin-bottom:10px;}
        .error-box p{color:var(--text-muted);font-size:0.88rem;line-height:1.7;}
    </style>
</head>
<body>

<?php if ($flash): ?>
    <?php
        $type  = htmlspecialchars($flash['type']);
        $msg   = $flash['message'];
        $icon  = ($type === 'success') ? '&#10003;' : '&#10005;';
        $label = ($type === 'success') ? 'Sucesso' : 'Erro';
    ?>
    <div class="toast-balloon <?= $type ?>" id="toastMsg">
        <div class="toast-icon"><?= $icon ?></div>
        <div class="toast-body"><strong><?= $label ?></strong><p><?= $msg ?></p></div>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <script>setTimeout(()=>{const t=document.getElementById('toastMsg');if(t){t.style.animation='toastOut 0.4s ease forwards';setTimeout(()=>t.remove(),400);}},5000);</script>
<?php endif; ?>

<div class="wrapper">
    <div class="logo-area">
        <a href="/"><img src="/assets/img/logo2026.png" alt="Koketsu Logo"></a>
    </div>

    <div class="card">
        <?php if (empty($token)): ?>
        <!-- Token inválido -->
        <div class="error-box">
            <div class="err-icon"><i class="fas fa-unlink"></i></div>
            <h2>Link Inválido</h2>
            <p>Este link de recuperação é inválido ou já expirou.<br>Solicite um novo link abaixo.</p>
            <a href="/recuperar-senha" style="display:inline-block;margin-top:20px;padding:14px 32px;background:linear-gradient(135deg,#f2cc7d,#b8860b);color:#000;border-radius:12px;font-weight:700;font-family:'Oswald',sans-serif;text-transform:uppercase;letter-spacing:2px;text-decoration:none;">Solicitar Novo Link</a>
        </div>
        <?php else: ?>
        <!-- Formulário nova senha -->
        <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
        <h1>Nova Senha</h1>
        <p class="subtitle">Crie uma senha forte<br>para proteger sua conta</p>

        <form action="/nova-senha" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <i class="fas fa-lock field-icon"></i>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Nova senha (mín. 8 caracteres)" required minlength="8" oninput="checkStrength(this.value)">
                <button type="button" class="toggle-pwd" onclick="togglePwd('nova_senha', this)" tabindex="-1">
                    <i class="fas fa-eye"></i>
                </button>
                <div class="strength-bar"><div class="strength-bar-inner" id="strengthBar"></div></div>
                <div class="strength-label" id="strengthLabel">força da senha</div>
            </div>

            <div class="form-group">
                <i class="fas fa-lock field-icon"></i>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirmar nova senha" required minlength="8">
                <button type="button" class="toggle-pwd" onclick="togglePwd('confirmar_senha', this)" tabindex="-1">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <button type="submit" class="btn-submit" onclick="return validateForm()">
                <i class="fas fa-check-circle" style="margin-right:8px;"></i> Redefinir Senha
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<script>
function togglePwd(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
        btn.style.color = '#f2cc7d';
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
        btn.style.color = '';
    }
}

function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    if (!bar) return;
    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const configs = [
        { pct: '0%',   color: '#333',    text: 'força da senha' },
        { pct: '25%',  color: '#ef4444', text: 'Fraca' },
        { pct: '50%',  color: '#f97316', text: 'Razoável' },
        { pct: '75%',  color: '#eab308', text: 'Boa' },
        { pct: '100%', color: '#22c55e', text: 'Forte' },
    ];
    const cfg = configs[score];
    bar.style.width     = cfg.pct;
    bar.style.background = cfg.color;
    label.textContent   = cfg.text;
    label.style.color   = score === 0 ? '#555' : cfg.color;
}

function validateForm() {
    const a = document.getElementById('nova_senha').value;
    const b = document.getElementById('confirmar_senha').value;
    if (a !== b) { alert('As senhas não coincidem!'); return false; }
    if (a.length < 8) { alert('A senha deve ter pelo menos 8 caracteres.'); return false; }
    return true;
}
</script>
</body>
</html>
