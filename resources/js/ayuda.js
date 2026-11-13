(function () {
  var lista = document.getElementById('ayudaLista');
  var buscar = document.getElementById('ayudaBuscar');
  var vacio = document.getElementById('ayudaVacio');
  if (!lista) return;

  // Abrir / cerrar preguntas
  lista.addEventListener('click', function (e) {
    var q = e.target.closest('.faq__q');
    if (!q) return;
    var faq = q.parentElement;
    var abierto = faq.classList.toggle('open');
    q.setAttribute('aria-expanded', abierto);
  });

  // Filtrar mientras escribe
  buscar.addEventListener('input', function () {
    var texto = buscar.value.trim().toLowerCase();
    var visibles = 0;
    lista.querySelectorAll('.faq').forEach(function (f) {
      var coincide = f.textContent.toLowerCase().indexOf(texto) !== -1;
      f.hidden = !coincide;
      if (coincide) visibles++;
    });
    vacio.hidden = visibles !== 0;
  });
})();