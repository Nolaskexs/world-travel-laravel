// Selección de asientos: estado en memoria + sincronización con el formulario de Laravel
document.addEventListener('DOMContentLoaded', () => {
  const root = document.querySelector('[data-seats]');
  if (!root) return;

  const $ = (s, r = root) => r.querySelector(s);
  const $$ = (s, r = root) => [...r.querySelectorAll(s)];
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const money = (n) => `${root.dataset.currency} ${Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
  const baseTotal = parseFloat(root.dataset.baseTotal) || 0;
  const TYPE_LABEL = { std: '', xl: ' · XL', biz: ' · Premium Business' };

  const form = $('#seatForm');
  const map = $('.map-scroll');
  const seats = new Map($$('.seat[data-seat]').map((b) => [b.dataset.seat, b]));
  const pax = $$('[data-pax]').map((el) => {
    const defaultSeat = el.dataset.initialSeat || null;
    return { el, name: el.dataset.name, code: el.dataset.code, seat: defaultSeat };
  });
  let active = 0;

  const owner = (code) => pax.findIndex((p) => p.seat === code);
  const say = (msg) => { const el = $('#seatMsg'); if (el) el.textContent = msg; };

  // Pasa el foco al siguiente pasajero sin asiento
  const nextFree = () => {
    for (let k = 1; k < pax.length; k++) {
      const i = (active + k) % pax.length;
      if (!pax[i].seat) return i;
    }
    return -1;
  };

  function render() {
    // Mapa: marca seleccionados y muestra la clave del pasajero (ej. A1) dentro del asiento
    seats.forEach((btn, code) => {
      const o = owner(code);
      btn.classList.toggle('is-selected', o > -1);
      btn.setAttribute('aria-pressed', o > -1);
      const lbl = $('.seat__lbl', btn);
      if (lbl) lbl.textContent = o > -1 ? pax[o].code : '';
    });

    // Lista de pasajeros + cálculo de recargos en vivo
    const extras = { xl: { n: 0, sum: 0 }, biz: { n: 0, sum: 0 } };
    pax.forEach((p, i) => {
      p.el.classList.toggle('is-active', i === active);
      const btn = $('.pax__btn', p.el);
      if (btn) btn.setAttribute('aria-pressed', i === active);
      const seatSpan = $('[data-pax-seat]', p.el);
      if (seatSpan) seatSpan.textContent = p.seat ?? '--';

      const subSpan = $('[data-pax-sub]', p.el);
      if (!p.seat) {
        if (subSpan) subSpan.textContent = 'Sin selección';
        return;
      }
      const b = seats.get(p.seat);
      const type = b ? b.dataset.type : 'std';
      const price = b ? (parseFloat(b.dataset.price) || 0) : 0;
      if (subSpan) subSpan.textContent = `Asiento ${p.seat}${TYPE_LABEL[type] || ''}${price ? ` (+${money(price)})` : ''}`;
      if (extras[type]) { extras[type].n++; extras[type].sum += price; }
    });

    const baseEl = $('[data-base]');
    if (baseEl) baseEl.textContent = money(baseTotal);

    ['xl', 'biz'].forEach((t) => {
      const line = $(`[data-line=${t}]`);
      if (line) {
        line.hidden = extras[t].n === 0;
        const countEl = $('[data-count]', line);
        if (countEl) countEl.textContent = extras[t].n;
        const amountEl = $('[data-amount]', line);
        if (amountEl) amountEl.textContent = `+${money(extras[t].sum)}`;
      }
    });

    const totalEl = $('[data-total]');
    if (totalEl) totalEl.textContent = money(baseTotal + extras.xl.sum + extras.biz.sum);

    // Inputs ocultos: asientos[0], asientos[1], ... y resumen en asientos_texto
    const box = $('#seatInputs');
    if (box) {
      box.replaceChildren(...pax.flatMap((p, i) => {
        if (!p.seat) return [];
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `asientos[${i}]`;
        input.value = p.seat;
        return [input];
      }));

      // Input combinado para facilitar lectura en query params o backend
      const combined = document.createElement('input');
      combined.type = 'hidden';
      combined.name = 'asientos_seleccionados';
      combined.value = pax.map(p => p.seat).filter(Boolean).join(',');
      box.appendChild(combined);
    }

    const missing = pax.filter((p) => !p.seat).length;
    const contBtn = $('[data-continue]');
    if (contBtn) contBtn.disabled = missing > 0;
    const missEl = $('[data-missing]');
    if (missEl) {
      missEl.textContent = missing
        ? `Falta${missing > 1 ? 'n' : ''} ${missing} asiento${missing > 1 ? 's' : ''} por elegir.`
        : 'Todos los asientos están elegidos.';
    }
  }

  function pick(code) {
    const o = owner(code);
    if (o === active) {
      pax[active].seat = null;
      say(`Asiento ${code} liberado.`);
    } else if (o > -1) {
      say(`El asiento ${code} ya está asignado a ${pax[o].name}.`);
      return;
    } else {
      pax[active].seat = code;
      say(`${pax[active].name}: asiento ${code}.`);
      const n = nextFree();
      if (n > -1) active = n;
    }
    render();
  }

  if (map) {
    map.addEventListener('click', (e) => {
      const btn = e.target.closest('.seat[data-seat]');
      if (btn && !btn.disabled) pick(btn.dataset.seat);
    });
  }

  pax.forEach((p, i) => {
    const btn = $('.pax__btn', p.el);
    if (!btn) return;
    btn.addEventListener('click', () => {
      active = i;
      render();
      if (p.seat && map) {
        const b = seats.get(p.seat);
        if (b) {
          const bRect = b.getBoundingClientRect(), mRect = map.getBoundingClientRect();
          map.scrollBy({ top: bRect.top - mRect.top - mRect.height / 2, behavior: reduce ? 'auto' : 'smooth' });
        }
      }
    });
  });

  // Acordeón Premium Business, desglose de precio y aviso descartable
  const toggle = (btn, target, cls) => {
    if (!btn || !target) return;
    btn.addEventListener('click', () => {
      const open = btn.getAttribute('aria-expanded') !== 'true';
      btn.setAttribute('aria-expanded', open);
      cls ? target.classList.toggle('is-open', open) : (target.hidden = !open);
    });
  };

  toggle($('.biz__head'), $('#biz-list'), true);
  toggle($('.summary__toggle'), $('#breakdown'), false);
  const closeNotice = $('[data-notice-close]');
  if (closeNotice) {
    closeNotice.addEventListener('click', () => {
      const notice = $('[data-notice]');
      if (notice) notice.hidden = true;
    });
  }

  if (form) {
    form.addEventListener('submit', (e) => {
      if (e.submitter?.name === 'omitir') {
        const box = $('#seatInputs');
        if (box) box.replaceChildren();
        return;
      }
      if (pax.some((p) => !p.seat)) {
        e.preventDefault();
        say('Elige un asiento para cada pasajero o usa "Quiero elegir asientos después".');
      }
    });
  }

  render();
});
