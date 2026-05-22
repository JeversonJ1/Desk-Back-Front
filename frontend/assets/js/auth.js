/**
 * auth.js - Gerenciamento de autenticação do cliente no site principal
 * Controla: login modal, sessão, avatar/dropdown na navbar
 */

(function () {
    'use strict';

    const AUTH_API = '/api/check_auth.php';
    const LOGIN_API = '/api/login_api.php';
    const LOGOUT_URL = '/backend/logout';
    const DEFAULT_AVATAR = '/assets/img/logo2026.png';

    // === Elementos DOM ===
    function getEls() {
        return {
            // Navbar
            loginBtn: document.getElementById('navLoginBtn'),
            userNav: document.getElementById('navUserContainer'),
            avatarBtn: document.getElementById('userAvatarBtn'),
            avatarImg: document.getElementById('userAvatarImg'),
            avatarFallback: document.getElementById('userAvatarFallback'),
            userName: document.getElementById('navUserName'),
            dropdown: document.getElementById('userDropdown'),
            ddAvatar: document.getElementById('ddAvatarImg'),
            ddAvatarFallback: document.getElementById('ddAvatarFallback'),
            ddName: document.getElementById('ddUserName'),
            ddProfileLink: document.getElementById('ddProfileLink'),
            // Modal
            overlay: document.getElementById('loginModalOverlay'),
            closeBtn: document.getElementById('loginModalClose'),
            form: document.getElementById('loginModalForm'),
            emailInput: document.getElementById('loginModalEmail'),
            senhaInput: document.getElementById('loginModalSenha'),
            submitBtn: document.getElementById('loginModalSubmit'),
            errorBox: document.getElementById('loginModalError'),
        };
    }

    // === Mostrar estado logado na navbar ===
    function showLoggedIn(user) {
        const els = getEls();
        if (!els.loginBtn || !els.userNav) return;

        els.loginBtn.style.display = 'none';
        els.userNav.style.display = 'flex';

        // Nome
        if (els.userName) {
            els.userName.textContent = (user.nome || 'Cliente').split(' ')[0];
        }

        // Avatar
        const hasFoto = user.foto && user.foto !== '/img/logoperf.jpg' && user.foto !== '';
        if (hasFoto) {
            if (els.avatarImg) { els.avatarImg.src = user.foto; els.avatarImg.style.display = 'block'; }
            if (els.avatarFallback) els.avatarFallback.style.display = 'none';
            if (els.ddAvatar) { els.ddAvatar.src = user.foto; els.ddAvatar.style.display = 'block'; }
            if (els.ddAvatarFallback) els.ddAvatarFallback.style.display = 'none';
        } else {
            if (els.avatarImg) els.avatarImg.style.display = 'none';
            if (els.avatarFallback) els.avatarFallback.style.display = 'flex';
            if (els.ddAvatar) els.ddAvatar.style.display = 'none';
            if (els.ddAvatarFallback) els.ddAvatarFallback.style.display = 'flex';
        }

        // Dropdown nome e link perfil
        if (els.ddName) els.ddName.textContent = user.nome || 'Cliente';

        const ddBadge = document.querySelector('.dd-badge');
        const userDropdownLinks = document.querySelector('.user-dropdown-links');

        if (user.tipo === 'admin') {
            if (ddBadge) {
                ddBadge.className = 'dd-badge inline-flex items-center gap-1 text-[9px] font-black text-white uppercase tracking-widest bg-purple-600 px-2 py-0.5 rounded-full mt-0.5';
                ddBadge.innerHTML = '<i class="bi bi-shield-fill text-[7px]"></i> ADMINISTRADOR';
            }
            if (userDropdownLinks) {
                userDropdownLinks.innerHTML = `
                    <a href="/backend/admin/dashboard" class="flex items-center gap-3 px-3 py-2.5 text-[13px] text-gray-300 hover:text-[#F2C84B] hover:bg-[#F2C84B]/8 rounded-xl transition-all">
                      <i class="bi bi-speedometer2 text-base text-gray-500"></i>
                      <span class="font-semibold">Painel Admin</span>
                    </a>
                    <div class="h-px bg-white/5 my-1 mx-2"></div>
                    <a class="logout-link flex items-center gap-3 px-3 py-2.5 text-[13px] text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-all" href="/backend/logout">
                      <i class="bi bi-box-arrow-right text-base"></i>
                      <span class="font-semibold">Sair da conta</span>
                    </a>
                `;
            }
        } else {
            if (ddBadge) {
                ddBadge.className = 'dd-badge inline-flex items-center gap-1 text-[9px] font-black text-black uppercase tracking-widest bg-[#F2C84B] px-2 py-0.5 rounded-full mt-0.5';
                ddBadge.innerHTML = '<i class="bi bi-star-fill text-[7px]"></i> MEMBRO KOKETSU';
            }
            if (userDropdownLinks) {
                userDropdownLinks.innerHTML = `
                    <a id="ddProfileLink" href="/backend/cliente/meu-perfil/${user.id || '0'}" class="flex items-center gap-3 px-3 py-2.5 text-[13px] text-gray-300 hover:text-[#F2C84B] hover:bg-[#F2C84B]/8 rounded-xl transition-all">
                      <i class="bi bi-person text-base text-gray-500"></i>
                      <span class="font-semibold">Meu Perfil</span>
                    </a>
                    <a href="/backend/cliente/pedidos" class="flex items-center gap-3 px-3 py-2.5 text-[13px] text-gray-300 hover:text-[#F2C84B] hover:bg-[#F2C84B]/8 rounded-xl transition-all">
                      <i class="bi bi-bag-check text-base text-gray-500"></i>
                      <span class="font-semibold">Meus Pedidos</span>
                    </a>
                    <a href="/backend/configuracoes" class="flex items-center gap-3 px-3 py-2.5 text-[13px] text-gray-300 hover:text-[#F2C84B] hover:bg-[#F2C84B]/8 rounded-xl transition-all">
                      <i class="bi bi-gear text-base text-gray-500"></i>
                      <span class="font-semibold">Preferências</span>
                    </a>
                    <div class="h-px bg-white/5 my-1 mx-2"></div>
                    <a href="#" id="ddLogoutBtn" class="logout-link flex items-center gap-3 px-3 py-2.5 text-[13px] text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-all">
                      <i class="bi bi-box-arrow-right text-base"></i>
                      <span class="font-semibold">Sair da conta</span>
                    </a>
                `;
                const newLogoutBtn = document.getElementById('ddLogoutBtn');
                if (newLogoutBtn) newLogoutBtn.addEventListener('click', handleLogout);
            }
        }
    }

    // === Mostrar estado deslogado na navbar ===
    function showLoggedOut() {
        const els = getEls();
        if (!els.loginBtn || !els.userNav) return;
        els.loginBtn.style.display = 'flex';
        els.userNav.style.display = 'none';
        closeDropdown();
    }

    // === Verificar autenticação ===
    async function checkAuth() {
        try {
            const res = await fetch(AUTH_API, { credentials: 'same-origin' });
            const data = await res.json();
            if (data.authenticated && data.user) {
                showLoggedIn(data.user);
            } else {
                showLoggedOut();
            }
        } catch (e) {
            console.warn('[Auth] Erro ao verificar sessão:', e);
            showLoggedOut();
        }
    }

    // === Login via modal ===
    async function handleLogin(e) {
        e.preventDefault();
        const els = getEls();
        const email = els.emailInput?.value?.trim();
        const senha = els.senhaInput?.value;

        if (!email || !senha) {
            showError('Preencha todos os campos.');
            return;
        }

        // Loading state
        els.submitBtn.disabled = true;
        els.submitBtn.classList.add('loading');
        hideError();

        try {
            const res = await fetch(LOGIN_API, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, senha })
            });

            const data = await res.json();

            if (data.success && data.user) {
                if (data.redirect_url) {
                    // Admin: redireciona para o painel
                    window.location.href = data.redirect_url;
                    return;
                }
                closeModal();
                showLoggedIn(data.user);
                // Limpar form
                if (els.form) els.form.reset();
            } else {
                showError(data.message || 'E-mail ou senha incorretos.');
            }
        } catch (err) {
            console.error('[Auth] Erro no login:', err);
            showError('Erro de conexão. Tente novamente.');
        } finally {
            els.submitBtn.disabled = false;
            els.submitBtn.classList.remove('loading');
        }
    }

    // === Logout ===
    async function handleLogout(e) {
        e.preventDefault();
        try {
            await fetch(LOGOUT_URL, { credentials: 'same-origin', redirect: 'follow' });
        } catch (err) {
            // fallback: vai funcionar mesmo assim pois a sessão é destruída
        }
        showLoggedOut();
        // Redireciona para home, saindo de áreas protegidas (admin ou painel cliente)
        window.location.href = '/';
    }

    // === Modal Controls ===
    function openModal() {
        const els = getEls();
        if (els.overlay) {
            els.overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            // Focus no email
            setTimeout(() => els.emailInput?.focus(), 300);
        }
    }

    function closeModal() {
        const els = getEls();
        if (els.overlay) {
            els.overlay.classList.remove('active');
            document.body.style.overflow = '';
            hideError();
        }
    }

    // === Dropdown Controls ===
    function toggleDropdown() {
        const els = getEls();
        if (els.dropdown) {
            els.dropdown.classList.toggle('open');
        }
    }

    function closeDropdown() {
        const els = getEls();
        if (els.dropdown) {
            els.dropdown.classList.remove('open');
        }
    }

    // === Error display ===
    function showError(msg) {
        const els = getEls();
        if (els.errorBox) {
            els.errorBox.textContent = msg;
            els.errorBox.classList.add('visible');
        }
    }

    function hideError() {
        const els = getEls();
        if (els.errorBox) {
            els.errorBox.classList.remove('visible');
        }
    }

    // === Event listeners ===
    function initEvents() {
        const els = getEls();

        // Botão login na navbar → abrir modal
        if (els.loginBtn) {
            els.loginBtn.addEventListener('click', function (e) {
                e.preventDefault();
                openModal();
            });
        }

        // Fechar modal
        if (els.closeBtn) {
            els.closeBtn.addEventListener('click', closeModal);
        }

        // Fechar modal ao clicar fora
        if (els.overlay) {
            els.overlay.addEventListener('click', function (e) {
                if (e.target === els.overlay) closeModal();
            });
        }

        // ESC para fechar modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal();
                closeDropdown();
            }
        });

        // Submit do form
        if (els.form) {
            els.form.addEventListener('submit', handleLogin);
        }

        // Toggle dropdown ao clicar no avatar
        if (els.avatarBtn) {
            els.avatarBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleDropdown();
            });
        }

        // Fechar dropdown ao clicar fora
        document.addEventListener('click', function (e) {
            const els = getEls();
            if (els.userNav && !els.userNav.contains(e.target)) {
                closeDropdown();
            }
        });

        // Logout e Perfil via Delegação de Eventos (pois o HTML é recriado dinamicamente)
        document.addEventListener('click', function (e) {
            // Logout
            let logoutBtn = e.target.closest('#ddLogoutBtn');
            if (logoutBtn) {
                e.preventDefault();
                handleLogout(e);
            }
        });
    }

    // === Inicializar ===
    window.AuthManager = {
        init: function() {
            initEvents();
            checkAuth();
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        window.AuthManager.init();
    });

})();
