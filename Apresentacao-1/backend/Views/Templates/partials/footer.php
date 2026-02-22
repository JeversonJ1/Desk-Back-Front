</div> <!-- Fim da div w3-main aberta no header.php -->

<footer class="koketsu-footer-min">
    <div class="footer-min-container">
        <div class="footer-min-left">
            <img src="/assets/img/logo2026.png" alt="Koketsu" class="logo-min">
            <span class="copyright-min">&copy; <?= date('Y'); ?> <span class="accent-min">Koketsu Grife</span>. Todos os direitos reservados.</span>
        </div>
        <div class="footer-min-right">
            <span class="version-tag-min">System v2.5.0</span>
            <div class="social-min">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</footer>

<style>
    .koketsu-footer-min {
        background: var(--bg-main);
        padding: 30px 40px;
        border-top: 1px solid var(--border-color);
        margin-top: 60px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .footer-min-container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .footer-min-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .logo-min {
        height: 32px;
        filter: grayscale(1) brightness(1.5);
        opacity: 0.8;
        transition: 0.3s;
    }

    .logo-min:hover {
        filter: none;
        opacity: 1;
    }

    .copyright-min {
        color: var(--text-muted);
        font-size: 0.85rem;
        letter-spacing: 0.3px;
    }

    .accent-min {
        color: var(--text-main);
        font-weight: 700;
    }

    .footer-min-right {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .version-tag-min {
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 600;
        background: var(--bg-card);
        padding: 4px 10px;
        border-radius: 4px;
        border: 1px solid var(--border-color);
    }

    .social-min {
        display: flex;
        gap: 15px;
    }

    .social-min a {
        color: var(--text-muted);
        font-size: 1.1rem;
        transition: 0.3s;
    }

    .social-min a:hover {
        color: var(--accent);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .footer-min-container {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }
        .footer-min-left {
            flex-direction: column;
            gap: 10px;
        }
        .footer-min-right {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<script> 
    // Funções para abrir/fechar o menu lateral em telas pequenas
    function w3_open() {
        const mySidebar = document.getElementById('mySidebar');
        const myOverlay = document.getElementById('myOverlay');
        if (mySidebar.style.display === 'block') {
            w3_close();
        } else {
            mySidebar.style.display = 'block';
            myOverlay.style.display = 'block';
        }
    }

    function w3_close() {
        const mySidebar = document.getElementById('mySidebar');
        const myOverlay = document.getElementById('myOverlay');
        mySidebar.style.display = 'none';
        myOverlay.style.display = 'none';
    }

    // Script para desaparecer as mensagens de alerta/flash
    setTimeout(() => { 
        const alert = document.querySelector('.alert'); 
        if(alert){ 
            alert.style.opacity = '0'; 
            alert.style.transform = 'translateY(-20px)'; 
            setTimeout(() => alert.remove(), 400); 
        } 
    }, 3000); 

    // Lógica para troca de logo baseada no tema
    function updateLogo() {
        const logo = document.getElementById('main-logo');
        if (!logo) return;
        const isLight = document.documentElement.classList.contains('theme-light');
        logo.src = isLight ? '/assets/img/logo2026.png' : '/assets/img/logo2026.png';
    }

    // Executa ao carregar e observa mudanças (se houver um switch dinâmico)
    document.addEventListener('DOMContentLoaded', updateLogo);
</script>
</body>
</html>
