
<style>
    .wt-footer {
        background: #0b1c27;
        color: #ffffff;
        padding: 48px 24px 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .wt-footer * {
        box-sizing: border-box;
    }

    .wt-footer__container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .wt-footer__content {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1.2fr;
        gap: 40px;
        padding: 30px 0 45px;
        border-top: 1px solid #53616b;
        border-bottom: 1px solid #53616b;
    }

    .wt-footer__column {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
    }

    .wt-footer__title {
        color: white;
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 10px;
    }

    .wt-footer__link {
        color: #d6dde2;
        font-size: 14px;
        text-decoration: none;
        line-height: 1.6;
        transition: color .2s ease;
    }

    .wt-footer__link:hover {
        color: white;
        text-decoration: underline;
    }

    .wt-footer__text {
        color: #d6dde2;
        font-size: 14px;
        line-height: 1.7;
        margin: 0;
    }

    .wt-footer__book {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        padding: 12px;
        border-radius: 9px;
        text-decoration: none;
        color: #0b1c27;
        max-width: 210px;
        transition: transform .2s ease;
    }

    .wt-footer__book:hover {
        transform: translateY(-3px);
        color: #0b1c27;
    }

    .wt-footer__book img {
        width: 175px;
        max-width: 100%;
        max-height: 85px;
        height: auto;
        object-fit: contain;
    }

    .wt-footer__book span {
        font-size: 12px;
        font-weight: 700;
        text-align: center;
    }

    .wt-footer__bottom {
        text-align: center;
        padding-top: 22px;
    }

    .wt-footer__bottom p {
        color: #ccd5dc;
        font-size: 13px;
        margin: 0;
    }

    .wt-footer a:focus-visible {
        outline: 2px solid white;
        outline-offset: 4px;
    }

    @media (max-width: 900px) {
        .wt-footer__content {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 35px;
        }
    }

    @media (max-width: 520px) {
        .wt-footer__content {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .wt-footer {
            padding: 32px 20px 22px;
        }
    }
</style>

<footer class="wt-footer">
    <div class="wt-footer__container">

        <div class="wt-footer__content">

            <!-- EXPLORA WORLD TRAVEL -->
            <nav class="wt-footer__column"
                 aria-label="Explora World Travel">

                <h2 class="wt-footer__title">
                    Explora World Travel
                </h2>

                <a href="{{ route('paquetes') }}"
                   class="wt-footer__link">
                    Paquetes turísticos
                </a>

                <a href="{{ route('ofertas') }}"
                   class="wt-footer__link">
                    Ofertas de viaje
                </a>

                <a href="{{ route('alojamientos') }}"
                   class="wt-footer__link">
                    Alojamientos
                </a>

                <a href="{{ route('search') }}"
                   class="wt-footer__link">
                    Buscar viajes
                </a>

            </nav>

            <!-- INFORMACIÓN Y SOPORTE -->
            <nav class="wt-footer__column"
                 aria-label="Información y soporte">

                <h2 class="wt-footer__title">
                    Información y soporte
                </h2>

                <a href="{{ route('preguntas.frecuentes') }}"
                   class="wt-footer__link">
                    Preguntas frecuentes
                </a>

                <a href="{{ route('ayuda') }}"
                   class="wt-footer__link">
                    Centro de ayuda
                </a>

            </nav>

            <!-- NUESTRA EMPRESA -->
            <nav class="wt-footer__column"
                 aria-label="Nuestra empresa">

                <h2 class="wt-footer__title">
                    Nuestra empresa
                </h2>

                <a href="{{ route('nosotros') }}"
                   class="wt-footer__link">
                    Sobre nosotros
                </a>

                <a href="{{ route('terminos') }}"
                   class="wt-footer__link">
                    Términos y condiciones
                </a>

                <a href="{{ route('privacidad') }}"
                   class="wt-footer__link">
                    Política de privacidad
                </a>

            </nav>

            <!-- LIBRO DE RECLAMACIONES -->
            <div class="wt-footer__column">

                <h2 class="wt-footer__title">
                    Libro de Reclamaciones
                </h2>

                <p class="wt-footer__text">
                    Tu opinión es importante para nosotros.
                    Registra aquí tus consultas, quejas
                    o reclamos.
                </p>

                <a href="{{ route('reclamaciones') }}"
                   class="wt-footer__book"
                   aria-label="Abrir el Libro de Reclamaciones">

                    <img
                        src="{{ asset('icons/libro-reclamaciones.png') }}"
                        alt="Imagen del Libro de Reclamaciones"
                        loading="lazy"
                    >

                    <span>
                        Acceder al Libro de Reclamaciones
                    </span>

                </a>

            </div>

        </div>

        <div class="wt-footer__bottom">
            <p>
                © {{ date('Y') }} World Travel.
                Todos los derechos reservados.
            </p>
        </div>

    </div>
</footer>
