// Menú lateral móvil y placeholders de imágenes del panel de administración
const side = document.getElementById('sidebar');
const scrim = document.querySelector('.scrim');
const opener = document.querySelector('[data-drawer-open]');

if (opener && side) {
  const drawer = (open) => {
    side.classList.toggle('is-open', open);
    if (scrim) scrim.classList.toggle('is-open', open);
    document.body.classList.toggle('lock', open); // bloquea el scroll del fondo con el menú abierto
    opener.setAttribute('aria-expanded', open);
    const closer = side.querySelector('.sidebar__close');
    (open && closer ? closer : opener).focus();
  };

  opener.addEventListener('click', () => drawer(true));
  document.querySelectorAll('[data-drawer-close]').forEach((b) => b.addEventListener('click', () => drawer(false)));
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && side.classList.contains('is-open')) drawer(false); });
  matchMedia('(min-width:1024px)').addEventListener('change', (e) => { if (e.matches && side.classList.contains('is-open')) drawer(false); });
}

// Si el archivo existe, marca el contenedor (.has-img) para ocultar el texto del placeholder.
// Se revisa también img.complete porque la imagen puede haber cargado antes de que corra este script.
document.querySelectorAll('img[data-fallback]').forEach((img) => {
  const ok = () => img.naturalWidth && img.parentElement.classList.add('has-img');
  img.complete ? ok() : img.addEventListener('load', ok);
});

// Flechas de desplazamiento para la barra de navegación del administrador
const menuScroll = document.getElementById('adminMenuBarScroll');
const btnMenuPrev = document.getElementById('adminMenuPrev');
const btnMenuNext = document.getElementById('adminMenuNext');

if (menuScroll && btnMenuPrev && btnMenuNext) {
  const updateMenuArrows = () => {
    // Si la pantalla es lo suficientemente ancha y no hay desborde, ocultar ambas flechas
    const hasOverflow = menuScroll.scrollWidth > (menuScroll.clientWidth + 8);
    if (!hasOverflow) {
      btnMenuPrev.style.display = 'none';
      btnMenuNext.style.display = 'none';
      return;
    }

    // Solo se muestran cuando se achica o hay desbordamiento horizontal
    btnMenuPrev.style.display = 'inline-flex';
    btnMenuNext.style.display = 'inline-flex';

    const atStart = menuScroll.scrollLeft <= 5;
    const atEnd = (menuScroll.scrollLeft + menuScroll.clientWidth) >= (menuScroll.scrollWidth - 6);

    btnMenuPrev.style.opacity = atStart ? '0.3' : '1';
    btnMenuPrev.style.pointerEvents = atStart ? 'none' : 'auto';
    btnMenuPrev.style.cursor = atStart ? 'default' : 'pointer';

    btnMenuNext.style.opacity = atEnd ? '0.3' : '1';
    btnMenuNext.style.pointerEvents = atEnd ? 'none' : 'auto';
    btnMenuNext.style.cursor = atEnd ? 'default' : 'pointer';
  };

  updateMenuArrows();
  window.addEventListener('resize', updateMenuArrows);
  window.addEventListener('orientationchange', updateMenuArrows);
  menuScroll.addEventListener('scroll', updateMenuArrows);
  document.addEventListener('DOMContentLoaded', updateMenuArrows);

  btnMenuPrev.addEventListener('click', () => {
    menuScroll.scrollBy({ left: -220, behavior: 'smooth' });
    setTimeout(updateMenuArrows, 260);
  });

  btnMenuNext.addEventListener('click', () => {
    menuScroll.scrollBy({ left: 220, behavior: 'smooth' });
    setTimeout(updateMenuArrows, 260);
  });
}
