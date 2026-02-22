<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    body {
        background: #080808; /* Fundo levemente mais escuro para contraste */
        font-family: 'Inter', sans-serif; /* Uma fonte mais moderna */
        margin: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
    }

    .login-wrapper {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at center, #1a1a1a 0%, #080808 100%);
    }

    .login-card {
        width: 100%;
        max-width: 500px; /* Aumentado de 400px para 500px */
        padding: 60px;    /* Aumentado para dar mais "ar" ao conteúdo */
        background: rgba(20, 20, 20, 0.95);
        border-radius: 24px;
        border: 1px solid rgba(200, 170, 98, 0.2); /* Borda dourada sutil */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
        text-align: center;
    }

    .login-card h1 {
        color: #C8AA62;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .input-group {
        position: relative;
        margin-bottom: 25px;
    }

    .input-group i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #C8AA62; /* Ícone já nasce dourado */
        font-size: 20px;
        opacity: 0.6;
    }

    .login-card input {
        width: 100%;
        height: 65px; /* Inputs mais altos */
        background: #111 !important;
        border: 1px solid #333 !important;
        border-radius: 12px !important;
        padding: 10px 20px 10px 55px !important;
        color: #fff !important;
        font-size: 18px !important;
        transition: all 0.4s ease;
    }

    .login-card input:focus {
        border-color: #C8AA62 !important;
        background: #151515 !important;
        box-shadow: 0 0 15px rgba(200, 170, 98, 0.15);
        outline: none;
    }

    .btn-login {
        width: 100%;
        height: 65px;
        background: linear-gradient(135deg, #C8AA62 0%, #8A6D2D 100%);
        border: none;
        border-radius: 12px;
        color: #000;
        font-size: 20px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 15px;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(200, 170, 98, 0.3);
        filter: brightness(1.1);
    }

    .footer-brand {
        margin-top: 40px;
        color: #444;
        font-size: 14px;
        letter-spacing: 1px;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <img src="/assets/img/logo2026.png" alt="Logo" height="130" style="margin-bottom: 20px;">
        
        <h1>Acesso Restrito</h1>

        <form action="/backend/adminlogin" method="POST">
            <div class="input-group">
                <i class="fa fa-user-shield"></i>
                <input type="email" name="email_usuarios" placeholder="E-mail Administrativo" required>
            </div>

            <div class="input-group">
                <i class="fa fa-key"></i>
                <input type="password" name="senha_usuarios" placeholder="Senha de Acesso" required>
            </div>

            <button type="submit" class="btn-login">
                Entrar no Sistema
            </button>
        </form>

        <div class="footer-brand">
            SISTEMA KOKETSU &copy; 2026
        </div>
    </div>
</div>