const apiBase = '/api/index.php?route=';

async function fetchJson(route, options = {}) {
    const params = new URLSearchParams(options.params || {});
    const url = new URL(`${apiBase}${route}`, window.location.origin);
    params.forEach((value, key) => url.searchParams.set(key, value));

    const response = await fetch(url.toString(), {
        headers: {
            'Content-Type': 'application/json',
            ...(options.headers || {})
        },
        ...options,
    });

    let payload = null;
    try {
        payload = await response.json();
    } catch (error) {
        payload = { success: false, message: 'Resposta inválida da API.' };
    }

    if (!response.ok && payload?.message) {
        throw new Error(payload.message);
    }

    return payload;
}

function renderGameCard(game) {
    const price = Number(game.price || 0);
    const link = `/game.php?id=${game.id}`;
    return `
        <article class="game-card">
            <a href="${link}">
                <div class="cover">${(game.title || 'PIXEL').slice(0, 5).toUpperCase()}</div>
            </a>
            <div class="content">
                <div class="meta">${game.genre || 'Indie'} • ${game.developer || 'PixelSwap'}</div>
                <h3><a href="${link}">${game.title || 'Game'}</a></h3>
                <div class="price-row">
                    <span class="price-tag">R$ ${price.toFixed(2).replace('.', ',')}</span>
                    <span class="status-pill">Disponível</span>
                </div>
            </div>
        </article>
    `;
}

async function purchaseListing(listingId) {
    if (!listingId) return;

    try {
        const result = await fetchJson('orders', {
            method: 'POST',
            body: JSON.stringify({ listing_id: Number(listingId) }),
        });
        alert(result.message || 'Compra concluída com sucesso.');
        window.location.reload();
    } catch (error) {
        alert(error.message);
    }
}

async function decideTrade(tradeId, action) {
    if (!tradeId) return;

    try {
        const result = await fetchJson(`trades/${tradeId}/${action}`, {
            method: 'PATCH',
            body: JSON.stringify({}),
        });
        alert(result.message || 'Operação concluída.');
        loadTradeDashboard();
    } catch (error) {
        alert(error.message);
    }
}

function renderHomeSections() {
    const featuredContainer = document.querySelector('#featured-games');
    const recentContainer = document.querySelector('#recent-offers');
    const popularContainer = document.querySelector('#popular-games');
    const tradeContainer = document.querySelector('#trade-listings');

    if (!featuredContainer || !recentContainer || !popularContainer || !tradeContainer) return;

    Promise.all([
        fetchJson('games'),
        fetchJson('listings')
    ])
        .then(([gamesResult, listingsResult]) => {
            const games = gamesResult.success ? (gamesResult.data || []) : [];
            const listings = listingsResult.success ? (listingsResult.data || []) : [];

            featuredContainer.innerHTML = games.slice(0, 4).map(renderGameCard).join('');
            recentContainer.innerHTML = games.slice(1, 4).map(renderGameCard).join('');
            popularContainer.innerHTML = games.slice(0, 4).map(renderGameCard).join('');
            tradeContainer.innerHTML = listings.filter(item => item.listing_type === 'trade').slice(0, 3).map((item) => `
                <article class="game-card">
                    <div class="cover">${(item.title || 'TRADE').slice(0, 5).toUpperCase()}</div>
                    <div class="content">
                        <div class="meta">Troca • ${item.genre || 'Indie'}</div>
                        <h3>${item.title || 'Título'}</h3>
                        <div class="price-row">
                            <span class="price-tag">Troca</span>
                            <span class="status-pill">Aceita troca</span>
                        </div>
                    </div>
                </article>
            `).join('');
        })
        .catch((error) => {
            console.error(error);
            featuredContainer.innerHTML = '<p>Não foi possível carregar os destaques.</p>';
        });
}

function bindSearch() {
    const searchInput = document.getElementById('global-search');
    const searchButton = document.getElementById('search-button');
    if (!searchInput || !searchButton) return;

    searchButton.addEventListener('click', () => {
        const term = encodeURIComponent(searchInput.value.trim());
        if (!term) {
            window.location.href = '/marketplace.php';
            return;
        }
        window.location.href = `/marketplace.php?search=${term}`;
    });
}

function setupMarketplacePage() {
    const container = document.getElementById('marketplace-grid');
    const searchInput = document.getElementById('marketplace-search');
    const genreSelect = document.getElementById('genre-filter');
    const sortSelect = document.getElementById('sort-filter');
    if (!container) return;

    const params = new URLSearchParams(window.location.search);
    const searchValue = params.get('search') || '';
    if (searchInput && searchValue) searchInput.value = decodeURIComponent(searchValue);

    async function loadGames() {
        const term = searchInput ? searchInput.value.trim() : '';
        const genre = genreSelect ? genreSelect.value : '';
        const sort = sortSelect ? sortSelect.value : '';

        try {
            const result = await fetchJson('games', {
                params: {
                    search: term,
                    genre,
                    sort,
                }
            });
            const games = result.success ? (result.data || []) : [];
            container.innerHTML = games.length ? games.map(renderGameCard).join('') : '<p>Nenhum jogo encontrado.</p>';
        } catch (error) {
            container.innerHTML = `<p>${error.message}</p>`;
        }
    }

    if (searchInput) searchInput.addEventListener('input', loadGames);
    if (genreSelect) genreSelect.addEventListener('change', loadGames);
    if (sortSelect) sortSelect.addEventListener('change', loadGames);
    loadGames();
}

function setupGamePage() {
    const id = new URLSearchParams(window.location.search).get('id');
    const container = document.getElementById('game-details');
    if (!id || !container) return;

    Promise.all([
        fetchJson(`games/${id}`),
        fetchJson('listings')
    ])
        .then(([gameResult, listingsResult]) => {
            const game = gameResult.success ? gameResult.data : null;
            const listings = listingsResult.success ? (listingsResult.data || []) : [];
            const offers = listings.filter((listing) => Number(listing.game_id) === Number(id));

            if (!game) {
                container.innerHTML = '<p>Jogo não encontrado.</p>';
                return;
            }

            const offersMarkup = offers.length
                ? offers.map((listing) => `
                    <article class="list-item">
                        <div class="list-item-head">
                            <strong>${listing.listing_type === 'trade' ? 'Troca' : 'Venda'}</strong>
                            <span class="status-pill">${listing.status || 'ativo'}</span>
                        </div>
                        <div class="list-item-meta">Vendedor: ${listing.seller_name || 'PixelSwap'} • ${listing.listing_type === 'trade' ? 'Aceita trocas' : 'Disponível para compra'}</div>
                        <div class="price-row" style="margin-top:0.8rem;">
                            <span class="price-tag">R$ ${Number(listing.price || 0).toFixed(2).replace('.', ',')}</span>
                            <button class="btn btn-primary" type="button" onclick="purchaseListing(${listing.id})">Comprar</button>
                        </div>
                    </article>
                `).join('')
                : '<p>Nenhuma oferta ativa para este jogo no momento.</p>';

            container.innerHTML = `
                <div class="game-header">
                    <div class="cover large">${(game.title || 'GAME').slice(0, 6).toUpperCase()}</div>
                    <div>
                        <span class="eyebrow">${game.genre || 'Indie'}</span>
                        <h1>${game.title}</h1>
                        <p>${game.description || 'Descrição não disponível.'}</p>
                        <div class="game-meta-row">
                            <span>Desenvolvedor: ${game.developer || 'Indie Studio'}</span>
                            <span>Lançamento: ${game.release_date || 'N/D'}</span>
                        </div>
                        <div class="hero-actions">
                            <button class="btn btn-primary" type="button" onclick="purchaseListing(${offers[0]?.id || 0})">Comprar por R$ ${Number(game.price || 0).toFixed(2).replace('.', ',')}</button>
                            <button class="btn btn-secondary" type="button">Adicionar à lista de desejos</button>
                        </div>
                    </div>
                </div>
                <div style="margin-top:2rem;">
                    <h2>Ofertas disponíveis</h2>
                    ${offersMarkup}
                </div>
            `;
        })
        .catch((error) => {
            container.innerHTML = `<p>${error.message}</p>`;
        });
}

function logoutUser() {
    fetchJson('auth/logout')
        .then(() => {
            window.location.href = '/index.php';
        })
        .catch(() => {
            window.location.href = '/index.php';
        });
}

async function loadCurrentUserProfile() {
    try {
        const result = await fetchJson('auth/me');
        const user = result?.data?.user || null;
        if (!user) return null;

        const nameEl = document.getElementById('profile-name');
        const emailEl = document.getElementById('profile-email');
        const avatarEl = document.getElementById('profile-avatar');
        if (nameEl) nameEl.textContent = user.name || 'Usuário';
        if (emailEl) emailEl.textContent = user.email || 'email@pixelswap.dev';
        if (avatarEl) avatarEl.textContent = (user.name || 'U').charAt(0).toUpperCase();
        return user;
    } catch (error) {
        console.error(error);
        return null;
    }
}

async function loadProfileDashboard() {
    const libraryGrid = document.getElementById('library-grid');
    const wishlistGrid = document.getElementById('wishlist-grid');
    const tradesGrid = document.getElementById('trades-grid');
    const ordersCounter = document.getElementById('stats-orders');
    const tradesCounter = document.getElementById('stats-trades');
    const wishlistCounter = document.getElementById('stats-wishlist');

    if (!libraryGrid && !wishlistGrid && !tradesGrid) return;

    try {
        const [meResult, ordersResult, wishlistResult, tradesResult] = await Promise.all([
            fetchJson('auth/me'),
            fetchJson('orders'),
            fetchJson('wishlist'),
            fetchJson('trades')
        ]);

        const user = meResult?.data?.user || null;
        if (user && document.getElementById('profile-name')) {
            document.getElementById('profile-name').textContent = user.name;
            document.getElementById('profile-email').textContent = user.email;
            const avatar = document.getElementById('profile-avatar');
            if (avatar) avatar.textContent = user.name.charAt(0).toUpperCase();
        }

        const orders = ordersResult.success ? (ordersResult.data || []) : [];
        const wishlist = wishlistResult.success ? (wishlistResult.data || []) : [];
        const trades = tradesResult.success ? (tradesResult.data || []) : [];

        if (ordersCounter) ordersCounter.textContent = String(orders.length);
        if (wishlistCounter) wishlistCounter.textContent = String(wishlist.length);
        if (tradesCounter) tradesCounter.textContent = String(trades.filter((trade) => trade.status === 'pending').length);

        libraryGrid.innerHTML = orders.length
            ? orders.map((order) => `
                <article class="game-card">
                    <div class="cover">${(order.title || 'GAME').slice(0, 5).toUpperCase()}</div>
                    <div class="content">
                        <div class="meta">Pedido concluído</div>
                        <h3>${order.title || 'Jogo'}</h3>
                        <div class="price-row">
                            <span class="price-tag">R$ ${Number(order.total_price || 0).toFixed(2).replace('.', ',')}</span>
                            <span class="status-pill">Ativo</span>
                        </div>
                    </div>
                </article>
            `).join('')
            : '<p>Nenhuma compra registrada ainda.</p>';

        wishlistGrid.innerHTML = wishlist.length
            ? wishlist.map((item) => `
                <article class="game-card">
                    <div class="cover">${(item.title || 'WISH').slice(0, 5).toUpperCase()}</div>
                    <div class="content">
                        <div class="meta">${item.genre || 'Indie'}</div>
                        <h3>${item.title || 'Título'}</h3>
                        <div class="price-row">
                            <span class="price-tag">R$ ${Number(item.price || 0).toFixed(2).replace('.', ',')}</span>
                            <span class="status-pill">Favorito</span>
                        </div>
                    </div>
                </article>
            `).join('')
            : '<p>Sua lista de desejos está vazia.</p>';

        tradesGrid.innerHTML = trades.length
            ? trades.map((trade) => `
                <article class="list-item">
                    <div class="list-item-head">
                        <strong>${trade.requested_title || 'Troca'}</strong>
                        <span class="status-pill">${trade.status || 'pending'}</span>
                    </div>
                    <div class="list-item-meta">${trade.sender_name || 'Usuário'} → ${trade.receiver_name || 'Você'}</div>
                    <div class="form-actions" style="margin-top:0.8rem;">
                        ${trade.status === 'pending' ? `
                            <button class="btn btn-primary" type="button" onclick="decideTrade(${trade.id}, 'accept')">Aceitar</button>
                            <button class="btn btn-secondary" type="button" onclick="decideTrade(${trade.id}, 'reject')">Recusar</button>
                        ` : ''}
                    </div>
                </article>
            `).join('')
            : '<p>Você ainda não possui propostas de troca.</p>';
    } catch (error) {
        if (libraryGrid) libraryGrid.innerHTML = `<p>${error.message}</p>`;
        if (wishlistGrid) wishlistGrid.innerHTML = '<p>Não foi possível carregar sua lista.</p>';
        if (tradesGrid) tradesGrid.innerHTML = '<p>Não foi possível carregar suas trocas.</p>';
    }
}

function loadTradeDashboard() {
    loadProfileDashboard();
}

async function loadSellerListings() {
    const container = document.getElementById('seller-listings');
    if (!container) return;

    try {
        const result = await fetchJson('listings', { params: { mine: 1 } });
        const listings = result.success ? (result.data || []) : [];

        container.innerHTML = listings.length
            ? listings.map((listing) => `
                <article class="list-item">
                    <div class="list-item-head">
                        <strong>${listing.title || 'Título'}</strong>
                        <span class="status-pill">${listing.listing_type === 'trade' ? 'Troca' : 'Venda'}</span>
                    </div>
                    <div class="list-item-meta">${listing.genre || 'Indie'} • R$ ${Number(listing.price || 0).toFixed(2).replace('.', ',')}</div>
                    <div class="form-actions" style="margin-top:1rem;">
                        <button class="btn btn-secondary" type="button" onclick="deleteListing(${listing.id})">Remover</button>
                    </div>
                </article>
            `).join('')
            : '<p>Você ainda não publicou anúncios.</p>';
    } catch (error) {
        container.innerHTML = `<p>${error.message}</p>`;
    }
}

async function deleteListing(id) {
    if (!id) return;
    try {
        const result = await fetchJson(`listings/${id}`, {
            method: 'DELETE',
        });
        if (result.success) {
            loadSellerListings();
        }
    } catch (error) {
        console.error(error);
    }
}

function setupSellerPage() {
    const form = document.getElementById('listing-form');
    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const payload = {
            game_id: Number(formData.get('game_id')),
            price: Number(formData.get('price')),
            listing_type: formData.get('listing_type') || 'sale',
            accepts_trade: formData.get('accepts_trade') ? 1 : 0,
        };

        const messageBox = document.getElementById('listing-message');
        try {
            const result = await fetchJson('listings', {
                method: 'POST',
                body: JSON.stringify(payload),
            });
            messageBox.className = 'message success';
            messageBox.textContent = result.message || 'Anúncio publicado com sucesso!';
            form.reset();
            loadSellerListings();
        } catch (error) {
            messageBox.className = 'message error';
            messageBox.textContent = error.message;
        }
    });

    loadSellerListings();
}

function setupAuthForms() {
    const registerForm = document.getElementById('register-form');
    const loginForm = document.getElementById('login-form');

    if (registerForm) {
        registerForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(registerForm);
            const payload = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: formData.get('password'),
            };

            const messageBox = document.getElementById('register-message');
            try {
                const result = await fetchJson('auth/register', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });
                messageBox.className = 'message success';
                messageBox.textContent = result.message || 'Cadastro realizado com sucesso!';
                registerForm.reset();
            } catch (error) {
                messageBox.className = 'message error';
                messageBox.textContent = error.message;
            }
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(loginForm);
            const payload = {
                email: formData.get('email'),
                password: formData.get('password'),
            };

            const messageBox = document.getElementById('login-message');
            try {
                const result = await fetchJson('auth/login', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });
                messageBox.className = 'message success';
                messageBox.textContent = result.message || 'Login realizado com sucesso!';
                setTimeout(() => {
                    window.location.href = '/profile.php';
                }, 600);
            } catch (error) {
                messageBox.className = 'message error';
                messageBox.textContent = error.message;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    setupMarketplacePage();
    setupGamePage();
    setupAuthForms();
    setupSellerPage();
    loadProfileDashboard();
    loadTradeDashboard();
});
