<x-layout>
    <x-slot:titulo>World Travel | Inicio</x-slot>
    
    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

  <!-- partial: buscador -->
  <section class="wrap" aria-label="Buscador de vuelos">
    <form class="sf" id="searchForm" autocomplete="off">
      <div class="sf__top">
        <fieldset class="trip"><legend class="sr">Tipo de viaje</legend>
          <label class="radio"><input type="radio" name="trip" value="round" checked>Ida y vuelta</label>
          <label class="radio"><input type="radio" name="trip" value="one">Solo ida</label>
          <label class="radio"><input type="radio" name="trip" value="multi">Multidestino</label>
        </fieldset>
        <div class="dd-wrap">
          <button type="button" class="chip" data-dd="pax" aria-expanded="false"><svg class="i"><use href="#i-user"/></svg><span id="paxLabel">1 pasajero</span></button>
          <div class="dd dd--pax" id="dd-pax" hidden>
            <div class="prow" data-pax="adults"><div><strong>Adultos</strong><small>Mayores de 12 años</small></div><div class="cnt"><button type="button" data-step="-1" aria-label="Quitar adulto"><svg class="i"><use href="#i-minus"/></svg></button><output>1</output><button type="button" data-step="1" aria-label="Agregar adulto"><svg class="i"><use href="#i-plus"/></svg></button></div></div>
            <div class="prow" data-pax="kids"><div><strong>Menores</strong><small>De 2 a 11 años</small></div><div class="cnt"><button type="button" data-step="-1" aria-label="Quitar menor"><svg class="i"><use href="#i-minus"/></svg></button><output>0</output><button type="button" data-step="1" aria-label="Agregar menor"><svg class="i"><use href="#i-plus"/></svg></button></div></div>
            <div class="prow" data-pax="infants"><div><strong>Infantes</strong><small>Menores de 2 años</small></div><div class="cnt"><button type="button" data-step="-1" aria-label="Quitar infante"><svg class="i"><use href="#i-minus"/></svg></button><output>0</output><button type="button" data-step="1" aria-label="Agregar infante"><svg class="i"><use href="#i-plus"/></svg></button></div></div>
            <div class="pax-actions"><button type="button" class="btn-ghost" data-act="cancel">Cancelar</button><button type="button" class="btn-dark" data-act="apply">Aplicar</button></div>
          </div>
        </div>
      </div>

      <div class="sf__row">
        <div class="dd-wrap"><label class="field"><svg class="i"><use href="#i-pin"/></svg><input id="origin" name="origin" value="Origen" placeholder="Origen" aria-label="Origen" data-dd="origin"></label><div class="dd dd--list" id="dd-origin" hidden></div></div>
        <button type="button" class="swap" aria-label="Intercambiar origen y destino"><svg class="i"><use href="#i-swap"/></svg></button>
        <div class="dd-wrap"><label class="field"><svg class="i"><use href="#i-pin"/></svg><input id="destination" name="destination" placeholder="Destino" aria-label="Destino" data-dd="destination"></label><div class="dd dd--list" id="dd-destination" hidden></div></div>
        <div class="dates dd-wrap">
          <button type="button" class="field" id="startBtn" data-dd="cal" data-field="start" aria-expanded="false"><svg class="i"><use href="#i-cal"/></svg><span id="startLabel">Salida</span></button>
          <button type="button" class="field" id="endBtn" data-dd="cal" data-field="end" aria-expanded="false"><svg class="i"><use href="#i-cal"/></svg><span id="endLabel">Retorno</span></button>
          <div class="dd dd--cal" id="dd-cal" data-field="start" hidden>
            <div class="cal">
              <button type="button" class="cal__nav cal__prev" aria-label="Mes anterior"><svg class="i"><use href="#i-left"/></svg></button>
              <button type="button" class="cal__nav cal__next" aria-label="Mes siguiente"><svg class="i"><use href="#i-right"/></svg></button>
              <div class="cal__m"></div><div class="cal__m"></div>
            </div>
          </div>
        </div>
        <button class="go" type="submit"><svg class="i"><use href="#i-search"/></svg><span>Buscar</span></button>
      </div>
    </form>
  </section>

  <!-- partial: hero -->
  <section class="wrap hero" aria-roledescription="carrusel" aria-label="Destacados">
    <div class="hero__frame">
      <div class="hero__view"><div class="hero__track">
        <div class="hero__slide"><div class="ph">Banner 1<br>1200×420</div></div>
        <div class="hero__slide"><div class="ph">Banner 2<br>1200×420</div></div>
        <div class="hero__slide"><div class="ph">Banner 3<br>1200×420</div></div>
        <div class="hero__slide"><div class="ph">Banner 4<br>1200×420</div></div>
      </div></div>
      <button class="arrow arrow--l" data-dir="-1" aria-label="Anterior"><svg class="i"><use href="#i-left"/></svg></button>
      <button class="arrow arrow--r" data-dir="1" aria-label="Siguiente"><svg class="i"><use href="#i-right"/></svg></button>
    </div>
  </section>

  <!-- partial: ofertas (las tarjetas se generan desde js/main.js; reemplazar por un loop EJS/PHP) -->
  <section class="wrap offers">
    <h2>Paquetes turísticos con los mejores precios</h2>
    <div class="slider" data-slider="packages">
      <div class="slider__view"><div class="slider__track"></div></div>
      <div class="slider__nav">
        <button class="arrow" data-dir="-1" aria-label="Anterior"><svg class="i"><use href="#i-left"/></svg></button>
        <div class="dots"></div>
        <button class="arrow" data-dir="1" aria-label="Siguiente"><svg class="i"><use href="#i-right"/></svg></button>
      </div>
    </div>
    <h2>Hospedajes para disfrutar</h2>
    <div class="slider" data-slider="hotels">
      <div class="slider__view"><div class="slider__track"></div></div>
      <div class="slider__nav">
        <button class="arrow" data-dir="-1" aria-label="Anterior"><svg class="i"><use href="#i-left"/></svg></button>
        <div class="dots"></div>
        <button class="arrow" data-dir="1" aria-label="Siguiente"><svg class="i"><use href="#i-right"/></svg></button>
      </div>
    </div>
  </section>

  <!-- partial: contacto, app y newsletter -->
  <section class="wrap">
    <div class="contact">
      <img src="{{ asset('icons/support2.jpg') }}" class="ph contact__img">
      <div><h3>Contáctanos</h3>
        <div class="socials">
          <img class="ph soc" src="{{ asset('icons/2023_Facebook_icon.svg.png') }}" aria-label="Facebook" href="#">
          <img class="ph soc" src="{{ asset('icons/instagram-circle-icon.png') }}" aria-label="Instagram" href="#">
          <img class="ph soc" src="{{ asset('icons/x-icon.jpg') }}" aria-label="X" href="#">
          <img class="ph soc" src="{{ asset('icons/what.png') }}" aria-label="WhatsApp" href="#">
        </div>
      </div>
    </div>
    <h2 class="app-title">Recibe ofertas y descubre el mundo</h2>
    <div class="app">
      <img class="ph app__phone" src="{{ asset('icons/support-icon.png') }}" alt="App World Travel">
      <p>En nuestra app encontrarás precios más bajos y más beneficios</p>
      <div class="ph app__qr"><br></div> <!-- QR Code -->
    </div>
    <div class="news">
      <h3>Ofertas exclusivas en tu email</h3>
      <form class="nl" id="newsletter" novalidate>
        <span class="nl__ico"><svg class="i"><use href="#i-mail"/></svg></span>
        <label class="field"><input type="email" name="email" placeholder="Ingresa tu correo" aria-label="Correo electrónico" required></label>
        <button class="btn-dark" type="submit">Recibir</button>
      </form>
      <p class="nl__msg" id="nlMsg" role="status"></p>
    </div>
  </section>
</x-layout>