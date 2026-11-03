/* ==========================================================================
   World Travel · Dashboard (JavaScript Vanilla)
   ========================================================================== */
(() => {
    'use strict';

    const $ = (selector, scope = document) => scope.querySelector(selector);
    const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));
    const MOBILE_QUERY = window.matchMedia('(max-width: 1023.98px)');

    /* ---------- Imágenes rotas: muestra iniciales o degradado ---------- */
    function initImageFallbacks() {
        $$('img[data-fallback]').forEach((img) => {
            const markBroken = () => img.parentElement?.classList.add('is-broken');
            // La imagen pudo fallar antes de que el script (defer) se ejecutara.
            if (img.complete && img.naturalWidth === 0) {
                markBroken();
            } else {
                img.addEventListener('error', markBroken, { once: true });
            }
        });
    }

    /* ---------- Sidebar como drawer en móvil ---------- */
    function initDrawer() {
        const drawer = $('[data-drawer]');
        const overlay = $('[data-drawer-overlay]');
        const openBtn = $('[data-drawer-open]');
        const closeBtn = $('[data-drawer-close]');
        if (!drawer || !openBtn) return;

        const setOpen = (open) => {
            drawer.classList.toggle('is-open', open);
            overlay.hidden = !open;
            openBtn.setAttribute('aria-expanded', String(open));
            document.body.classList.toggle('is-locked', open);
            if (open) {
                $('a, button', drawer)?.focus();
            } else if (drawer.contains(document.activeElement)) {
                openBtn.focus();
            }
        };

        openBtn.addEventListener('click', () => setOpen(true));
        closeBtn?.addEventListener('click', () => setOpen(false));
        overlay?.addEventListener('click', () => setOpen(false));
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && drawer.classList.contains('is-open')) setOpen(false);
        });
        drawer.addEventListener('click', (event) => {
            if (MOBILE_QUERY.matches && event.target.closest('a[href^="#"]')) setOpen(false);
        });
        MOBILE_QUERY.addEventListener('change', (event) => {
            if (!event.matches) setOpen(false);
        });
    }

    /* ---------- Carrusel de viajes ---------- */
    function initCarousels() {
        $$('[data-carousel]').forEach((carousel) => {
            const track = $('[data-carousel-track]', carousel);
            const prev = $('[data-carousel-prev]', carousel);
            const next = $('[data-carousel-next]', carousel);
            if (!track || !prev || !next) return;

            const step = () => {
                const card = $('.trip-card:not([hidden])', track);
                const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
                return card ? card.getBoundingClientRect().width + gap : track.clientWidth;
            };

            const update = () => {
                const maxScroll = track.scrollWidth - track.clientWidth - 1;
                prev.disabled = track.scrollLeft <= 0;
                next.disabled = track.scrollLeft >= maxScroll;
            };

            prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
            next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));

            track.addEventListener('scroll', () => requestAnimationFrame(update), { passive: true });
            track.addEventListener('carousel:refresh', update);
            window.addEventListener('resize', update);
            update();
        });
    }

    /* ---------- Anillo de progreso del perfil ---------- */
    function initProgressRing() {
        $$('[data-progress]').forEach((ring) => {
            const value = Math.min(100, Math.max(0, Number(ring.dataset.progress) || 0));
            ring.style.setProperty('--progress', String(value));
        });
    }

    /* ---------- Gráfico de barras apiladas (millas) ---------- */
    function initMilesChart() {
        const chart = $('[data-chart]');
        if (!chart) return;

        const columns = $$('.chart__col', chart).map((col) => ({
            col,
            nacional: Number(col.dataset.nacional) || 0,
            internacional: Number(col.dataset.internacional) || 0,
        }));
        const max = Math.max(1, ...columns.map((c) => c.nacional + c.internacional));

        // Se aplica en el siguiente frame para que la transición CSS anime desde 0.
        requestAnimationFrame(() => {
            columns.forEach(({ col, nacional, internacional }) => {
                $('.chart__seg--nac', col).style.height = `${(nacional / max) * 100}%`;
                $('.chart__seg--int', col).style.height = `${(internacional / max) * 100}%`;
            });
        });
    }

    /* ---------- Búsqueda en vivo sobre viajes e historial ---------- */
    function initSearch() {
        const form = $('[data-search]');
        const input = $('[data-search-input]');
        const emptyMessage = $('[data-search-empty]');
        const focusBtn = $('[data-search-focus]');
        if (!form || !input) return;

        const items = $$('[data-searchable]');
        const normalize = (text) =>
            text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
        const index = items.map((el) => ({ el, text: normalize(el.dataset.searchText || el.textContent) }));

        let timer;
        const filter = () => {
            const query = normalize(input.value);
            let visible = 0;
            index.forEach(({ el, text }) => {
                const match = !query || text.includes(query);
                el.hidden = !match;
                if (match) visible += 1;
            });
            if (emptyMessage) emptyMessage.hidden = visible > 0 || !query;
            $$('[data-carousel-track]').forEach((track) => {
                track.scrollLeft = 0;
                track.dispatchEvent(new Event('carousel:refresh'));
            });
        };

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(filter, 150);
        });

        // Enter con resultados locales filtra; sin texto no envía el formulario.
        form.addEventListener('submit', (event) => {
            if (!input.value.trim()) event.preventDefault();
        });

        focusBtn?.addEventListener('click', () => {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            input.focus({ preventScroll: true });
        });
    }

    /* ---------- Toast + botones de acción ---------- */
    function initActions() {
        const toast = $('[data-toast]');
        let hideTimer;

        const showToast = (message) => {
            if (!toast) return;
            clearTimeout(hideTimer);
            toast.textContent = message;
            toast.hidden = false;
            requestAnimationFrame(() => toast.classList.add('is-visible'));
            hideTimer = setTimeout(() => {
                toast.classList.remove('is-visible');
                setTimeout(() => { toast.hidden = true; }, 250);
            }, 2600);
        };

        document.addEventListener('click', (event) => {
            const trigger = event.target.closest('[data-action]');
            if (!trigger) return;

            const { action, label = '' } = trigger.dataset;
            document.dispatchEvent(new CustomEvent('dashboard:action', { detail: { action, label, trigger } }));
            showToast(label || 'Acción realizada');
        });
    }

    function init() {
        initImageFallbacks();
        initDrawer();
        initCarousels();
        initProgressRing();
        initMilesChart();
        initSearch();
        initActions();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
