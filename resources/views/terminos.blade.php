<x-layout>
    <link rel="stylesheet" href="{{ asset('css/informativas.css') }}">

    <section class="wt-info">
        <div class="wt-info__container">
            <div class="wt-info__heading">
                <h1>Términos y condiciones</h1>
                <p>Condiciones de uso de la versión académica de World Travel.</p>
            </div>

            <div class="wt-info__card">
                <h2>1. Naturaleza del proyecto</h2>
                <p>World Travel es una plataforma desarrollada con fines académicos para demostrar la planificación de viajes, la selección de paquetes, el registro de reservas y la simulación de pagos.</p>
                <p>La versión actual no constituye una agencia de viajes habilitada para contratar servicios turísticos reales.</p>
            </div>

            <div class="wt-info__card">
                <h2>2. Registro y acceso</h2>
                <p>Para utilizar determinadas funciones, el usuario debe crear una cuenta y mantener la confidencialidad de sus credenciales. El acceso a funciones administrativas está limitado a usuarios autorizados.</p>
            </div>

            <div class="wt-info__card">
                <h2>3. Paquetes y reservas</h2>
                <p>Los destinos, fechas, disponibilidades e importes presentados sirven para demostrar el funcionamiento de World Travel. Las reservas registradas en el entorno de prueba no garantizan alojamiento, transporte ni otros servicios reales.</p>
            </div>

            <div class="wt-info__card">
                <h2>4. Métodos y verificación de pago</h2>
                <p>El sistema incluye un simulador académico para representar operaciones aprobadas y rechazadas con distintos métodos de pago. No procesa tarjetas, billeteras digitales ni transferencias bancarias reales.</p>
            </div>

            <div class="wt-info__card">
                <h2>5. Constancias de demostración</h2>
                <p>Las constancias generadas identificadas como «SIMULACIÓN ACADÉMICA» documentan pruebas del sistema. No acreditan cobros ni sustituyen boletas o facturas electrónicas.</p>
            </div>

            <div class="wt-info__card">
                <h2>6. Modificaciones y cancelaciones</h2>
                <p>Los estados de las reservas pueden modificarse durante las pruebas administrativas. Antes de ofrecer servicios al público deberán definirse políticas formales de cancelación, devolución y atención al consumidor.</p>
            </div>

            <div class="wt-info__card">
                <h2>7. Uso responsable</h2>
                <p>Los usuarios deben utilizar esta versión únicamente para demostraciones. No deben introducir datos de tarjetas reales, claves bancarias ni información personal de terceros sin autorización.</p>
            </div>

            <div class="wt-info__footer">
                <a href="{{ route('ayuda') }}" class="wt-info__button">Consultar al Centro de Ayuda</a>
            </div>
        </div>
    </section>
</x-layout>
