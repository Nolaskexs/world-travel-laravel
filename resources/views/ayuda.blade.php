<x-layout>
  <x-slot:titulo>World Travel | Centro de Ayuda</x-slot>

  <x-slot:recursos>
    @vite(['resources/css/styles.css', 'resources/css/ayuda.css', 'resources/js/ayuda.js'])
  </x-slot:recursos>

  <section class="wrap ayuda">
    <header class="ayuda__header">
      <h1 class="ayuda__title">¿En qué podemos ayudarte?</h1>
      <p class="ayuda__sub">Encuentra respuestas rápidas organizadas por categorías o contáctanos.</p>
    </header>

    <div class="ayuda__search-wrap">
      <div class="ayuda__search-box">
        <svg class="ayuda__search-icon" viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="11" cy="11" r="8" fill="none" stroke="currentColor" stroke-width="2"/>
          <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <input class="ayuda__search" id="ayudaBuscar" type="search" placeholder="Busca tu pregunta o tema (ej. equipaje, factura, cancelación)..." aria-label="Buscar en ayuda">
      </div>

      {{-- Apartado de Categorías debajo del buscador --}}
      <nav class="ayuda__categories" id="ayudaCategorias" aria-label="Categorías de ayuda">
        <button type="button" class="ayuda__cat-btn active" data-cat="todas">
          <span>Todas</span>
          <small class="ayuda__cat-count">16</small>
        </button>
        <button type="button" class="ayuda__cat-btn" data-cat="vuelos">
          <span>Reservas y Vuelos</span>
          <small class="ayuda__cat-count">4</small>
        </button>
        <button type="button" class="ayuda__cat-btn" data-cat="pagos">
          <span>Pagos y Facturación</span>
          <small class="ayuda__cat-count">4</small>
        </button>
        <button type="button" class="ayuda__cat-btn" data-cat="cambios">
          <span>Cambios y Cancelaciones</span>
          <small class="ayuda__cat-count">3</small>
        </button>
        <button type="button" class="ayuda__cat-btn" data-cat="equipaje">
          <span>Equipaje y Check-in</span>
          <small class="ayuda__cat-count">3</small>
        </button>
        <button type="button" class="ayuda__cat-btn" data-cat="alojamientos">
          <span>Alojamientos y Hoteles</span>
          <small class="ayuda__cat-count">3</small>
        </button>
      </nav>
    </div>

    <div class="ayuda__list" id="ayudaLista">
      {{-- Categoría: Reservas y Vuelos --}}
      <article class="faq" data-category="vuelos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Cómo reservo un vuelo o paquete turístico?</span>
          <span class="faq__tag">Reservas y Vuelos</span>
        </button>
        <div class="faq__a">
          <p>Elige tu origen, destino y fechas en nuestro buscador del inicio o catálogo de paquetes. Selecciona la opción deseada, completa los datos de los pasajeros y continúa al proceso de confirmación y pago seguro.</p>
        </div>
      </article>

      <article class="faq" data-category="vuelos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Con cuánta anticipación debo llegar al aeropuerto?</span>
          <span class="faq__tag">Reservas y Vuelos</span>
        </button>
        <div class="faq__a">
          <p>Para vuelos nacionales recomendamos presentarte al menos 2 horas antes de la salida programada. Para vuelos internacionales recomendamos de 3 a 4 horas de anticipación para trámites de aduanas, migración y controles de seguridad.</p>
        </div>
      </article>

      <article class="faq" data-category="vuelos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Cómo selecciono o cambio mis asientos en el vuelo?</span>
          <span class="faq__tag">Reservas y Vuelos</span>
        </button>
        <div class="faq__a">
          <p>Puedes elegir tus asientos directamente durante el flujo de reserva con el mapa interactivo del avión, o gestionarlos posteriormente desde tu panel de usuario en "Mis viajes" en la opción "Elegir Asientos".</p>
        </div>
      </article>

      <article class="faq" data-category="vuelos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Puedo viajar con niños pequeños o infantes?</span>
          <span class="faq__tag">Reservas y Vuelos</span>
        </button>
        <div class="faq__a">
          <p>Sí. Los infantes menores de 2 años viajan en brazos de un adulto acompañante abonando solo tasas e impuestos aplicables. Los niños a partir de 2 años cumplidos requieren su propio asiento y boleto emitido.</p>
        </div>
      </article>

      {{-- Categoría: Pagos y Facturación --}}
      <article class="faq" data-category="pagos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Qué medios de pago aceptan?</span>
          <span class="faq__tag">Pagos y Facturación</span>
        </button>
        <div class="faq__a">
          <p>Aceptamos tarjetas de crédito y débito (Visa, Mastercard, American Express, Diners Club) procesadas bajo altos estándares de cifrado y seguridad, además de transferencias bancarias locales.</p>
        </div>
      </article>

      <article class="faq" data-category="pagos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Los precios incluyen impuestos y cargos de servicio?</span>
          <span class="faq__tag">Pagos y Facturación</span>
        </button>
        <div class="faq__a">
          <p>Sí, en World Travel todos los precios mostrados incluyen impuestos de ley (IGV), tasas aeroportuarias y cargos administrativos. Los valores mostrados en Soles son referenciales según el tipo de cambio oficial de 3.51.</p>
        </div>
      </article>

      <article class="faq" data-category="pagos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Cómo solicito mi comprobante o factura electrónica?</span>
          <span class="faq__tag">Pagos y Facturación</span>
        </button>
        <div class="faq__a">
          <p>Durante la confirmación de pago puedes elegir entre Boleta o Factura con RUC y razón social. El comprobante tributario en formato PDF y XML se envía a tu correo electrónico registrado una vez aprobada la transacción.</p>
        </div>
      </article>

      <article class="faq" data-category="pagos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Qué ocurre si mi pago es rechazado o queda pendiente?</span>
          <span class="faq__tag">Pagos y Facturación</span>
        </button>
        <div class="faq__a">
          <p>Tu reserva quedará registrada con estado "Pendiente" en tu panel de "Mis viajes". Podrás ingresar en cualquier momento y reintentar el pago con otra tarjeta antes de la fecha límite establecida.</p>
        </div>
      </article>

      {{-- Categoría: Cambios y Cancelaciones --}}
      <article class="faq" data-category="cambios">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Puedo cambiar la fecha o cancelar mi reserva?</span>
          <span class="faq__tag">Cambios y Cancelaciones</span>
        </button>
        <div class="faq__a">
          <p>Sí. Puedes solicitar modificaciones o cancelaciones desde la sección "Mis viajes" o escribiendo a nuestro soporte. La aplicación de penalidades o diferencias de tarifa dependerá de las condiciones de la aerolínea y paquete adquirido.</p>
        </div>
      </article>

      <article class="faq" data-category="cambios">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿En qué plazo se procesa el reembolso si cancelo mi reserva?</span>
          <span class="faq__tag">Cambios y Cancelaciones</span>
        </button>
        <div class="faq__a">
          <p>Una vez aprobada la solicitud de devolución, el reintegro se realiza al mismo medio de pago utilizado en un plazo promedio de 7 a 15 días hábiles, sujeto a los tiempos de liquidación de tu banco emisor.</p>
        </div>
      </article>

      <article class="faq" data-category="cambios">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Qué ocurre si la aerolínea cancela o reprograma mi vuelo?</span>
          <span class="faq__tag">Cambios y Cancelaciones</span>
        </button>
        <div class="faq__a">
          <p>Te notificaremos por correo electrónico y mensajería tan pronto como la aerolínea reporte el evento. Tendrás derecho a reubicación sin cargo en el itinerario más próximo o a la devolución total del importe abonado.</p>
        </div>
      </article>

      {{-- Categoría: Equipaje y Check-in --}}
      <article class="faq" data-category="equipaje">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Cómo y cuándo realizo el web check-in?</span>
          <span class="faq__tag">Equipaje y Check-in</span>
        </button>
        <div class="faq__a">
          <p>El web check-in se activa entre 24 y 48 horas antes de la salida programada del vuelo. Puedes realizarlo desde el botón "Hacer Check-in" en tu panel de "Mis viajes" o ingresando tu código de reserva.</p>
        </div>
      </article>

      <article class="faq" data-category="equipaje">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Cuánto equipaje puedo llevar en mi viaje?</span>
          <span class="faq__tag">Equipaje y Check-in</span>
        </button>
        <div class="faq__a">
          <p>Todas las reservas incluyen un bolso o mochila personal de hasta 8 kg. El equipaje de mano en cabina (hasta 10 kg) y la maleta de bodega (hasta 23 kg) varían según la categoría de la tarifa seleccionada al reservar.</p>
        </div>
      </article>

      <article class="faq" data-category="equipaje">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Qué artículos están restringidos en el equipaje de mano?</span>
          <span class="faq__tag">Equipaje y Check-in</span>
        </button>
        <div class="faq__a">
          <p>Por normativas internacionales de aviación, están prohibidos líquidos en envases superiores a 100 ml, aerosoles, objetos cortopunzantes, herramientas pesadas, sustancias inflamables y baterías de litio que excedan la potencia estándar.</p>
        </div>
      </article>

      {{-- Categoría: Alojamientos y Hoteles --}}
      <article class="faq" data-category="alojamientos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Cuáles son los horarios de check-in y check-out en los hoteles?</span>
          <span class="faq__tag">Alojamientos</span>
        </button>
        <div class="faq__a">
          <p>Por regla general, la hora de ingreso (check-in) es a partir de las 14:00 o 15:00 hrs, y la salida (check-out) se realiza entre las 11:00 y 12:00 hrs. Puedes coordinar ingresos anticipados o salidas tardías directamente con la recepción del hotel.</p>
        </div>
      </article>

      <article class="faq" data-category="alojamientos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿El precio de mi alojamiento incluye desayuno o servicios extra?</span>
          <span class="faq__tag">Alojamientos</span>
        </button>
        <div class="faq__a">
          <p>Cada hotel o resort especifica en su ficha de detalle si incluye desayuno buffet, acceso a piscina, gimnasio o wifi de cortesía. Los servicios de lavandería, room service o consumos del minibar se abonan de forma independiente.</p>
        </div>
      </article>

      <article class="faq" data-category="alojamientos">
        <button class="faq__q" type="button" aria-expanded="false">
          <span class="faq__q-text">¿Cómo solicito una petición especial para mi habitación?</span>
          <span class="faq__tag">Alojamientos</span>
        </button>
        <div class="faq__a">
          <p>Al confirmar tu alojamiento puedes indicar notas adicionales (cama matrimonial, dos camas individuales, cuna de bebé, piso alto o accesibilidad). Las remitimos inmediatamente al establecimiento hotelero.</p>
        </div>
      </article>
    </div>

    <div class="ayuda__vacio" id="ayudaVacio" hidden>
      <p>No encontramos preguntas que coincidan con tu búsqueda.</p>
      <button type="button" class="ayuda__reset-btn" id="ayudaResetFiltros">Restablecer filtros y ver todas las preguntas</button>
    </div>

    <div class="ayuda__contacto">
      <h2>¿Aún necesitas ayuda?</h2>
      <p>Nuestro equipo de soporte personalizado te atiende todos los días.</p>
      <a class="ayuda__btn" href="tel:0001010001">Llamar: 000-1010001</a>
      <a class="ayuda__btn ayuda__btn--sec" href="https://wa.me/51999999999" target="_blank" rel="noopener">WhatsApp</a>
    </div>
  </section>
</x-layout>
