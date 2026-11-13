<x-layout>
  <x-slot:titulo>World Travel | Ayuda</x-slot>

  <x-slot:recursos>
    @vite(['resources/css/styles.css', 'resources/css/ayuda.css', 'resources/js/ayuda.js'])
  </x-slot:recursos>

  <section class="wrap ayuda">
    <h1 class="ayuda__title">¿En qué podemos ayudarte?</h1>
    <p class="ayuda__sub">Encuentra respuestas rápidas o contáctanos.</p>

    <input class="ayuda__search" id="ayudaBuscar" type="search" placeholder="Busca tu pregunta…" aria-label="Buscar en ayuda">

    <div class="ayuda__list" id="ayudaLista">
      <article class="faq">
        <button class="faq__q" type="button" aria-expanded="false">¿Cómo reservo un vuelo?</button>
        <div class="faq__a"><p>Elige origen, destino y fechas en el buscador, selecciona tu vuelo y completa los datos del pasajero.</p></div>
      </article>

      <article class="faq">
        <button class="faq__q" type="button" aria-expanded="false">¿Puedo cambiar o cancelar mi reserva?</button>
        <div class="faq__a"><p>Sí, desde “Mis viajes”. Las condiciones dependen de la tarifa y de la aerolínea.</p></div>
      </article>

      <article class="faq">
        <button class="faq__q" type="button" aria-expanded="false">¿Qué medios de pago aceptan?</button>
        <div class="faq__a"><p>Tarjetas de crédito y débito, y transferencia bancaria.</p></div>
      </article>

      <article class="faq">
        <button class="faq__q" type="button" aria-expanded="false">¿Cuándo recibo mi boleto?</button>
        <div class="faq__a"><p>Te lo enviamos por correo apenas se confirme el pago.</p></div>
      </article>
    </div>

    <p class="ayuda__vacio" id="ayudaVacio" hidden>No encontramos resultados. Escríbenos y te ayudamos.</p>

    <div class="ayuda__contacto">
      <h2>¿Aún necesitas ayuda?</h2>
      <p>Nuestro equipo te atiende todos los días.</p>
      <a class="ayuda__btn" href="tel:0001010001">Llamar: 000-1010001</a>
      <a class="ayuda__btn ayuda__btn--sec" href="https://wa.me/51999999999" target="_blank" rel="noopener">WhatsApp</a>
    </div>
  </section>

</x-layout>