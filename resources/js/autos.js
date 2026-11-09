'use strict';
/* =========================================================
   AUTOS — resources/js/autos.js
   Es independiente de main.js (main.js solo se carga en Inicio y falla si no
   existen los campos del buscador de vuelos). Todos los ids y atributos de
   esta sección llevan el prefijo "au" / "data-au-" para evitar choques.
   ========================================================= */
  
(() => {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const ICON = (id) => `<svg class="i" aria-hidden="true"><use href="#i-${id}"/></svg>`;
  const norm = (s) => s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
  const MONTHS = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
  const money = (n) => `S/ ${n}`;

  const form = $('#auSearchForm');
  if (!form) return; // la página no es Autos

  /* ---------- Datos de EJEMPLO (reemplazar por datos del backend / @foreach) ---------- */
  // [ciudad, código, país] — mismas ciudades del buscador de vuelos (main.js)
  const PLACES = [['Lima', 'LIM', 'Perú'], ['Cusco', 'CUZ', 'Perú'], ['Arequipa', 'AQP', 'Perú'], ['Roma', 'FCO', 'Italia'], ['Tokio', 'HND', 'Japón'], ['Nagoya', 'NGO', 'Japón'], ['Osaka', 'KIX', 'Japón'], ['Fukuoka', 'FUK', 'Japón'], ['Hiroshima', 'HIJ', 'Japón'], ['Nueva Delhi', 'DEL', 'India'], ['Madrid', 'MAD', 'España'], ['París', 'CDG', 'Francia'], ['Nueva York', 'JFK', 'Estados Unidos'], ['Cancún', 'CUN', 'México'], ['Buenos Aires', 'EZE', 'Argentina'], ['Bogotá', 'BOG', 'Colombia']];

  // Fotos en public/icons/autos/. Si falta alguna se muestra un marcador con el icono de auto.
  const CARS = [
    { cat: 'Autos Medianos', city: 'Lima', country: 'Perú', place: 'Aeropuerto Internacional Jorge Chávez', company: 'AutoAndes', price: 138, old: null, img: 'mediano-plata.jpg', seats: 5, bags: 2, gear: 'Automático' },
    { cat: 'Autos Medianos', city: 'Cusco', country: 'Perú', place: 'Aeropuerto Internacional Alejandro Velasco Astete', company: 'Ruta Sur', price: 198, old: null, img: 'mediano-blanco.jpg', seats: 5, bags: 2, gear: 'Manual' },
    { cat: 'Autos Chicos', city: 'Arequipa', country: 'Perú', place: 'Aeropuerto Internacional Rodríguez Ballón', company: 'Costa Rent', price: 158, old: 228, img: 'chico-negro.jpg', seats: 4, bags: 1, gear: 'Manual' },
    { cat: 'Autos Medianos', city: 'Cancún', country: 'México', place: 'Aeropuerto Internacional de Cancún', company: 'Vía Móvil', price: 184, old: null, img: 'mediano-plata.jpg', seats: 5, bags: 2, gear: 'Automático' },
    { cat: 'Autos Chicos', city: 'Bogotá', country: 'Colombia', place: 'Aeropuerto Internacional El Dorado', company: 'Pacífico Cars', price: 161, old: null, img: 'chico-negro.jpg', seats: 4, bags: 1, gear: 'Manual' },
    { cat: 'Autos Medianos', city: 'Buenos Aires', country: 'Argentina', place: 'Aeropuerto Internacional Ministro Pistarini', company: 'Andina Rent', price: 176, old: 205, img: 'mediano-blanco.jpg', seats: 5, bags: 2, gear: 'Automático' },
    { cat: 'Autos Chicos', city: 'Madrid', country: 'España', place: 'Aeropuerto Adolfo Suárez Madrid-Barajas', company: 'Costa Rent', price: 149, old: null, img: 'chico-negro.jpg', seats: 4, bags: 1, gear: 'Manual' },
    { cat: 'Autos Medianos', city: 'Nueva York', country: 'Estados Unidos', place: 'Aeropuerto Internacional John F. Kennedy', company: 'AutoAndes', price: 215, old: null, img: 'mediano-plata.jpg', seats: 5, bags: 2, gear: 'Automático' },
  ];
  const IMG_BASE = (($('#auOffers') && $('#auOffers').dataset.imgBase) || 'icons/autos').replace(/\/$/, '');

  // Estado compartido de la sección
  const state = { place: '', car: null, start: null, end: null };

  /* ---------- Paneles desplegables: uno abierto a la vez (id del panel: "auDd-<nombre>") ---------- */
  const setDD = (name, open) => {
    const p = $('#auDd-' + name);
    p.hidden = !open;
    $$(`[data-au-dd="${name}"]`).forEach((t) => t.setAttribute('aria-expanded', open));
    if (open) p.dispatchEvent(new Event('open'));
  };
  const openDD = (name) => {
    $$('.dd[id^="auDd-"]').forEach((p) => { if (p.id !== 'auDd-' + name && !p.hidden) setDD(p.id.slice(5), false); });
    if ($('#auDd-' + name).hidden) setDD(name, true);
  };
  const closeDD = (name) => {
    if (name) setDD(name, false);
    else $$('.dd[id^="auDd-"]').forEach((p) => { if (!p.hidden) setDD(p.id.slice(5), false); });
  };

  /* ---------- Mensajes y marcas de error del formulario ---------- */
  const msg = $('#auMsg');
  const showMsg = (text) => { msg.innerHTML = text ? `${ICON('info')}<span>${text}</span>` : ''; };
  const clearErrors = () => {
    showMsg('');
    $$('.is-invalid', form).forEach((el) => el.classList.remove('is-invalid'));
  };
  const fail = (text, mark, focus) => {
    showMsg(text);
    mark.classList.add('is-invalid');
    if (focus) focus.focus();
  };

  /* ---------- Lugar de retiro ---------- */
  function initPlace() {
    const input = $('#auPlace'), panel = $('#auDd-lugar');
    const label = ([c, , co]) => `${c}, ${co}`;
    const initial = PLACES.find((p) => label(p) === input.value);
    state.place = initial ? label(initial) : '';

    const render = (q = '') => {
      const n = norm(q.trim());
      const items = PLACES.filter((p) => !n || norm(p.join(' ')).includes(n));
      panel.innerHTML = items.length
        ? items.map((p) => `<button type="button" class="opt" data-place="${label(p)}">${ICON('pin')}<span>${p[0]}<small>${p[1]} - ${p[2]}</small></span></button>`).join('')
        : '<p class="empty">Sin resultados</p>';
    };
    const choose = (value) => {
      input.value = value;
      state.place = value;
      closeDD('lugar');
      clearErrors();
    };
    render();
    panel.addEventListener('open', () => render());

    input.addEventListener('input', () => {
      state.place = ''; // hasta elegir una opción de la lista, el lugar no es válido
      state.car = null;
      $('#auVehiculo').value = '';
      openDD('lugar');
      render(input.value);
    });
    input.addEventListener('focus', () => { input.select(); openDD('lugar'); });
    input.addEventListener('click', () => openDD('lugar'));
    input.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter') return;
      e.preventDefault(); // Enter elige la primera sugerencia en vez de enviar el formulario
      const first = $('.opt', panel);
      if (first) choose(first.dataset.place);
    });
    panel.addEventListener('click', (e) => {
      const b = e.target.closest('.opt');
      if (b) { choose(b.dataset.place); $('#auDd-cal').dataset.field = 'start'; openDD('cal'); }
    });
  }

  /* ---------- Calendario de retiro / devolución ---------- */
  let openCalendar = () => {};
  function initCalendar() {
    const panel = $('#auDd-cal');
    const today = new Date(); today.setHours(0, 0, 0, 0);
    const firstOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    let view = firstOfMonth;
    const iso = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
    const fmt = (d) => `${d.getDate()} ${MONTHS[d.getMonth()].slice(0, 3).toLowerCase()}`;

    const month = (first) => {
      const y = first.getFullYear(), m = first.getMonth();
      const lead = (first.getDay() + 6) % 7; // la semana empieza en lunes
      let h = `<h4>${MONTHS[m]} ${y}</h4><div class="cal__g">` + [...'LMMJVSD'].map((d) => `<span class="cal__wd">${d}</span>`).join('') + '<span></span>'.repeat(lead);
      for (let d = 1, n = new Date(y, m + 1, 0).getDate(); d <= n; d++) {
        const dt = new Date(y, m, d), t = +dt;
        const sel = t === +state.start || t === +state.end;
        const inr = state.start && state.end && t > +state.start && t < +state.end;
        h += `<button type="button" class="day${sel ? ' sel' : ''}${inr ? ' in' : ''}" data-t="${t}"${dt < today ? ' disabled' : ''}>${d}</button>`;
      }
      return h + '</div>';
    };
    const render = () => {
      $$('.cal__m', panel).forEach((el, i) => { el.innerHTML = month(new Date(view.getFullYear(), view.getMonth() + i, 1)); });
      $('.cal__prev', panel).classList.toggle('is-hidden', view <= firstOfMonth);
      $('#auPickupLabel').textContent = state.start ? fmt(state.start) : 'Retiro';
      $('#auReturnLabel').textContent = state.end ? fmt(state.end) : 'Devolución';
      $('#auPickupDate').value = state.start ? iso(state.start) : '';
      $('#auReturnDate').value = state.end ? iso(state.end) : '';
    };
    const shift = (n) => { view = new Date(view.getFullYear(), view.getMonth() + n, 1); render(); };
    $('.cal__prev', panel).addEventListener('click', () => shift(-1));
    $('.cal__next', panel).addEventListener('click', () => shift(1));

    // Botones "Retiro" y "Devolución": abren el mismo calendario en el campo correspondiente
    $$('[data-au-dd="cal"]').forEach((t) => t.addEventListener('click', () => {
      const switching = panel.dataset.field !== t.dataset.field;
      panel.dataset.field = t.dataset.field;
      (panel.hidden || switching) ? openDD('cal') : closeDD('cal');
    }));

    panel.addEventListener('click', (e) => {
      const b = e.target.closest('.day');
      if (!b) return;
      e.stopPropagation(); // render() reemplaza el botón; sin esto el clic "fuera" cerraría el panel
      const d = new Date(+b.dataset.t);
      let done = false;
      if (panel.dataset.field === 'end' && state.start && d >= state.start) { state.end = d; done = true; } // devolución el mismo día o después
      else { state.start = d; state.end = null; panel.dataset.field = 'end'; } // primer clic: queda esperando la devolución
      clearErrors();
      render();
      if (done) closeDD('cal');
    });

    openCalendar = (field) => { panel.dataset.field = field; openDD('cal'); };
    render();
  }

  /* ---------- Envío del formulario (validación en el navegador) ---------- */
  function initForm() {
    const summary = $('#auSummary'), sumText = $('#auSummaryText');
    const when = (date, time) => { const d = new Date(date); const [h, m] = time.split(':'); d.setHours(+h, +m, 0, 0); return d; };
    const fmt = (d) => `${d.getDate()} ${MONTHS[d.getMonth()].slice(0, 3).toLowerCase()}`;

    form.addEventListener('change', clearErrors);
    form.addEventListener('submit', (e) => {
      // TODO backend: cuando exista el controlador de Autos, quitar este preventDefault
      // para que el formulario envíe el GET (lugar_retiro, fecha_retiro, hora_retiro, fecha_devolucion, hora_devolucion, vehiculo).
      e.preventDefault();
      clearErrors();

      if (!state.place) return fail('Elige un lugar de retiro de la lista.', $('#auPlaceField'), $('#auPlace'));
      if (!state.start) { fail('Selecciona la fecha de retiro.', $('#auPickupBtn')); return openCalendar('start'); }
      if (!state.end) { fail('Selecciona la fecha de devolución.', $('#auReturnBtn')); return openCalendar('end'); }

      const pt = $('#auPickupTime'), rt = $('#auReturnTime');
      const from = when(state.start, pt.value), to = when(state.end, rt.value);
      if (to <= from) {
        $$('.au-hour', form).forEach((el) => el.classList.add('is-invalid'));
        return showMsg('La devolución debe ser posterior al retiro. Cambia la fecha o la hora.');
      }

      const days = Math.max(1, Math.ceil((to - from) / 864e5)); // se cobra por bloques de 24 h
      const car = state.car !== null ? CARS[state.car] : null;
      sumText.textContent = `${state.place} · Retiro ${fmt(state.start)} ${pt.value} → Devolución ${fmt(state.end)} ${rt.value} · ${days} ${days > 1 ? 'días' : 'día'}` + (car ? ` · ${car.cat} de ${car.company}` : '');
      summary.hidden = false;
      const calm = matchMedia('(prefers-reduced-motion: reduce)').matches;
      $('#auOffers').scrollIntoView({ behavior: calm ? 'auto' : 'smooth', block: 'start' });
    });
  }

  /* ---------- Ofertas: tarjetas, carrusel y modal de detalle ---------- */
  const car$ = (c) => `
    ${c.img ? `<img src="${IMG_BASE}/${c.img}" alt="${c.cat}" loading="lazy">` : ''}`;

  const cardHTML = (c, i) => `
    <article class="au-card">
      <div class="au-card__media">${car$(c)}</div>
      <div class="au-card__body">
        <span class="au-card__cat">Autos</span>
        <h3 class="au-card__name">${c.cat}</h3>
        <p class="au-card__place">${c.place}</p>
        <p class="au-card__co">${ICON('car')}Con ${c.company}</p>
        <div class="au-card__foot">
          <div class="au-card__price">
            <small>Tarifa diaria desde</small>
            <p class="au-card__amount">${c.old ? `<s>${money(c.old)}</s>` : ''}<strong>${money(c.price)}</strong></p>
            <span class="au-card__flex">✓ Reserva flexible</span>
          </div>
          <button type="button" class="au-card__cta" data-car="${i}" aria-label="Ver detalles: ${c.cat}, ${c.place}">Ver detalles</button>
        </div>
      </div>
    </article>`;

  // Si la foto no existe todavía, se reemplaza por un marcador con el icono de auto
  const guardImages = (root) => $$('img', root).forEach((img) => {
    const fallback = () => { const ph = document.createElement('div'); ph.className = 'au-ph'; ph.innerHTML = ICON('car'); img.replaceWith(ph); };
    img.addEventListener('error', fallback, { once: true });
    if (img.complete && img.naturalWidth === 0) fallback();
  });

  function initSlider() {
    const root = $('#auSlider'), track = $('.slider__track', root), view = $('.slider__view', root), dots = $('.dots', root);
    const [prev, next] = $$('.au-arrow', root);
    track.innerHTML = CARS.map(cardHTML).join('');
    guardImages(track);

    let i = 0, step = 0, max = 0;
    const update = () => {
      track.style.transform = `translateX(${-i * step}px)`;
      prev.classList.toggle('is-hidden', i === 0);
      next.classList.toggle('is-hidden', i >= max);
      $$('.dot', dots).forEach((d, k) => d.classList.toggle('on', k === i));
    };
    const layout = () => { // recalcula cuántas tarjetas caben según el ancho actual
      const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      step = track.firstElementChild.offsetWidth + gap;
      const perView = Math.max(1, Math.round((view.clientWidth + gap) / step));
      max = Math.max(0, track.children.length - perView);
      i = Math.min(i, max);
      dots.innerHTML = Array.from({ length: max + 1 }, (_, k) => `<button type="button" class="dot" aria-label="Ir a la posición ${k + 1}"></button>`).join('');
      update();
    };
    const go = (d) => { i = Math.min(max, Math.max(0, i + d)); update(); };
    prev.addEventListener('click', () => go(-1));
    next.addEventListener('click', () => go(1));
    dots.addEventListener('click', (e) => {
      const k = $$('.dot', dots).indexOf(e.target.closest('.dot'));
      if (k >= 0) { i = k; update(); }
    });
    // Deslizar con el dedo en celulares
    let x0 = null;
    view.addEventListener('touchstart', (e) => { x0 = e.touches[0].clientX; }, { passive: true });
    view.addEventListener('touchend', (e) => {
      if (x0 === null) return;
      const dx = e.changedTouches[0].clientX - x0; x0 = null;
      if (Math.abs(dx) > 50) go(dx < 0 ? 1 : -1);
    }, { passive: true });
    new ResizeObserver(layout).observe(view);

    // Clic en "Ver detalles" (el botón cubre toda la tarjeta)
    track.addEventListener('click', (e) => {
      const b = e.target.closest('[data-car]');
      if (b) openModal(+b.dataset.car, b);
    });
  }

  const modal = $('#auModal');
  let current = null, lastTrigger = null;
  function openModal(index, trigger) {
    const c = CARS[index];
    current = index; lastTrigger = trigger;
    $('#auModalBody').innerHTML = `
      <div class="au-modal__img">${car$(c)}</div>
      <h3 class="au-modal__name">${c.cat}</h3>
      <p class="au-modal__sub">${c.place} · ${c.city}, ${c.country}</p>
      <p class="au-modal__sub">Con ${c.company}</p>
      <ul class="au-tags">
        <li class="au-tag">${c.seats} pasajeros</li>
        <li class="au-tag">${c.bags} ${c.bags > 1 ? 'maletas' : 'maleta'}</li>
        <li class="au-tag">${c.gear}</li>
        <li class="au-tag">Reserva flexible</li>
      </ul>
      <p class="au-note">Información y tarifa de ejemplo. La disponibilidad real se confirmará cuando exista el backend de Autos.</p>`;
    guardImages($('#auModalBody'));
    $('#auModalPrice').innerHTML = `<small>Tarifa diaria desde</small><strong>${money(c.price)}</strong>`;
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('au-lock');
    $('#auModalClose').focus();
  }
  function closeModal() {
    if (!modal.classList.contains('is-open')) return false;
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('au-lock');
    if (lastTrigger) lastTrigger.focus();
    return true;
  }
  function initModal() {
    $('#auModalClose').addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
    // "Seleccionar vehículo": lleva el lugar al buscador y pide las fechas (solo interfaz, sin reserva real)
    $('#auModalSelect').addEventListener('click', () => {
      const c = CARS[current];
      state.car = current;
      $('#auVehiculo').value = String(current);
      state.place = `${c.city}, ${c.country}`;
      $('#auPlace').value = state.place;
      clearErrors();
      closeModal();
      const calm = matchMedia('(prefers-reduced-motion: reduce)').matches;
      form.scrollIntoView({ behavior: calm ? 'auto' : 'smooth', block: 'center' });
      openCalendar('start');
    });
  }

  /* ---------- Cierre global de paneles ---------- */
  document.addEventListener('click', (e) => { if (!e.target.closest('.dd-wrap')) closeDD(); });
  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (!closeModal()) closeDD();
  });

  initPlace();
  initCalendar();
  initForm();
  initSlider();
  initModal();
})();
