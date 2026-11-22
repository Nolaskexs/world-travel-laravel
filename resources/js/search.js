"use strict";

(() => {
  const { $, $$, ICON, closeDD } = window.WT; // utilidades expuestas por main.js (que se carga antes)
  const RATE = 3.51,
    PAGE = 5;

  const params = window.__SEARCH_PARAMS__ || {};
  const origIata = params.originIata || (window.__VUELOS_DB__?.[0]?.origen?.codigo_iata) || "DPS";
  const destIata = params.destinationIata || (window.__VUELOS_DB__?.[0]?.destino?.codigo_iata) || "BKK";
  const origName = params.originName || params.origin || (window.__VUELOS_DB__?.[0]?.origen?.nombre) || "Bali";
  const destName = params.destinationName || params.destination || (window.__VUELOS_DB__?.[0]?.destino?.nombre) || "Bangkok";
  const paxCount = params.totalPax || ((params.adults || 1) + (params.kids || 0) + (params.infants || 0));
  const paxText = paxCount > 1 ? `${paxCount} pasajeros` : "1 Adulto";
  const isOneWay = params.trip === "one";

  const startDateStr = params.start || "2026-11-13";
  const [sY, sM, sD] = startDateStr.split("-").map(Number);
  const BASE = new Date(sY || 2026, (sM || 11) - 1, sD || 13);

  const endDateStr = params.end || "2026-11-20";
  const [eY, eM, eD] = endDateStr.split("-").map(Number);
  const RET_BASE = new Date(eY || 2026, (eM || 11) - 1, eD || 20);
  const AIR = [
    "Air France",
    "All Nippon Airways",
    "KLM",
    "LATAM Airlines",
    "United Airlines",
    "Iberia",
    "Japan Airlines",
    "American Airlines",
    "Delta Air Lines",
    "Sky Airline",
    "JetSMART",
    "Thai Airways",
    "Singapore Airlines",
  ];
  const DOW = ["dom", "lun", "mar", "mié", "jue", "vie", "sáb"];
  const MON = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
  const TZ = {
    FUK: 9, HND: 9, NRT: 9, TYO: 9,
    CDG: 1, MAD: 1, FCO: 1, LHR: 0, AMS: 1,
    LIM: -5, CUZ: -5, AQP: -5, TPP: -5, TYL: -5, JUL: -5, ICG: -5,
    BOG: -5, UIO: -5, EZE: -3, GIG: -3, SCL: -4,
    CUN: -5, MEX: -6, MIA: -5, JFK: -5, LAX: -8, DFW: -6, IAH: -6, ATL: -5,
    DPS: 8, BKK: 7, SIN: 8, KUL: 8, CGK: 7, DXB: 4, DOH: 3,
    SYD: 10, CAI: 2, RAK: 1,
  };
  const NAMES = {
    FUK: ["Fukuoka", "FUKUOKA (FUK)"],
    HND: ["Tokio", "TOKIO (HND)"],
    NRT: ["Tokio - Narita", "TOKIO - NARITA (NRT)"],
    TYO: ["Tokio", "TOKIO (TYO)"],
    CDG: ["París", "PARÍS (CDG)"],
    MAD: ["Madrid", "MADRID (MAD)"],
    FCO: ["Roma", "ROMA (FCO)"],
    LHR: ["Londres", "LONDRES (LHR)"],
    AMS: ["Ámsterdam", "ÁMSTERDAM (AMS)"],
    LIM: ["Lima", "LIMA (LIM)"],
    CUZ: ["Cusco", "CUSCO (CUZ)"],
    AQP: ["Arequipa", "AREQUIPA (AQP)"],
    TPP: ["Tarapoto", "TARAPOTO (TPP)"],
    TYL: ["Máncora", "MÁNCORA (TYL)"],
    JUL: ["Puno", "PUNO (JUL)"],
    ICG: ["Ica", "ICA (ICG)"],
    BOG: ["Bogotá", "BOGOTÁ (BOG)"],
    EZE: ["Buenos Aires", "BUENOS AIRES (EZE)"],
    GIG: ["Río de Janeiro", "RÍO DE JANEIRO (GIG)"],
    SCL: ["Santiago", "SANTIAGO (SCL)"],
    CUN: ["Cancún", "CANCÚN (CUN)"],
    MEX: ["Ciudad de México", "CIUDAD DE MÉXICO (MEX)"],
    MIA: ["Miami", "MIAMI (MIA)"],
    JFK: ["Nueva York", "NUEVA YORK (JFK)"],
    LAX: ["Los Ángeles", "LOS ÁNGELES (LAX)"],
    IAH: ["Houston", "HOUSTON (IAH)"],
    DFW: ["Dallas", "DALLAS (DFW)"],
    ATL: ["Atlanta", "ATLANTA (ATL)"],
    DPS: ["Bali", "BALI (DPS)"],
    BKK: ["Bangkok", "BANGKOK (BKK)"],
    SIN: ["Singapur", "SINGAPUR (SIN)"],
    KUL: ["Kuala Lumpur", "KUALA LUMPUR (KUL)"],
    CGK: ["Yakarta", "YAKARTA (CGK)"],
    DXB: ["Dubái", "DUBÁI (DXB)"],
    DOH: ["Doha", "DOHA (DOH)"],
    SYD: ["Sídney", "SÍDNEY (SYD)"],
    CAI: ["El Cairo", "EL CAIRO (CAI)"],
    RAK: ["Marrakech", "MARRAKECH (RAK)"],
  };

  if (origIata && origName) {
    NAMES[origIata] = [origName, `${origName.toUpperCase()} (${origIata})`];
  }
  if (destIata && destName) {
    NAMES[destIata] = [destName, `${destName.toUpperCase()} (${destIata})`];
  }

  if (window.__DESTINOS_DB__) {
    window.__DESTINOS_DB__.forEach(([nom, iata, pais, aero]) => {
      if (iata) NAMES[iata] = [nom, `${nom.toUpperCase()} (${iata})`];
    });
  }

  const hasDB = Array.isArray(window.__VUELOS_DB__) && window.__VUELOS_DB__.length > 0;

  if (hasDB) {
    window.__VUELOS_DB__.forEach((v) => {
      if (!AIR.includes(v.aerolinea)) AIR.push(v.aerolinea);
    });
  }

  // [aerolínea, precio, tasas, escalas, salida ida (min), duración ida (min), salida vuelta, duración vuelta, mano, bodega]
  const DATA = [
    [0, 2269, 1407, 2, 600, 2695, 1160, 2990, 1, 1],
    [0, 2320, 1423, 2, 1270, 3465, 1160, 2990, 1, 1],
    [2, 2301, 1389, 1, 810, 1620, 1260, 1730, 1, 1],
    [1, 2412, 1320, 1, 1290, 1450, 540, 1500, 1, 0],
    [3, 2150, 1210, 2, 1425, 3105, 1000, 3300, 0, 1],
    [4, 3140, 1680, 0, 690, 1230, 1005, 1310, 1, 1],
    [5, 2288, 1441, 2, 1270, 3465, 1220, 2965, 1, 1],
    [2, 2890, 1510, 1, 660, 1745, 1320, 1800, 1, 1],
    [1, 3320, 1750, 0, 720, 1180, 1080, 1240, 1, 1],
    [3, 2475, 1330, 1, 900, 1960, 780, 2040, 0, 0],
  ];

  /* ---------- Modelo ---------- */
  const pad = (n) => String(n).padStart(2, "0");
  const hm = (m) => `${pad(Math.floor(m / 60) % 24)}:${pad(m % 60)}`;
  const dur = (m) => `${Math.floor(m / 60)}h ${pad(m % 60)}m`;
  
  const t12 = (m) => {
    const x = ((m % 1440) + 1440) % 1440, h = Math.floor(x / 60);
    return `${pad(h % 12 || 12)}:${pad(x % 60)} ${h < 12 ? "AM" : "PM"}`;
  };
  
  const dayLabel = (n) => {
    const d = new Date(BASE.getFullYear(), BASE.getMonth(), BASE.getDate() + n);
    return `${DOW[d.getDay()]} ${d.getDate()} ${MON[d.getMonth()]}`;
  };
  const retDateLabel = `${DOW[RET_BASE.getDay()]} ${RET_BASE.getDate()} ${MON[RET_BASE.getMonth()]}`;
  
  const getMidHubs = (s, from, to) => {
    const list = ["LAX", "DFW", "IAH", "MIA", "LIM", "CDG", "MAD", "AMS"].filter((h) => h !== from && h !== to);
    return list.slice(0, s);
  };
  
  const getTz = (code) => (TZ[code] !== undefined ? TZ[code] : 0);

  const mkLeg = (dep, d, fromIata, toIata, route) => {
    const tzDiffMin = (getTz(toIata) - getTz(fromIata)) * 60;
    return {
      route,
      sel: 0,
      opts: [0, 180, 300].map((x) => {
        const depMin = (dep + x) % 1440;
        const totalArrivalMin = dep + d + x + tzDiffMin;
        const arr = ((totalArrivalMin % 1440) + 1440) % 1440;
        const plus = Math.floor(totalArrivalMin / 1440);
        return {
          dep: depMin,
          d: d + x,
          arr,
          plus,
        };
      }),
    };
  };

  const FL = params.busquedaIncompleta
    ? []
    : hasDB
    ? window.__VUELOS_DB__.map((v, id) => {
        let od = 1425;
        if (v.fecha_salida && v.fecha_salida.includes(" ")) {
          const [h, m] = v.fecha_salida.split(" ")[1].split(":").map(Number);
          od = h * 60 + m;
        }
        const fOrigIata = (v.origen && v.origen.codigo_iata) ? v.origen.codigo_iata : origIata;
        const fDestIata = (v.destino && v.destino.codigo_iata) ? v.destino.codigo_iata : destIata;
        const escIatas = (v.escalas || []).map((e) => e.llegada_iata).filter((i) => i && i !== fDestIata);
        const routeOut = [fOrigIata, ...escIatas, fDestIata];
        const routeBack = [fDestIata, ...escIatas.slice().reverse(), fOrigIata];
        const outDur = v.duracion_minutos || 265;
        const backDur = outDur;
        const retDepTime = ((od + 180) % 1440);

        return {
          id,
          air: v.aerolinea,
          price: Math.round(v.precio_adulto),
          tax: Math.round(v.impuestos_tasas),
          stops: v.cantidad_escalas,
          carry: v.incluye_equipaje_mano ? 1 : 0,
          hold: v.incluye_equipaje_bodega ? 1 : 0,
          personal: v.incluye_articulo_personal ? 1 : 0,
          origen: v.origen,
          destino: v.destino,
          escalas: v.escalas,
          duracion_formateada: v.duracion_formateada,
          etiqueta: v.etiqueta,
          out: mkLeg(od, outDur, fOrigIata, fDestIata, routeOut),
          back: mkLeg(retDepTime, backDur, fDestIata, fOrigIata, routeBack),
        };
      })
    : DATA.map(([a, price, tax, stops, od, odur, bd, bdur, carry, hold], id) => ({
        id,
        air: AIR[a] || "World Travel Airlines",
        price,
        tax,
        stops,
        carry,
        hold,
        out: mkLeg(od, odur, origIata, destIata, [origIata, ...getMidHubs(stops, origIata, destIata), destIata]),
        back: mkLeg(bd, bdur, destIata, origIata, [destIata, ...getMidHubs(stops, origIata, destIata).reverse(), origIata]),
      }));

  const total = (f) => f.price + f.tax;
  const tdur = (f) => f.out.opts[0].d + f.back.opts[0].d;

  /* ---------- Estado ---------- */
  const list = $("#list");
  let cur = "USD", sort = "price", shown = PAGE;
  const lo = (i) => Math.floor(Math.min(...DATA.map((r) => r[i])) / 60);
  const hi = (i) => Math.ceil(Math.max(...DATA.map((r) => r[i])) / 60);
  
  const allOutDurs = FL.map((f) => f.out.opts[0].d);
  const allBackDurs = FL.map((f) => f.back.opts[0].d);
  const minDurOut = allOutDurs.length ? Math.max(1, Math.floor(Math.min(...allOutDurs) / 60)) : lo(5);
  const maxDurOut = allOutDurs.length ? Math.ceil(Math.max(...allOutDurs) / 60) + 1 : hi(5);
  const minDurBack = allBackDurs.length ? Math.max(1, Math.floor(Math.min(...allBackDurs) / 60)) : lo(7);
  const maxDurBack = allBackDurs.length ? Math.ceil(Math.max(...allBackDurs) / 60) + 1 : hi(7);

  const RG = [
    { key: "depOut", city: destName, tag: "Ida", min: 0, max: 24, txt: (v) => `Salida hasta ${pad(v)}:00`, edge: (v) => `${pad(v)}:00` },
    { key: "depBack", city: origName, tag: "Vuelta", min: 0, max: 24, txt: (v) => `Salida hasta ${pad(v)}:00`, edge: (v) => `${pad(v)}:00` },
    { key: "durOut", city: destName, tag: "Ida", min: minDurOut, max: maxDurOut, txt: (v) => `Hasta ${v}h`, edge: (v) => `${v}h` },
    { key: "durBack", city: origName, tag: "Vuelta", min: minDurBack, max: maxDurBack, txt: (v) => `Hasta ${v}h`, edge: (v) => `${v}h` },
  ];
  
  const R = Object.fromEntries(RG.map((r) => [r.key, r.max]));

  /* ---------- Plantillas ---------- */
  const fmtN = (n) => Math.round(n * (cur === "USD" ? 1 : RATE)).toLocaleString("de-DE");
  const sym = () => (cur === "USD" ? "US$" : "S/");
  const money = (n) => `<small>${cur === "USD" ? "USD" : "S/"}</small> <b>${fmtN(n)}</b>`;
  const ic = (id, on) => `<svg class="i${on ? "" : " off"}" aria-hidden="true"><use href="#i-${id}"/></svg>`;
  
  const bags = (f) => `<span class="bags" title="Artículo personal, equipaje de mano y de bodega">${ic("bag", 1)}${ic("case", f.carry)}${ic("suit", f.hold)}</span>`;
  
  const rowInner = (o, f) => `
    <span class="time">${hm(o.dep)} - ${hm(o.arr)}${o.plus ? `<sup>+${o.plus}</sup>` : ""}</span>
    <span class="dur">${dur(o.d)}</span>
    <span class="stops">${f.stops ? `${f.stops} ${f.stops > 1 ? "escalas" : "escala"}` : "Directo"}</span>
    ${bags(f)}`;

  const legHTML = (f, k) => {
    const L = f[k],
      fOrig = (f.origen && f.origen.codigo_iata) ? f.origen.codigo_iata : origIata,
      fDest = (f.destino && f.destino.codigo_iata) ? f.destino.codigo_iata : destIata,
      fOrigNom = (f.origen && f.origen.nombre) ? f.origen.nombre : (NAMES[fOrig] ? NAMES[fOrig][0] : origName),
      fDestNom = (f.destino && f.destino.nombre) ? f.destino.nombre : (NAMES[fDest] ? NAMES[fDest][0] : destName),
      [aIata, bIata] = k === "out" ? [fOrig, fDest] : [fDest, fOrig],
      [aNom, bNom] = k === "out" ? [fOrigNom, fDestNom] : [fDestNom, fOrigNom];
    const dateText = k === "out" ? dayLabel(0) : retDateLabel;
      
    return `
      <div class="leg" data-leg="${k}">
        <div class="leg__h">
          <span class="ph" title="${f.air}"><svg class="i" style="width:16px;height:16px;fill:#64748b;"><use href="#i-plane"/></svg></span>
          <h3>
            <span class="leg__dest"><span class="leg__city">${aNom}</span> <span class="leg__code">(${aIata})</span></span>
            ${ICON("arrow")}
            <span class="leg__dest"><span class="leg__city">${bNom}</span> <span class="leg__code">(${bIata})</span></span>
          </h3>
          ${k === "out" ? `<button type="button" class="detail" data-detail>Ver detalle ${ICON("info")}</button>` : ""}
        </div>
        <p class="date">${dateText} <span class="leg__air-inline">· ${f.air}</span></p>
        <div class="col leg__main">
          <div>
            <div class="row" data-main>${rowInner(L.opts[L.sel], f)}</div>
          </div>
        </div>
        <div class="col leg__opts">
          <div>
            ${L.opts.map((o, i) => `
              <label class="row radio">
                <input type="radio" name="r${f.id}${k}" value="${i}"${i === L.sel ? " checked" : ""}>
                ${rowInner(o, f)}
              </label>`).join("")}
          </div>
        </div>
        <button type="button" class="more" data-more aria-expanded="false">
          <span>Ver más horarios</span>${ICON("down")}
        </button>
      </div>`;
  };

//Card HTML: muestra la tarjeta de cada vuelo, con los horarios seleccionados y el precio final. Los badges se muestran solo si el vuelo es el más barato o la mejor opción.
  const cardHTML = (f, tags) => `
    <article class="fcard" data-id="${f.id}">
      <div>
        ${tags.length ? `<div class="badges">${tags.map(([t, c]) => `<span class="bdg${c}">${t}</span>`).join("")}</div>` : ""}
        ${legHTML(f, "out")}
        ${isOneWay ? "" : legHTML(f, "back")}
      </div>
      <div class="buy">
        <p class="buy__l buy__l--big">
          <span>Precio por adulto</span>
          <span>${money(f.price)}</span>
        </p>
        <p class="buy__l">
          <span>${paxText}</span>
          <span>${money(f.price * paxCount)}</span>
        </p>
        <p class="buy__l">
          <span>Impuestos, tasas y cargos</span>
          <span>${money(f.tax * paxCount)}</span>
        </p>
        <p class="buy__l buy__l--tot">
          <span>Precio final</span>
          <span>${money(total(f) * paxCount)}</span>
        </p>
        <a class="btn-dark" href="/asientos?vuelo_id=${f.id || ''}&origin=${encodeURIComponent(origName || 'Lima')}&destination=${encodeURIComponent(destName || 'Tokio')}&origin_iata=${encodeURIComponent(origIata || 'LIM')}&destination_iata=${encodeURIComponent(destIata || 'HND')}&codigo_vuelo=${encodeURIComponent(f.code || (f.out && f.out.num) || 'WT-8183')}&precio=${encodeURIComponent(f.price || 0)}&adultos=${encodeURIComponent(params.adults || 1)}&menores=${encodeURIComponent(params.kids || 0)}&fecha_viaje=${encodeURIComponent(params.start || startDateStr || '')}">Reservar vuelo y elegir asientos</a>
      </div>
    </article>`;

  /* ---------- Filtros: checkboxes ---------- */
  const cnt = (fn) => FL.filter(fn).length;
  
  const chk = (v, label, n, dis, logo) => `
    <label class="chk">
      <input type="checkbox" value="${v}"${v === "all" ? " checked" : ""}${dis ? " disabled" : ""}>
      ${label} (${n})
      ${logo ? '<span class="ph"></span>' : ""}
    </label>`;
    
  const picked = (g) => new Set($$("input:checked", $(`[data-group=${g}]`)).map((i) => i.value).filter((v) => v !== "all"));

  $("[data-group=bag]").innerHTML =
    chk("all", "Todas las opciones", FL.length) +
    chk("personal", "Artículo personal", FL.length, 1) +
    chk("carry", "Equipaje de mano", cnt((f) => f.carry)) +
    chk("hold", "Equipaje de bodega", cnt((f) => f.hold));

  $("[data-group=stops]").innerHTML =
    chk("all", "Todas las opciones", FL.length) +
    chk("0", "Directo", cnt((f) => !f.stops)) +
    chk("1", "1 escala", cnt((f) => f.stops === 1)) +
    chk("2", "2 o más escalas", cnt((f) => f.stops >= 2));

  $("[data-group=air]").innerHTML =
    `<div class="tools"><button type="button" data-air="1">Seleccionar todo</button><button type="button" data-air="0">Borrar todo</button></div>` +
    AIR.map((a) => chk(a, a, cnt((f) => f.air === a), 0, 1)).join("");

  /* ---------- Filtros: sliders con avión ---------- */
  const later = (fn, ms) => {
    let t;
    return () => {
      clearTimeout(t);
      t = setTimeout(fn, ms);
    };
  };
  
  const refresh = later(() => {
    shown = PAGE;
    render();
  }, 120);

  const rgHTML = (r) => `
    <div class="rg">
      <p class="rg__t">${ICON("plane")}Vuelo a <b>${r.city}</b><span class="tag">${r.tag}</span></p>
      <p class="rg__v">${r.txt(r.max)}</p>
      <div class="rng" data-key="${r.key}">
        <div class="rng__track"><div class="rng__trail"></div></div>
        <input type="range" min="${r.min}" max="${r.max}" step="1" value="${r.max}" aria-label="Vuelo a ${r.city} (${r.tag})">
        <span class="rng__plane">${ICON("plane")}</span>
      </div>
      <div class="rg__e">
        <span>${r.edge(r.min)}</span>
        <span>${r.edge(r.max)}</span>
      </div>
    </div>`;

  $("#rg-time").innerHTML = RG.slice(0, 2).map(rgHTML).join("");
  $("#rg-dur").innerHTML = RG.slice(2).map(rgHTML).join("");

  function initRange(el) {
    const r = RG.find((x) => x.key === el.dataset.key),
      input = $("input", el),
      plane = $(".rng__plane", el),
      trail = $(".rng__trail", el),
      label = $(".rg__v", el.parentNode);
      
    let pos = 100, target = 100, raf = 0;
    
    // Interpola el avión hacia el valor real: el rastro lo sigue "viajando" aunque el usuario salte con un clic.
    const tick = () => {
      pos += (target - pos) * 0.22;
      if (Math.abs(target - pos) < 0.05) pos = target;
      plane.style.left = pos + "%";
      trail.style.width = pos + "%"; // el rastro llega exactamente hasta el avión
      raf = pos === target ? 0 : requestAnimationFrame(tick);
    };
    
    input.addEventListener("input", () => {
      target = ((input.value - input.min) / (input.max - input.min)) * 100;
      label.textContent = r.txt(+input.value);
      R[r.key] = +input.value;
      if (!raf) raf = requestAnimationFrame(tick);
      refresh();
    });
    
    input.addEventListener("pointerdown", () => el.classList.add("is-drag"));
    window.addEventListener("pointerup", () => el.classList.remove("is-drag"));
  }
  $$(".rng").forEach(initRange);

  /* ---------- Render ---------- */
  function render(anim = false) {
    if (params.busquedaIncompleta) {
      const msg = params.avisoBusqueda || "Por favor, ingresa una ciudad de origen y una ciudad de destino para realizar la búsqueda de vuelos.";
      list.innerHTML = `
        <div class="search-notice-card" role="alert">
          <div class="search-notice-card__icon">
            <svg class="i" style="width:28px;height:28px;fill:#d97706;" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z"/></svg>
          </div>
          <div class="search-notice-card__body">
            <h3>Búsqueda incompleta</h3>
            <p>${msg}</p>
          </div>
        </div>
      `;
      $("#empty").hidden = true;
      $("#moreBtn").hidden = true;
      $("#count").textContent = "0 vuelos encontrados";
      $$(".sort button").forEach((b) => {
        $("small", b).textContent = "—";
      });
      return;
    }

    const S = picked("stops"), B = picked("bag"), A = picked("air");
    
    const res = FL.filter((f) => 
      (!S.size || S.has(String(Math.min(f.stops, 2)))) &&
      [...B].every((k) => f[k]) &&
      (!A.size || A.has(f.air)) &&
      f.out.opts[0].dep <= R.depOut * 60 &&
      f.back.opts[0].dep <= R.depBack * 60 &&
      f.out.opts[0].d <= R.durOut * 60 &&
      f.back.opts[0].d <= R.durBack * 60
    );
    
    const mp = Math.min(...res.map(total));
    const mt = Math.min(...res.map(tdur));
    const sc = (f) => total(f) / mp + tdur(f) / mt; // "mejor opción": equilibrio precio / duración
    
    const order = {
      price: (a, b) => total(a) - total(b),
      time: (a, b) => tdur(a) - tdur(b),
      best: (a, b) => sc(a) - sc(b),
    };
    
    const top = (k) => [...res].sort(order[k])[0];
    
    $$(".sort button").forEach((b) => {
      const t = top(b.dataset.sort);
      $("small", b).textContent = t ? `${sym()} ${fmtN(total(t) * paxCount)} · ${dur(tdur(t))}` : "—";
    });
    
    const cheap = top("price");
    const best = top("best");
    
    list.classList.toggle("fx", anim);
    list.innerHTML = [...res]
      .sort(order[sort])
      .slice(0, shown)
      .map((f) => cardHTML(f, [
        f === cheap && ["El más barato", ""], 
        f === best && ["Mejor opción", " bdg--2"]
      ].filter(Boolean)))
      .join("");
      
    $("#empty").hidden = !!res.length;
    $("#moreBtn").hidden = shown >= res.length;
    $("#count").textContent = `${res.length} ${res.length === 1 ? "vuelo encontrado" : "vuelos encontrados"}`;
  }

  /* ---------- Eventos del sidebar ---------- */
  const side = $("#filters");
  
  side.addEventListener("click", (e) => {
    const head = e.target.closest(".acc__head");
    if (head) { // abre/cierra el acordeón; la animación de altura la hace el CSS (grid 0fr → 1fr)
      const open = head.parentNode.classList.toggle("is-open");
      head.setAttribute("aria-expanded", open);
    }
    
    const air = e.target.closest("[data-air]");
    if (air) {
      $$("input", $("[data-group=air]")).forEach((i) => {
        i.checked = air.dataset.air === "1";
      });
      shown = PAGE;
      render();
    }
    
    const c = e.target.closest("[data-cur]");
    if (c) {
      cur = c.dataset.cur;
      $("#curLabel").textContent = c.textContent;
      $$("[data-cur]").forEach((o) => o.classList.toggle("is-sel", o === c));
      closeDD("currency");
      render();
    }
  });

  side.addEventListener("change", (e) => {
    const i = e.target,
      box = i.closest("[data-group]");
    if (i.type !== "checkbox" || !box) return;
    
    if (box.dataset.group !== "air") { // "Todas las opciones" es excluyente con el resto
      if (i.value === "all") {
        $$("input", box).forEach((x) => {
          x.checked = x === i;
        });
      } else {
        $("input[value=all]", box).checked = false;
      }
      if (!$$("input:checked", box).length) {
        $("input[value=all]", box).checked = true;
      }
    }
    shown = PAGE;
    render();
  });

  /* Drawer de filtros (móvil) */
  const drawer = (open) => {
    side.classList.toggle("is-open", open);
    $("#scrim").classList.toggle("is-open", open);
    document.body.classList.toggle("lock", open);
  };

  $("#openFilters")?.addEventListener("click", () => drawer(true));
  $("#closeFilters")?.addEventListener("click", () => drawer(false));
  $("#scrim")?.addEventListener("click", () => drawer(false));
  matchMedia("(min-width:992px)").addEventListener("change", () => drawer(false));

  /* ---------- Orden y paginación ---------- */
  $$(".sort button").forEach((b) =>
    b.addEventListener("click", () => {
      sort = b.dataset.sort;
      $$(".sort button").forEach((x) => {
        x.classList.toggle("is-active", x === b);
        x.setAttribute("aria-selected", x === b);
      });
      render(true);
    })
  );

  $("#moreBtn").addEventListener("click", () => {
    shown += PAGE;
    render();
  });

  /* ---------- Tarjetas (delegación: funcionan aunque se re-rendericen) ---------- */
  list.addEventListener("click", (e) => {
    const card = e.target.closest(".fcard");
    if (!card) return;
    
    const more = e.target.closest("[data-more]");
    if (more) { // alterna fila seleccionada ↔ lista de radios; el CSS anima la altura
      const open = more.closest(".leg").classList.toggle("is-open");
      more.setAttribute("aria-expanded", open);
      more.firstElementChild.textContent = open ? "Ver menos horarios" : "Ver más horarios";
    }
    
    if (e.target.closest("[data-detail]")) {
      openModal(FL[+card.dataset.id]);
    }
  });

  list.addEventListener("change", (e) => { // al elegir otro horario se actualiza la fila visible
    if (e.target.type !== "radio") return;
    const leg = e.target.closest(".leg"),
      f = FL[+e.target.closest(".fcard").dataset.id],
      L = f[leg.dataset.leg];
    L.sel = +e.target.value;
    $("[data-main]", leg).innerHTML = rowInner(L.opts[L.sel], f);
  });

  /* ---------- Modal de itinerario ---------- */
  const modal = $("#modal");
  let lastFocus = null;

  function legDetail(f, k) {
    const fOrig = (f.origen && f.origen.codigo_iata) ? f.origen.codigo_iata : origIata;
    const fDest = (f.destino && f.destino.codigo_iata) ? f.destino.codigo_iata : destIata;
    const fOrigNom = (f.origen && f.origen.nombre) ? f.origen.nombre : (NAMES[fOrig] ? NAMES[fOrig][0] : origName);
    const fDestNom = (f.destino && f.destino.nombre) ? f.destino.nombre : (NAMES[fDest] ? NAMES[fDest][0] : destName);

    if (f.escalas && f.escalas.length > 0 && k === "out") {
      let h = `
        <div class="dhead">
          <b>Salida a ${fDestNom} <span>· ${dayLabel(0)}</span></b>
          <span>Duración total: <b>${f.duracion_formateada || dur(f.out.opts[f.out.sel].d)}</b></span>
        </div>`;

      f.escalas.forEach((esc, idx) => {
        const depTime = esc.hora_salida ? esc.hora_salida.split(" ")[1] : "11:45";
        const arrTime = esc.hora_llegada ? esc.hora_llegada.split(" ")[1] : "14:00";
        const escOrigNom = esc.origen || (NAMES[esc.origen_iata] ? NAMES[esc.origen_iata][0] : esc.origen_iata);
        const escDestNom = esc.llegada || (NAMES[esc.llegada_iata] ? NAMES[esc.llegada_iata][0] : esc.llegada_iata);
        h += `
          <p class="op">
            <span class="ph"><svg class="i" style="width:14px;height:14px;fill:#64748b;"><use href="#i-plane"/></svg></span>${esc.numero_vuelo} · Operado por ${esc.aerolinea_operadora}
          </p>
          <ol class="tl">
            <li>
              <time>${depTime}</time>
              <i class="dot"></i>
              <div>
                <b>${escOrigNom}</b><small>${NAMES[esc.origen_iata] ? NAMES[esc.origen_iata][1] : esc.origen_iata}</small>
              </div>
            </li>
            <li class="tl__fly">
              <span>${dur(esc.duracion_tramo_minutos)}</span>${ICON("plane")}
            </li>
            <li>
              <time>${arrTime}</time>
              <i class="dot dot--end"></i>
              <div>
                <b>${escDestNom}</b><small>${NAMES[esc.llegada_iata] ? NAMES[esc.llegada_iata][1] : esc.llegada_iata}</small>
                <em>Llega el ${dayLabel(idx + 1)}</em>
              </div>
            </li>
          </ol>`;

        if (esc.cambio_avion || esc.tiempo_espera_minutos) {
          h += `
            <div class="lay">
              ${ICON("clock")}
              <span>${dur(esc.tiempo_espera_minutos || 120)}</span>Cambio de avión en <b>${escDestNom}</b>
            </div>`;
        }
      });
      return h;
    }

    const L = f[k],
      o = L.opts[L.sel],
      r = L.route,
      stops = r.length - 2,
      fl = Math.round((o.d - stops * 120) / (r.length - 1));
      
    let u = (k === "out" ? 0 : 3) * 1440 + o.dep - getTz(r[0]) * 60; // minutos UTC desde el día base
    
    let h = `
      <div class="dhead">
        <b>${k === "out" ? `Salida a ${fDestNom}` : `Vuelta a ${fOrigNom}`} <span>· ${k === "out" ? dayLabel(0) : retDateLabel}</span></b>
        <span>Duración total: <b>${dur(o.d)}</b></span>
      </div>`;
      
    for (let i = 0; i < r.length - 1; i++) {
      const a = r[i],
        b = r[i + 1],
        dl = u + getTz(a) * 60,
        al = u + fl + getTz(b) * 60,
        next = Math.floor(al / 1440) !== Math.floor(dl / 1440);
        
      h += `
        <p class="op">
          <span class="ph"><svg class="i" style="width:14px;height:14px;fill:#64748b;"><use href="#i-plane"/></svg></span>${(f.id + 1) * 113 + i * 29} · Operado por ${f.air}
        </p>
        <ol class="tl">
          <li>
            <time>${t12(dl)}</time>
            <i class="dot"></i>
            <div>
              <b>${NAMES[a] ? NAMES[a][0] : a}</b><small>${NAMES[a] ? NAMES[a][1] : a}</small>
            </div>
          </li>
          <li class="tl__fly">
            <span>${dur(fl)}</span>${ICON("plane")}
          </li>
          <li>
            <time>${t12(al)}</time>
            <i class="dot dot--end"></i>
            <div>
              <b>${NAMES[b] ? NAMES[b][0] : b}</b><small>${NAMES[b] ? NAMES[b][1] : b}</small>
              ${next ? `<em>Llega el ${dayLabel(Math.floor(al / 1440))}</em>` : ""}
            </div>
          </li>
        </ol>`;
        
      if (i < r.length - 2) {
        h += `
          <div class="lay">
            ${ICON("clock")}
            <span>${dur(120)}</span>Cambio de avión en <b>${NAMES[b] ? NAMES[b][0] : b}</b>
          </div>`;
      }
      u += fl + 120;
    }
    return h;
  }

  function openModal(f) {
    $("#mBody").innerHTML =
      legDetail(f, "out") + (isOneWay ? "" : legDetail(f, "back")) + `<p class="inc">Equipaje incluido: ${bags(f)}</p>`;
    $("#mBody").scrollTop = 0;
    $("#mPrice").innerHTML = money(total(f) * paxCount);
    lastFocus = document.activeElement;
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("lock");
    $("#mClose").focus();
  }

  function closeModal() { // devuelve el foco al botón que abrió el modal
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("lock");
    if (lastFocus) lastFocus.focus();
  }

  $("#mClose").addEventListener("click", closeModal);
  $("#mGo").addEventListener("click", closeModal);
  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.classList.contains("is-open")) closeModal();
  });

  render(true);
})();