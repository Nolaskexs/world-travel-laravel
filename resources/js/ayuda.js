(function () {
  var lista = document.getElementById('ayudaLista');
  var buscar = document.getElementById('ayudaBuscar');
  var vacio = document.getElementById('ayudaVacio');
  var catNav = document.getElementById('ayudaCategorias');
  var resetBtn = document.getElementById('ayudaResetFiltros');
  if (!lista) return;

  var categoriaActual = 'todas';

  // Abrir / cerrar acordeón
  lista.addEventListener('click', function (e) {
    var q = e.target.closest('.faq__q');
    if (!q) return;
    var faq = q.closest('.faq');
    if (!faq) return;
    var abierto = faq.classList.toggle('open');
    q.setAttribute('aria-expanded', abierto ? 'true' : 'false');
  });

  function aplicarFiltros() {
    var texto = (buscar ? buscar.value : '').trim().toLowerCase();
    var faqs = lista.querySelectorAll('.faq');
    var visibles = 0;

    faqs.forEach(function (f) {
      var cat = f.getAttribute('data-category') || '';
      var coincideCat = (categoriaActual === 'todas') || (cat === categoriaActual);
      var coincideTexto = !texto || (f.textContent.toLowerCase().indexOf(texto) !== -1);

      var coincide = coincideCat && coincideTexto;
      f.hidden = !coincide;
      if (coincide) {
        visibles++;
      }
    });

    if (vacio) {
      vacio.hidden = visibles !== 0;
    }
  }

  // Filtrar al escribir
  if (buscar) {
    buscar.addEventListener('input', aplicarFiltros);
  }

  // Filtrar por categoría
  if (catNav) {
    catNav.addEventListener('click', function (e) {
      var btn = e.target.closest('.ayuda__cat-btn');
      if (!btn) return;

      var cat = btn.getAttribute('data-cat') || 'todas';
      categoriaActual = cat;

      catNav.querySelectorAll('.ayuda__cat-btn').forEach(function (b) {
        b.classList.remove('active');
      });
      btn.classList.add('active');

      aplicarFiltros();
    });
  }

  // Botón restablecer
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      if (buscar) buscar.value = '';
      categoriaActual = 'todas';
      if (catNav) {
        catNav.querySelectorAll('.ayuda__cat-btn').forEach(function (b) {
          b.classList.toggle('active', b.getAttribute('data-cat') === 'todas');
        });
      }
      aplicarFiltros();
      if (buscar) buscar.focus();
    });
  }
})();
