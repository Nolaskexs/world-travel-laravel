<x-layout>
    <x-slot:titulo>World Travel | Búsqueda</x-slot>
    
    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/css/search.css', 'resources/js/searchmain.js', 'resources/js/search.js'])
        <script>
            window.__DESTINOS_DB__ = @json($destinosData ?? $destinosCatalogo ?? []);
            window.__VUELOS_DB__ = @json($vuelosData ?? []);
            window.__SEARCH_PARAMS__ = @json($searchParams ?? []);
        </script>
    </x-slot:recursos>

    <!-- partial: buscador compacto -->
    <section class="wrap wrap--search" aria-label="Buscador de vuelos">
      <form class="sf sf--bar" id="searchForm" method="GET" action="{{ route('search') }}" autocomplete="off">
        <input type="hidden" name="start" id="inputStart" value="{{ $start ?? '2026-11-13' }}">
        <input type="hidden" name="end" id="inputEnd" value="{{ $end ?? '2026-11-20' }}">
        <input type="hidden" name="adults" id="inputAdults" value="{{ $adults ?? 3 }}">
        <input type="hidden" name="kids" id="inputKids" value="{{ $kids ?? 0 }}">
        <input type="hidden" name="infants" id="inputInfants" value="{{ $infants ?? 0 }}">

        <div class="sf__top">
          <fieldset class="trip">
            <legend class="sr">Tipo de viaje</legend>
            <label class="radio">
              <input type="radio" name="trip" value="round" {{ ($trip ?? 'round') === 'round' ? 'checked' : '' }}>Ida y vuelta
            </label>
            <label class="radio">
              <input type="radio" name="trip" value="one" {{ ($trip ?? '') === 'one' ? 'checked' : '' }}>Solo ida
            </label>
            <label class="radio">
              <input type="radio" name="trip" value="multi" {{ ($trip ?? '') === 'multi' ? 'checked' : '' }}>Multidestino
            </label>
          </fieldset>
          <div class="dd-wrap">
            <button type="button" class="chip" data-dd="pax" aria-expanded="false">
              <svg class="i"><use href="#i-user"/></svg>
              <span id="paxLabel">{{ $totalPax ?? (($adults ?? 1) + ($kids ?? 0) + ($infants ?? 0)) }} {{ ($totalPax ?? (($adults ?? 1) + ($kids ?? 0) + ($infants ?? 0))) > 1 ? 'pasajeros' : 'pasajero' }}</span>
            </button>
            <div class="dd dd--pax" id="dd-pax" hidden>
              <div class="prow" data-pax="adults">
                <div>
                  <strong>Adultos</strong><small>Mayores de 12 años</small>
                </div>
                <div class="cnt">
                  <button type="button" data-step="-1" aria-label="Quitar adulto"><svg class="i"><use href="#i-minus"/></svg></button>
                  <output>{{ $adults ?? 1 }}</output>
                  <button type="button" data-step="1" aria-label="Agregar adulto"><svg class="i"><use href="#i-plus"/></svg></button>
                </div>
              </div>
              <div class="prow" data-pax="kids">
                <div>
                  <strong>Menores</strong><small>De 2 a 11 años</small>
                </div>
                <div class="cnt">
                  <button type="button" data-step="-1" aria-label="Quitar menor"><svg class="i"><use href="#i-minus"/></svg></button>
                  <output>{{ $kids ?? 0 }}</output>
                  <button type="button" data-step="1" aria-label="Agregar menor"><svg class="i"><use href="#i-plus"/></svg></button>
                </div>
              </div>
              <div class="prow" data-pax="infants">
                <div>
                  <strong>Infantes</strong><small>Menores de 2 años</small>
                </div>
                <div class="cnt">
                  <button type="button" data-step="-1" aria-label="Quitar infante"><svg class="i"><use href="#i-minus"/></svg></button>
                  <output>{{ $infants ?? 0 }}</output>
                  <button type="button" data-step="1" aria-label="Agregar infante"><svg class="i"><use href="#i-plus"/></svg></button>
                </div>
              </div>
              <div class="pax-actions">
                <button type="button" class="btn-ghost" data-act="cancel">Cancelar</button>
                <button type="button" class="btn-dark" data-act="apply">Aplicar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="sf__row">
          <div class="dd-wrap">
            <label class="field">
              <svg class="i"><use href="#i-pin"/></svg>
              <input id="origin" name="origin" value="{{ $origin ?? '' }}" placeholder="Origen" aria-label="Origen" data-dd="origin">
            </label>
            <div class="dd dd--list" id="dd-origin" hidden></div>
          </div>
          
          <button type="button" class="swap" aria-label="Intercambiar origen y destino">
            <svg class="i"><use href="#i-swap"/></svg>
          </button>
          
          <div class="dd-wrap">
            <label class="field">
              <svg class="i"><use href="#i-pin"/></svg>
              <input id="destination" name="destination" value="{{ $destination ?? '' }}" placeholder="Destino" aria-label="Destino" data-dd="destination">
            </label>
            <div class="dd dd--list" id="dd-destination" hidden></div>
          </div>
          
          <div class="dates dd-wrap">
            <button type="button" class="field" id="startBtn" data-date="{{ $start ?? '2026-11-13' }}" data-dd="cal" data-field="start" aria-expanded="false">
              <svg class="i"><use href="#i-cal"/></svg>
              <span id="startLabel">{{ $start ? \Carbon\Carbon::parse($start)->translatedFormat('d M') : '13 nov' }}</span>
            </button>
            <button type="button" class="field" id="endBtn" data-date="{{ $end ?? '2026-11-20' }}" data-dd="cal" data-field="end" aria-expanded="false">
              <svg class="i"><use href="#i-cal"/></svg>
              <span id="endLabel">{{ $end ? \Carbon\Carbon::parse($end)->translatedFormat('d M') : '20 nov' }}</span>
            </button>
            
            <div class="dd dd--cal" id="dd-cal" data-field="start" hidden>
              <div class="cal">
                <button type="button" class="cal__nav cal__prev" aria-label="Mes anterior">
                  <svg class="i"><use href="#i-left"/></svg>
                </button>
                <button type="button" class="cal__nav cal__next" aria-label="Mes siguiente">
                  <svg class="i"><use href="#i-right"/></svg>
                </button>
                <div class="cal__m"></div>
                <div class="cal__m"></div>
              </div>
            </div>
          </div>
          <button class="go" type="submit">
            <svg class="i"><use href="#i-search"/></svg>
            <span>Buscar</span>
          </button>
        </div>
      </form>
    </section>

    <!-- Filtros de búsqueda y resultados -->
    <div class="wrap wrap--search">
      <div class="scrim" id="scrim"></div>
      
      <div class="layout">
        <aside class="filters" id="filters" aria-label="Filtros de búsqueda">
        <div class="fhead">
          <h2>Filtros</h2>
          <button class="ico-btn" id="closeFilters" type="button" aria-label="Cerrar filtros">
            <svg class="i"><use href="#i-x"/></svg>
          </button>
        </div>
        
        <section class="cur">
          <h3>Moneda</h3>
          <small>Tipo de cambio: S/ 3,51</small>
          <div class="dd-wrap">
            <button type="button" class="field" data-dd="currency" aria-expanded="false">
              <span id="curLabel">Dólares</span>
              <svg class="i chev"><use href="#i-down"/></svg>
            </button>
            <div class="dd dd--cur" id="dd-currency" hidden>
              <button type="button" class="opt is-sel" data-cur="USD">Dólares</button>
              <button type="button" class="opt" data-cur="PEN">Soles</button>
            </div>
          </div>
        </section>
        
        <section class="acc is-open">
          <button class="acc__head" type="button" aria-expanded="true">Equipaje<svg class="i"><use href="#i-down"/></svg></button>
          <div class="acc__body">
            <div>
              <div class="acc__pad" data-group="bag"></div>
            </div>
          </div>
        </section>
        
        <section class="acc is-open">
          <button class="acc__head" type="button" aria-expanded="true">Escalas<svg class="i"><use href="#i-down"/></svg></button>
          <div class="acc__body">
            <div>
              <div class="acc__pad" data-group="stops"></div>
            </div>
          </div>
        </section>
        
        <section class="acc is-open">
          <button class="acc__head" type="button" aria-expanded="true">Aerolíneas<svg class="i"><use href="#i-down"/></svg></button>
          <div class="acc__body">
            <div>
              <div class="acc__pad" data-group="air"></div>
            </div>
          </div>
        </section>
        
        <section class="acc is-open">
          <button class="acc__head" type="button" aria-expanded="true">Horarios<svg class="i"><use href="#i-down"/></svg></button>
          <div class="acc__body">
            <div>
              <div class="acc__pad" id="rg-time"></div>
            </div>
          </div>
        </section>
        
        <section class="acc is-open">
          <button class="acc__head" type="button" aria-expanded="true">Duración<svg class="i"><use href="#i-down"/></svg></button>
          <div class="acc__body">
            <div>
              <div class="acc__pad" id="rg-dur"></div>
            </div>
          </div>
        </section>
      </aside>

      <!-- Filtro de vuelos encontrados -->
      <section class="res" aria-label="Resultados de vuelos">
        <div class="sort" role="tablist" aria-label="Ordenar por">
          <button type="button" role="tab" class="is-active" aria-selected="true" data-sort="price">
            <b>El más barato</b><small></small>
          </button>
          <button type="button" role="tab" aria-selected="false" data-sort="best">
            <b>Mejor opción</b><small></small>
          </button>
          <button type="button" role="tab" aria-selected="false" data-sort="time">
            <b>Menor duración</b><small></small>
          </button>
        </div>
        <p class="count" id="count" aria-live="polite"></p>
        <div class="list" id="list"></div>
        <p class="nores" id="empty" hidden>No hay vuelos con esos filtros. Prueba ampliando los rangos o quitando alguna opción.</p>
        <button type="button" class="btn-more" id="moreBtn">Mostrar más resultados</button>
      </section>
      </div>
    </div>

    <div class="modal" id="modal" aria-hidden="true">
      <div class="modal__box" role="dialog" aria-modal="true" aria-labelledby="mTitle">
        <div class="modal__h">
          <h2 id="mTitle">Detalle de itinerario</h2>
          <button type="button" class="ico-btn" id="mClose" aria-label="Cerrar">
            <svg class="i"><use href="#i-x"/></svg>
          </button>
        </div>
        <div class="modal__b" id="mBody"></div>
        <div class="modal__f">
          <span id="mPrice"></span>
          <button type="button" class="btn-dark" id="mGo">Continuar</button>
        </div>
      </div>
    </div>
</x-layout>