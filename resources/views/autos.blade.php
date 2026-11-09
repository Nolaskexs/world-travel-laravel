@php
    // Horas disponibles cada 30 min (00:00 – 23:30). Se reutilizan en retiro y devolución.
    $horas = [];
    for ($m = 0; $m < 1440; $m += 30) {
        $horas[] = sprintf('%02d:%02d', intdiv($m, 60), $m % 60);
    }
@endphp

<x-layout>
    <x-slot:titulo>World Travel | Autos</x-slot>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/css/autos.css', 'resources/js/autos.js'])
    </x-slot:recursos>

    <!-- partial: buscador de autos (mismo estilo del buscador de vuelos: .sf) -->
    <section class="wrap" aria-label="Buscador de autos">
        {{-- GET hacia la misma ruta: cuando exista backend bastará con leer los parámetros en el controlador --}}
        <form class="sf au-search" id="auSearchForm" method="GET" action="{{ route('autos') }}" autocomplete="off" novalidate>
            <div class="sf__top">
                <h1 class="au-title"><svg class="i"><use href="#i-car"/></svg>Alquiler de autos</h1>
            </div>

            <div class="au-row">
                <!-- Lugar de retiro -->
                <div class="dd-wrap">
                    <label class="field" id="auPlaceField">
                        <svg class="i"><use href="#i-pin"/></svg>
                        <input id="auPlace" name="lugar_retiro" value="Lima, Perú" placeholder="Lugar de retiro"
                               aria-label="Lugar de retiro" role="combobox" aria-autocomplete="list"
                               aria-controls="auDd-lugar" aria-expanded="false" data-au-dd="lugar">
                    </label>
                    <div class="dd dd--list" id="auDd-lugar" hidden></div>
                </div>

                <!-- Fechas y horas (el calendario es compartido por retiro y devolución) -->
                <div class="dd-wrap au-dates">
                    <button type="button" class="field" id="auPickupBtn" data-au-dd="cal" data-field="start" aria-expanded="false">
                        <svg class="i"><use href="#i-cal"/></svg>
                        <span id="auPickupLabel">Retiro</span>
                    </button>

                    <label class="field au-hour">
                        <span class="au-hour__lbl">Hora</span>
                        <select id="auPickupTime" name="hora_retiro" class="au-hour__sel" aria-label="Hora de retiro">
                            @foreach ($horas as $h)
                                <option value="{{ $h }}" @selected($h === '11:00')>{{ $h }}</option>
                            @endforeach
                        </select>
                        <svg class="i au-hour__chev"><use href="#i-down"/></svg>
                    </label>

                    <button type="button" class="field" id="auReturnBtn" data-au-dd="cal" data-field="end" aria-expanded="false">
                        <svg class="i"><use href="#i-cal"/></svg>
                        <span id="auReturnLabel">Devolución</span>
                    </button>

                    <label class="field au-hour">
                        <span class="au-hour__lbl">Hora</span>
                        <select id="auReturnTime" name="hora_devolucion" class="au-hour__sel" aria-label="Hora de devolución">
                            @foreach ($horas as $h)
                                <option value="{{ $h }}" @selected($h === '11:00')>{{ $h }}</option>
                            @endforeach
                        </select>
                        <svg class="i au-hour__chev"><use href="#i-down"/></svg>
                    </label>

                    <input type="hidden" id="auPickupDate" name="fecha_retiro">
                    <input type="hidden" id="auReturnDate" name="fecha_devolucion">

                    <div class="dd dd--cal" id="auDd-cal" data-field="start" hidden>
                        <div class="cal">
                            <button type="button" class="cal__nav cal__prev" aria-label="Mes anterior"><svg class="i"><use href="#i-left"/></svg></button>
                            <button type="button" class="cal__nav cal__next" aria-label="Mes siguiente"><svg class="i"><use href="#i-right"/></svg></button>
                            <div class="cal__m"></div><div class="cal__m"></div>
                        </div>
                    </div>
                </div>

                <button class="go" type="submit"><svg class="i"><use href="#i-search"/></svg><span>Buscar</span></button>
            </div>

            {{-- Reservado para el backend: id del vehículo elegido en las ofertas --}}
            <input type="hidden" id="auVehiculo" name="vehiculo">

            <p class="au-msg" id="auMsg" role="status" aria-live="polite"></p>
        </form>
    </section>

    <!-- partial: resumen de la búsqueda (se muestra al buscar con datos válidos) -->
    <section class="wrap au-summary" id="auSummary" aria-live="polite" hidden>
        <div class="au-summary__box">
            <strong>Tu búsqueda</strong>
            <p class="au-summary__text" id="auSummaryText"></p>
            <p class="au-summary__note">Por ahora se muestran ofertas de ejemplo: la disponibilidad real se conectará cuando exista el backend de Autos.</p>
        </div>
    </section>

    {{-- partial: ofertas (las tarjetas se generan desde resources/js/autos.js; cuando exista la tabla de vehículos se reemplazan por un bucle de Blade) --}}
    <section class="wrap offers au-offers" id="auOffers" aria-labelledby="auOffersTitle" data-img-base="{{ asset('icons/autos') }}">
        <h2 id="auOffersTitle">Ofertas en renta de autos en múltiples destinos</h2>

        <div class="slider au-slider" id="auSlider">
            <div class="au-slider__stage">
                <div class="slider__view"><div class="slider__track"></div></div>
                <button type="button" class="arrow au-arrow au-arrow--l" data-dir="-1" aria-label="Anterior"><svg class="i"><use href="#i-left"/></svg></button>
                <button type="button" class="arrow au-arrow au-arrow--r" data-dir="1" aria-label="Siguiente"><svg class="i"><use href="#i-right"/></svg></button>
            </div>
            <div class="dots"></div>
        </div>
    </section>

    <!-- partial: detalle del vehículo (se llena desde autos.js) -->
    <div class="au-modal" id="auModal" aria-hidden="true">
        <div class="au-modal__box" role="dialog" aria-modal="true" aria-labelledby="auModalTitle">
            <div class="au-modal__head">
                <h2 id="auModalTitle">Detalle del vehículo</h2>
                <button type="button" class="au-modal__close" id="auModalClose" aria-label="Cerrar">
                    <svg class="i"><use href="#i-x"/></svg>
                </button>
            </div>
            <div class="au-modal__body" id="auModalBody"></div>
            <div class="au-modal__foot">
                <div class="au-modal__price" id="auModalPrice"></div>
                <button type="button" class="btn-dark" id="auModalSelect">Seleccionar vehículo</button>
            </div>
        </div>
    </div>
</x-layout>