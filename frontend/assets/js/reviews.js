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
            reviewsContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-warning spinner-border-sm"></div></div>';

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
                            <img src="${review.foto_usuarios ? '/backend/upload/' + review.foto_usuarios : '/assets/img/manutencao.png'}" 
                                 class="rounded-circle" 
                                 style="width: 40px; height: 40px; object-fit: cover; border: 1px solid var(--gold-primary);"
                                 onerror="this.onerror=null; this.src='/assets/img/manutencao.png'">
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

            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>ENVIANDO...';
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
        container.innerHTML = `
            <div id="reviewCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    ${reviews.map((review, index) => `
                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                            <div class="review-card-modern mx-auto">
                                <div class="row g-0 align-items-center">
                                    <div class="col-md-4 d-none d-md-block">
                                        <div class="review-img-wrapper" style="height: 350px; overflow: hidden; border-radius: 20px 0 0 20px;">
                                            <img src="${review.foto_produto ? '/backend/upload/' + review.foto_produto : '/assets/img/manutencao.png'}" 
                                                 class="img-fluid h-100 w-100 object-fit-cover" 
                                                 alt="${review.nome_cliente}"
                                                 onerror="this.onerror=null; this.src='/assets/img/manutencao.png'">
                                        </div>
                                    </div>
                                    <div class="col-md-8 p-4 p-lg-5">
                                        <div class="stars text-warning mb-3">
                                            ${generateStars(review.nota_avaliacoes)}
                                        </div>
                                        <h4 class="review-quote text-white mb-3">"${review.comentario_avaliacoes || 'Produto sensacional, recomendo muito!'}"</h4>
                                        <div class="reviewer-meta d-flex align-items-center mt-4">
                                            <div class="reviewer-avatar-wrapper position-relative me-3">
                                                <img src="${review.foto_usuarios ? '/backend/upload/' + review.foto_usuarios : '/assets/img/manutencao.png'}" 
                                                     class="reviewer-avatar" 
                                                     style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gold-primary);" 
                                                     onerror="this.onerror=null; this.src='/assets/img/manutencao.png'">
                                                <div class="verified-badge position-absolute bottom-0 end-0 bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; border: 2px solid #000;">
                                                    <i class="bi bi-check-lg text-black" style="font-size: 10px;"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="reviewer-name text-white fw-bold">${review.nome_cliente || 'Cliente Koketsu'}</div>
                                                <div class="reviewed-product small text-secondary">Comprou: ${review.nome_produto || 'Produto Exclusivo'}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="carousel-controls mt-4 d-flex justify-content-center gap-3">
                    <button class="control-btn" type="button" data-bs-target="#reviewCarousel" data-bs-slide="prev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="control-btn" type="button" data-bs-target="#reviewCarousel" data-bs-slide="next">
                        <i class="bi bi-chevron-right"></i>
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
