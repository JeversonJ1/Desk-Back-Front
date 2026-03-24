/**
 * Koketsu Grife - Review System
 * Handles product page reviews, submission form, and homepage reviews carousel.
 */

const ReviewManager = (() => {
    const API_BASE = '/api/avaliacoes.php';
    const AUTH_API = '/api/check_auth.php';
    let currentUser = null;

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

            if (result.status === 'success' && result.data.length > 0) {
                renderProductReviews(result.data);
            } else {
                reviewsContainer.innerHTML = '<p class="text-secondary text-center py-4">Este produto ainda não possui avaliações. Seja o primeiro a avaliar!</p>';
            }

            // Check if user can review
            checkUserAbility(productId);
        } catch (error) {
            console.error('Error fetching reviews:', error);
            reviewsContainer.innerHTML = '<p class="text-danger text-center py-4">Erro ao carregar avaliações.</p>';
        }
    };

    /**
     * Fetch and render average rating and count at the top of the product page
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

                // Render stars
                starsContainer.innerHTML = generateStars(media);

                // Update text
                if (total > 0) {
                    countText.innerText = `(${total} ${total === 1 ? 'avaliação' : 'avaliações'})`;
                } else {
                    countText.innerText = '(Este produto ainda não possui avaliações)';
                }
            }
        } catch (error) {
            console.warn('[Review] Error loading rating summary:', error);
        }
    };

    const renderProductReviews = (reviews) => {
        const reviewsContainer = document.getElementById('reviews-list');
        reviewsContainer.innerHTML = reviews.map(review => `
            <div class="review-item-premium fade-in">
                <div class="review-header d-flex justify-content-between align-items-start mb-3">
                    <div class="reviewer-info d-flex align-items-center">
                        <div class="reviewer-avatar-mini me-3">
                            <img src="${review.foto_usuarios ? '/backend/upload/' + review.foto_usuarios : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'}" 
                                 class="rounded-circle" 
                                 style="width: 40px; height: 40px; object-fit: cover; border: 1px solid var(--gold-primary);"
                                 onerror="this.onerror=null; this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'">
                        </div>
                        <div>
                            <span class="reviewer-name fw-bold text-white d-block mb-1">${review.nome_cliente}</span>
                            <div class="stars text-warning small">
                                ${generateStars(review.nota_avaliacoes)}
                            </div>
                        </div>
                    </div>
                    <span class="review-date small text-secondary opacity-50">${new Date(review.data_avaliacao_avaliacoes).toLocaleDateString('pt-BR')}</span>
                </div>
                <p class="review-text text-secondary mb-0 mt-2" style="font-size: 0.95rem; line-height: 1.6;">${review.comentario_avaliacoes || 'Sem comentário.'}</p>
            </div>
        `).join('');
    };

    /**
     * Check if current user is logged in and has bought the product
     */
    const checkUserAbility = async (productId) => {
        const formContainer = document.getElementById('review-form-container');
        if (!formContainer) return;

        try {
            const authRes = await fetch(AUTH_API);
            const authData = await authRes.json();

            if (authData.authenticated && authData.user) {
                currentUser = authData.user;

                // Now check if they bought it via backend (using the POST endpoint logic or similar)
                // For now, we'll try to submit a partial check or rely on the backend validation during POST
                // But to SHOW the form, we can do a quick check if it's possible
                // We'll just show the form if logged in, and handle '403 Forbidden' if they haven't bought it when they submit
                // This is simpler and doesn't require a dedicated "check_buyer" endpoint yet.
                formContainer.classList.remove('d-none');
                setupForm(productId);
            }
        } catch (e) {
            console.warn('[Review] Error checking user ability:', e);
        }
    };

    const setupForm = (productId) => {
        const form = document.getElementById('product-review-form');
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('review-rating-value');

        // Star selection interaction
        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                const val = parseInt(star.dataset.value);
                highlightStars(val);
            });

            star.addEventListener('mouseleave', () => {
                highlightStars(parseInt(ratingInput.value));
            });

            star.addEventListener('click', () => {
                const val = parseInt(star.dataset.value);
                ratingInput.value = val;
                highlightStars(val);
            });
        });

        const highlightStars = (val) => {
            stars.forEach(s => {
                const sVal = parseInt(s.dataset.value);
                if (sVal <= val) {
                    s.classList.replace('bi-star', 'bi-star-fill');
                } else {
                    s.classList.replace('bi-star-fill', 'bi-star');
                }
            });
        };

        // Form submission
        form.onsubmit = async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-current mr-2 align-middle"></span>ENVIANDO...';
            btn.disabled = true;

            const payload = {
                id_produto: productId,
                id_usuarios: currentUser.id,
                nota_avaliacoes: ratingInput.value,
                comentario_avaliacoes: document.getElementById('review-comment').value
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
                    Utils.showNotification('Sua avaliação foi enviada com sucesso!', 'success');
                    const formContainer = document.getElementById('review-form-container');
                    if (formContainer) {
                        formContainer.innerHTML = `
                            <div class="text-center py-5 fade-in">
                                <i class="bi bi-star-fill text-warning fs-1 mb-3 d-block"></i>
                                <h4 class="text-white fw-bold">AVALIAÇÃO RECEBIDA!</h4>
                                <p class="text-secondary">Sua opinião ajuda a manter a elite Koketsu sempre no topo.</p>
                                <div class="gold-divider mx-auto mt-4"></div>
                            </div>
                        `;
                    }
                    setTimeout(() => initProductReviews(productId), 3000);
                } else {
                    Utils.showNotification(result.message || 'Erro ao enviar avaliação.', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (err) {
                console.error('Error submitting review:', err);
                Utils.showNotification(`Erro de conexão: ${err.message || 'Tente novamente.'}`, 'error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        };
    };

    /**
     * Homepage Carousel
     */
    const initHomeCarousel = async () => {
        const carouselContainer = document.getElementById('home-reviews-container');
        if (!carouselContainer) return;

        try {
            const response = await fetch(`${API_BASE}/ultimas`);
            const result = await response.json();

            if (result.status === 'success' && result.data.length > 0) {
                renderHomeCarousel(result.data);
            } else {
                const section = carouselContainer.closest('section');
                if (section) section.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching latest reviews:', error);
        }
    };

    const renderHomeCarousel = (reviews) => {
        const container = document.getElementById('home-reviews-container');
        
        // Tailwind/Pure JS Carousel implementation
        window.nextReviewSlide = () => {
            const inner = document.getElementById('reviewCarouselInner');
            if(!inner) return;
            const items = inner.children.length;
            let currentStr = inner.getAttribute('data-index') || '0';
            let current = parseInt(currentStr);
            current = (current + 1) % items;
            inner.setAttribute('data-index', current);
            inner.style.transform = `translateX(-${current * 100}%)`;
        };

        window.prevReviewSlide = () => {
            const inner = document.getElementById('reviewCarouselInner');
            if(!inner) return;
            const items = inner.children.length;
            let currentStr = inner.getAttribute('data-index') || '0';
            let current = parseInt(currentStr);
            current = (current - 1 + items) % items;
            inner.setAttribute('data-index', current);
            inner.style.transform = `translateX(-${current * 100}%)`;
        };

        // Auto slide every 5s
        if(window.reviewInterval) clearInterval(window.reviewInterval);
        window.reviewInterval = setInterval(window.nextReviewSlide, 5000);

        container.innerHTML = `
            <div id="reviewCarousel" class="relative overflow-hidden w-full group">
                <div id="reviewCarouselInner" class="flex transition-transform duration-700 ease-in-out w-full" data-index="0" style="transform: translateX(0%);">
                    ${reviews.map((review, index) => `
                        <div class="w-full shrink-0 px-2 lg:px-4">
                            <div class="bg-[#0a0a0a] border border-[#F2C84B]/20 rounded-2xl mx-auto shadow-2xl overflow-hidden" style="max-width: 900px;">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="hidden md:block md:w-1/3">
                                        <div class="h-[350px] overflow-hidden">
                                            <img src="${review.foto_produto ? '/backend/upload/' + review.foto_produto : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'}" 
                                                 class="h-full w-full object-cover" 
                                                 alt="${review.nome_cliente}"
                                                 onerror="this.onerror=null; this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'">
                                        </div>
                                    </div>
                                    <div class="w-full md:w-2/3 p-6 lg:p-10">
                                        <div class="flex text-[#F2C84B] mb-4 text-xl">
                                            ${generateStars(review.nota_avaliacoes)}
                                        </div>
                                        <h4 class="text-white text-lg md:text-xl italic mb-6 leading-relaxed">"${review.comentario_avaliacoes || 'Produto sensacional, recomendo muito!'}"</h4>
                                        <div class="flex items-center mt-6">
                                            <div class="relative mr-4">
                                                <img src="${review.foto_usuarios ? '/backend/upload/' + review.foto_usuarios : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'}" 
                                                     class="w-16 h-16 rounded-full object-cover border-2 border-[#F2C84B]" 
                                                     onerror="this.onerror=null; this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'">
                                                <div class="absolute bottom-0 right-0 bg-[#F2C84B] rounded-full w-5 h-5 flex items-center justify-center border-2 border-black">
                                                    <i class="bi bi-check-lg text-black text-[10px]"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-white font-bold text-sm tracking-widest uppercase">${review.nome_cliente || 'Cliente Koketsu'}</div>
                                                <div class="text-gray-400 text-xs mt-1">Comprou: <span class="text-[#F2C84B]">${review.nome_produto || 'Produto Exclusivo'}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="flex justify-center gap-4 mt-8">
                    <button class="w-12 h-12 rounded-full border border-white/20 text-white flex items-center justify-center hover:bg-[#F2C84B] hover:text-black hover:border-transparent transition-all" type="button" onclick="window.prevReviewSlide()">
                        <i class="bi bi-chevron-left text-lg"></i>
                    </button>
                    <button class="w-12 h-12 rounded-full border border-white/20 text-white flex items-center justify-center hover:bg-[#F2C84B] hover:text-black hover:border-transparent transition-all" type="button" onclick="window.nextReviewSlide()">
                        <i class="bi bi-chevron-right text-lg"></i>
                    </button>
                </div>
            </div>
        `;
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
        initHomeCarousel
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

    // Homepage
    if (document.getElementById('home-reviews-container')) {
        ReviewManager.initHomeCarousel();
    }
});
