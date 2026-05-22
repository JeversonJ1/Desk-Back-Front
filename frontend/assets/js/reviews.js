/**
 * Koketsu Grife - Review System
 * Handles product page reviews, submission form, and homepage reviews display.
 */

const ReviewManager = (() => {
    const API_BASE = '/api/avaliacoes.php';
    const AUTH_API = '/api/check_auth.php';
    let currentUser = null;

    /**
     * Helper: Extrai array de avaliações independentemente do formato de resposta
     * Suporta tanto {status, data} quanto array direto
     */
    const extractReviews = (result) => {
        if (Array.isArray(result)) return result;
        if (result && result.data && Array.isArray(result.data)) return result.data;
        if (result && result.status === 'success' && Array.isArray(result.data)) return result.data;
        return [];
    };

    /**
     * Fetch and render reviews for a specific product
     */
    const initProductReviews = async (productId) => {
        const reviewsContainer = document.getElementById('reviews-list');
        if (!reviewsContainer) return;

        // Fetch rating summary in parallel
        initRatingSummary(productId);

        try {
            reviewsContainer.innerHTML = '<div class="flex justify-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-[#F2C84B]"></div></div>';

            const response = await fetch(`${API_BASE}/produto/${productId}`);
            const result = await response.json();
            const reviews = extractReviews(result);

            if (reviews.length > 0) {
                renderProductReviews(reviews);
            } else {
                reviewsContainer.innerHTML = `
                    <div class="text-center py-12">
                        <i class="bi bi-chat-square-text text-4xl text-gray-700 block mb-4"></i>
                        <p class="text-gray-400 text-sm mb-2">Este produto ainda não possui avaliações.</p>
                        <p class="text-[var(--brand-yellow)] text-xs font-bold uppercase tracking-widest">Seja o primeiro a avaliar!</p>
                    </div>`;
            }

            // Check if user can review
            checkUserAbility(productId);
        } catch (error) {
            console.error('Error fetching reviews:', error);
            reviewsContainer.innerHTML = '<p class="text-red-400 text-center py-4">Erro ao carregar avaliações.</p>';
        }
    };

    /**
     * Fetch and render average rating and count
     */
    const initRatingSummary = async (productId) => {
        const starsContainer = document.getElementById('product-rating-stars');
        const countText = document.getElementById('product-rating-count-text');

        if (!starsContainer || !countText) return;

        try {
            const response = await fetch(`${API_BASE}/stats/produto/${productId}`);
            const result = await response.json();

            if (result.status === 'success') {
                const { total, media } = result.data;
                starsContainer.innerHTML = generateStars(media);

                if (total > 0) {
                    countText.innerText = `(${total} ${total === 1 ? 'avaliação' : 'avaliações'})`;
                } else {
                    countText.innerText = '(Sem avaliações - Seja o primeiro!)';
                }
            }
        } catch (error) {
            console.warn('[Review] Error loading rating summary:', error);
        }
    };

    const renderProductReviews = (reviews) => {
        const reviewsContainer = document.getElementById('reviews-list');
        reviewsContainer.innerHTML = reviews.map(review => `
            <div class="review-item-premium fade-in" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; padding: 24px; margin-bottom: 16px; transition: all 0.3s ease;">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-black font-bold text-sm shrink-0" 
                             style="background: linear-gradient(135deg, #F2C84B, #b8880b);">
                            ${(review.nome_cliente || 'C').charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <span class="text-white font-bold text-sm block mb-1">${review.nome_cliente || 'Cliente Koketsu'}</span>
                            <div class="flex text-[#F2C84B] text-xs gap-0.5">
                                ${generateStars(review.nota_avaliacoes)}
                            </div>
                        </div>
                    </div>
                    <span class="text-gray-600 text-[10px] uppercase tracking-wider">${review.data_avaliacao_avaliacoes ? new Date(review.data_avaliacao_avaliacoes).toLocaleDateString('pt-BR') : ''}</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mt-3 italic">&ldquo;${review.comentario_avaliacoes || 'Sem comentário.'}&rdquo;</p>
            </div>
        `).join('');
    };

    /**
     * Check if current user is logged in
     */
    const checkUserAbility = async (productId) => {
        const formContainer = document.getElementById('review-form-container');
        if (!formContainer) return;

        try {
            const authRes = await fetch(AUTH_API);
            const authData = await authRes.json();

            if (authData.authenticated && authData.user) {
                currentUser = authData.user;

                // Show form
                formContainer.classList.remove('d-none', 'hidden');
                setupForm(productId);
            } else {
                // Show login prompt
                formContainer.classList.remove('d-none', 'hidden');
                formContainer.innerHTML = `
                <div class="text-center py-8">
                    <i class="bi bi-lock-fill text-[var(--brand-yellow)] text-3xl mb-4 block"></i>
                    <p class="text-gray-400 text-sm mb-2">Faça login para deixar sua avaliação.</p>
                    <a href="/backend/login" class="inline-block bg-[var(--brand-yellow)] text-black font-bold text-xs uppercase tracking-widest px-6 py-3 rounded-xl hover:brightness-110 transition mt-2">
                        FAZER LOGIN
                    </a>
                </div>`;
            }
        } catch (e) {
            console.warn('[Review] Error checking user ability:', e);
        }
    };


    const setupForm = (productId) => {
        const form = document.getElementById('product-review-form');
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('review-rating-value');

        if (!form || !ratingInput) return;

        // Star selection interaction
        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                const val = parseInt(star.dataset.value);
                highlightStars(val, stars);
            });

            star.addEventListener('mouseleave', () => {
                highlightStars(parseInt(ratingInput.value), stars);
            });

            star.addEventListener('click', () => {
                const val = parseInt(star.dataset.value);
                ratingInput.value = val;
                highlightStars(val, stars);
            });
        });

        // Initialize with 5 stars filled
        highlightStars(parseInt(ratingInput.value) || 5, stars);

        // Form submission
        form.onsubmit = async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-current mr-2 align-middle"></span>ENVIANDO...';
            btn.disabled = true;

            const nota = parseInt(ratingInput.value);
            const comentario = document.getElementById('review-comment')?.value?.trim() || '';

            if (nota < 1 || nota > 5) {
                if (window.Utils && window.Utils.showNotification) {
                    Utils.showNotification('Selecione uma nota de 1 a 5 estrelas.', 'error');
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
                return;
            }

            const payload = {
                id_produto: productId,
                id_usuarios: currentUser.id,
                nota_avaliacoes: nota,
                comentario_avaliacoes: comentario
            };

            try {
                const res = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                let result;
                try {
                    result = await res.json();
                } catch (jsonErr) {
                    const text = await res.text();
                    console.error('Non-JSON response:', text);
                    throw new Error('Servidor retornou resposta inválida.');
                }

                if (result.status === 'success') {
                    if (window.Utils && window.Utils.showNotification) {
                        Utils.showNotification('Sua avaliação foi enviada com sucesso!', 'success');
                    }
                    const formContainer = document.getElementById('review-form-container');
                    if (formContainer) {
                        formContainer.innerHTML = `
                            <div class="text-center py-8 fade-in">
                                <i class="bi bi-star-fill text-[var(--brand-yellow)] text-4xl mb-4 block"></i>
                                <h4 class="text-white font-bold text-lg uppercase tracking-wider mb-2">AVALIAÇÃO RECEBIDA!</h4>
                                <p class="text-gray-400 text-sm">Sua opinião ajuda a manter a elite Koketsu sempre no topo.</p>
                            </div>
                        `;
                    }
                    // Reload reviews after 2 seconds
                    setTimeout(() => initProductReviews(productId), 2000);
                } else {
                    if (window.Utils && window.Utils.showNotification) {
                        Utils.showNotification(result.message || 'Erro ao enviar avaliação.', 'error');
                    }
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (err) {
                console.error('Error submitting review:', err);
                if (window.Utils && window.Utils.showNotification) {
                    Utils.showNotification(`Erro de conexão: ${err.message || 'Tente novamente.'}`, 'error');
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        };
    };

    const highlightStars = (val, starsNodeList) => {
        const stars = starsNodeList || document.querySelectorAll('.rating-star');
        stars.forEach(s => {
            const sVal = parseInt(s.dataset.value);
            if (sVal <= val) {
                s.classList.replace('bi-star', 'bi-star-fill');
                s.classList.add('text-[var(--brand-yellow)]');
            } else {
                s.classList.replace('bi-star-fill', 'bi-star');
                s.classList.remove('text-[var(--brand-yellow)]');
            }
        });
    };

    /**
     * Homepage Reviews — Grid Layout
     */
    /**
     * Homepage Reviews — Carousel Layout
     */
    const initHomeReviews = async () => {
        const track = document.getElementById('home-reviews-track');
        const skeleton = document.getElementById('reviews-skeleton');
        const cta = document.getElementById('reviews-cta');
        if (!track) return;

        let reviews = [];
        let currentIndex = 0;
        let autoplayInterval = null;

        const getItemsVisible = () => {
            const w = window.innerWidth;
            if (w >= 1025) return 3;
            if (w >= 641) return 2;
            return 1;
        };

        const updateCarousel = () => {
            const itemsVisible = getItemsVisible();
            const prevBtn = document.getElementById('carousel-prev-btn');
            const nextBtn = document.getElementById('carousel-next-btn');
            const dotsContainer = document.getElementById('home-reviews-dots');
            
            const maxIndex = Math.max(0, reviews.length - itemsVisible);
            if (currentIndex > maxIndex) currentIndex = maxIndex;

            // Obter a largura do card item
            const card = track.querySelector('.carousel-item-review');
            if (!card) return;

            const cardWidth = card.getBoundingClientRect().width;
            const gap = 24; // correspondente a gap: 24px no CSS

            // Calcula o deslocamento horizontal
            const offset = currentIndex * (cardWidth + gap);
            track.style.transform = `translateX(-${offset}px)`;

            // Atualiza estados dos botões
            if (prevBtn) prevBtn.disabled = (currentIndex === 0);
            if (nextBtn) nextBtn.disabled = (currentIndex === maxIndex);

            // Esconde setas se houver menos avaliações do que o visível
            if (prevBtn && nextBtn) {
                if (reviews.length <= itemsVisible) {
                    prevBtn.classList.add('hidden');
                    nextBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                    nextBtn.classList.remove('hidden');
                }
            }

            // Renderiza os pontos de paginação (dots)
            if (dotsContainer) {
                if (reviews.length <= itemsVisible) {
                    dotsContainer.innerHTML = '';
                } else {
                    const totalPages = reviews.length - itemsVisible + 1;
                    dotsContainer.innerHTML = Array.from({ length: totalPages }, (_, i) =>
                        `<button class="carousel-dot ${i === currentIndex ? 'active' : ''}" data-index="${i}"></button>`
                    ).join('');

                    // Evento de clique nos dots
                    dotsContainer.querySelectorAll('.carousel-dot').forEach(dot => {
                        dot.onclick = () => {
                            currentIndex = parseInt(dot.dataset.index);
                            updateCarousel();
                            startAutoplay(); // Reinicia temporizador
                        };
                    });
                }
            }
        };

        const navigatePrev = () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        };

        const navigateNext = () => {
            const itemsVisible = getItemsVisible();
            const maxIndex = Math.max(0, reviews.length - itemsVisible);
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            } else {
                currentIndex = 0; // Volta para o início (loop)
                updateCarousel();
            }
        };

        const startAutoplay = () => {
            stopAutoplay();
            autoplayInterval = setInterval(() => {
                navigateNext();
            }, 5000);
        };

        const stopAutoplay = () => {
            if (autoplayInterval) clearInterval(autoplayInterval);
        };

        try {
            const resp = await fetch(`${API_BASE}/ultimas`);
            const result = await resp.json();
            reviews = extractReviews(result);

            if (reviews.length === 0) {
                const wrapper = document.getElementById('home-reviews-carousel-wrapper');
                if (wrapper) {
                    wrapper.innerHTML = `
                    <div class="text-center py-12 opacity-30">
                        <i class="bi bi-star text-4xl block mb-4 text-[var(--brand-yellow)]"></i>
                        <p class="text-sm uppercase tracking-widest">Seja o primeiro a avaliar!</p>
                    </div>`;
                }
                return;
            }

            // Calcular média global
            const avg = (reviews.reduce((s, r) => s + parseFloat(r.nota_avaliacoes || 5), 0) / reviews.length).toFixed(1);
            const avgEl = document.getElementById('home-avg-text');
            if (avgEl) avgEl.textContent = avg;

            const starsEl = document.getElementById('home-avg-stars');
            if (starsEl) starsEl.innerHTML = generateStars(parseFloat(avg));

            // Renderizar itens do carrossel
            track.innerHTML = reviews.map(r => {
                const nota    = parseInt(r.nota_avaliacoes || 5);
                const nome    = r.nome_cliente || 'Cliente';
                const produto = r.nome_produto || '';
                const coment  = r.comentario_avaliacoes || 'Produto incrível! Recomendo muito.';
                const inicial = nome.charAt(0).toUpperCase();
                const stars   = generateStars(nota);

                return `
                <div class="carousel-item-review">
                    <div class="home-review-card h-full">
                        <div class="hrc-stars">${stars}</div>
                        <p class="hrc-comment">&ldquo;${coment}&rdquo;</p>
                        <div class="hrc-footer">
                            <div class="hrc-avatar">${inicial}</div>
                            <div>
                                <div class="hrc-name">${nome}</div>
                                ${produto ? `<div class="hrc-product text-left">${produto}</div>` : ''}
                            </div>
                            <span class="hrc-badge">✓ COMPROU</span>
                        </div>
                    </div>
                </div>`;
            }).join('');

            // Configurar listeners de navegação pelas setas
            const prevBtn = document.getElementById('carousel-prev-btn');
            const nextBtn = document.getElementById('carousel-next-btn');

            if (prevBtn) {
                prevBtn.onclick = () => {
                    navigatePrev();
                    startAutoplay();
                };
            }
            if (nextBtn) {
                nextBtn.onclick = () => {
                    navigateNext();
                    startAutoplay();
                };
            }

            // Configurar redimensionamento responsivo
            window.addEventListener('resize', updateCarousel);

            // Inicializar visualização
            updateCarousel();

            // Configurar Autoplay com Pause on Hover
            const wrapper = document.getElementById('home-reviews-carousel-wrapper');
            if (wrapper) {
                wrapper.addEventListener('mouseenter', stopAutoplay);
                wrapper.addEventListener('mouseleave', startAutoplay);
            }
            startAutoplay();

            // Configurar Gestos de Deslizar (Swipe)
            let isDragging = false;
            let startX = 0;
            const viewport = document.getElementById('home-reviews-viewport');

            if (viewport) {
                // Eventos Touch
                viewport.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    isDragging = true;
                    stopAutoplay();
                }, { passive: true });

                viewport.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;
                    const currentX = e.touches[0].clientX;
                    const diff = currentX - startX;

                    if (diff > 50) {
                        isDragging = false;
                        navigatePrev();
                    } else if (diff < -50) {
                        isDragging = false;
                        navigateNext();
                    }
                }, { passive: true });

                viewport.addEventListener('touchend', () => {
                    isDragging = false;
                    startAutoplay();
                });

                // Eventos Mouse
                viewport.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    startX = e.clientX;
                    stopAutoplay();
                    viewport.style.cursor = 'grabbing';
                });

                viewport.addEventListener('mousemove', (e) => {
                    if (!isDragging) return;
                    const currentX = e.clientX;
                    const diff = currentX - startX;

                    if (diff > 85) {
                        isDragging = false;
                        navigatePrev();
                    } else if (diff < -85) {
                        isDragging = false;
                        navigateNext();
                    }
                });

                const dragEnd = () => {
                    if (isDragging) {
                        isDragging = false;
                        startAutoplay();
                    }
                    viewport.style.cursor = 'grab';
                };

                viewport.addEventListener('mouseup', dragEnd);
                viewport.addEventListener('mouseleave', dragEnd);
            }

            if (cta) cta.style.display = 'block';

        } catch (e) {
            console.warn('Reviews home: erro ao carregar', e);
            if (skeleton) skeleton.style.display = 'none';
        }
    };


    const generateStars = (rating) => {
        let starsHtml = '';
        const r = parseFloat(rating);
        for (let i = 1; i <= 5; i++) {
            if (i <= r) {
                starsHtml += '<i class="bi bi-star-fill"></i>';
            } else if (i - 0.5 <= r) {
                starsHtml += '<i class="bi bi-star-half"></i>';
            } else {
                starsHtml += '<i class="bi bi-star"></i>';
            }
        }
        return starsHtml;
    };

    return {
        initProductReviews,
        initHomeReviews
    };
})();

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    // Product Page
    const productParams = new URLSearchParams(window.location.search);
    const productId = productParams.get('id');
    if (productId && document.getElementById('reviews-list')) {
        ReviewManager.initProductReviews(productId);
    }

    // Homepage — unified handler (replaces both inline script and old initHomeCarousel)
    if (document.getElementById('home-reviews-track')) {
        ReviewManager.initHomeReviews();
    }
});
