// Menú lateral móvil y placeholders de imágenes del panel de administración
const side = document.getElementById('sidebar');
const scrim = document.querySelector('.scrim');
const opener = document.querySelector('[data-drawer-open]');

const drawer = (open) => {
  side.classList.toggle('is-open', open);
  scrim.classList.toggle('is-open', open);
  document.body.classList.toggle('lock', open); // bloquea el scroll del fondo con el menú abierto
  opener.setAttribute('aria-expanded', open);
  (open ? side.querySelector('.sidebar__close') : opener).focus();
};

opener.addEventListener('click', () => drawer(true));
document.querySelectorAll('[data-drawer-close]').forEach((b) => b.addEventListener('click', () => drawer(false)));
document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && side.classList.contains('is-open')) drawer(false); });
matchMedia('(min-width:1024px)').addEventListener('change', (e) => { if (e.matches && side.classList.contains('is-open')) drawer(false); });

// Si el archivo existe, marca el contenedor (.has-img) para ocultar el texto del placeholder.
// Se revisa también img.complete porque la imagen puede haber cargado antes de que corra este script.
document.querySelectorAll('img[data-fallback]').forEach((img) => {
  const ok = () => img.naturalWidth && img.parentElement.classList.add('has-img');
  img.complete ? ok() : img.addEventListener('load', ok);
});
