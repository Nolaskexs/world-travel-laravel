<x-layout>
    <x-slot:titulo>World Travel | Acerca de Nosotros</x-slot>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/css/nosotros.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <div class="wrap about-page">
        <!-- Bloque Principal estilo editorial (inspirado en tu referencia) -->
        <section class="about-hero">
            <h1 class="about-title">NOSOTROS</h1>

            <div class="about-narrative">
                <p class="about-lead">
                    <strong>World Travel nació en Lima, Perú, con una misión clara: hacer que descubrir el mundo sea una experiencia accesible, transparente y sin complicaciones. Desde nuestro primer vuelo gestionado, no hemos dejado de conectar personas con sus destinos soñados.</strong>
                </p>

                <p>
                    Nos consolidamos como una agencia de viajes digital integral en Latinoamérica, diseñada para el viajero moderno que busca planificar, personalizar y reservar cada detalle de su aventura desde un solo lugar y con total seguridad.
                </p>

                <p>
                    Actualmente conectamos decenas de destinos nacionales e internacionales —desde Machu Picchu y Buenos Aires hasta Cancún, Nueva York, Roma y Tokio— integrando vuelos con rutas directas o escalas, paquetes turísticos todo incluido, hospedajes selectos y un planificador inteligente de itinerarios a medida.
                </p>
            </div>
        </section>

        <!-- Cifras clave del proyecto -->
        <section class="about-stats" aria-label="Nuestro impacto en cifras">
            <div class="stat-card">
                <span class="stat-num">+50</span>
                <span class="stat-label">Destinos globales con aeropuertos IATA integrados</span>
            </div>
            <div class="stat-card">
                <span class="stat-num">4 en 1</span>
                <span class="stat-label">Vuelos, Alojamientos, Paquetes e Itinerarios personalizados</span>
            </div>
            <div class="stat-card">
                <span class="stat-num">24/7</span>
                <span class="stat-label">Gestión digital de reservas, pasajeros y soporte al viajero</span>
            </div>
        </section>

        <!-- Qué ofrecemos y Pilares -->
        <section class="about-pillars">
            <h2>Lo que mueve a World Travel</h2>
            <div class="pillars-grid">
                <article class="pillar-card">
                    <div class="pillar-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>
                        </svg>
                    </div>
                    <h3>Rutas e Itinerarios a Medida</h3>
                    <p>Nuestro buscador avanzado permite cotizar viajes de ida y vuelta, solo ida o multidestino, armando itinerarios dinámicos para adultos, niños e infantes con transparencia en cada escala.</p>
                </article>

                <article class="pillar-card">
                    <div class="pillar-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3>Seguridad e Innovación</h3>
                    <p>Protegemos cada cuenta y reserva mediante autenticación avanzada de dos factores (2FA) y soporte para Passkeys, brindando un panel dedicado para que cada cliente controle sus viajes.</p>
                </article>

                <article class="pillar-card">
                    <div class="pillar-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <h3>Transparencia Multimoneda</h3>
                    <p>Ofrecemos tarifas claras tanto en Soles peruanos (PEN) como en Dólares (USD), con descuentos exclusivos en paquetes y hospedajes verificados alrededor del mundo.</p>
                </article>
            </div>
        </section>

        <!-- Call to action final -->
        <section class="about-cta">
            <div>
                <h2>¿Listo para tu próxima aventura?</h2>
                <p>Explora nuestras ofertas de temporada o diseña tu propio recorrido paso a paso.</p>
            </div>
            <div class="about-cta__btns">
                <a href="{{ route('inicio') }}" class="btn-primary-wt">Buscar vuelos</a>
                <a href="{{ route('ofertas') }}" class="btn-outline-wt">Ver ofertas</a>
            </div>
        </section>
    </div>
</x-layout>