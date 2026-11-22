<x-layout>
    <link rel="stylesheet" href="{{ asset('css/informativas.css') }}">

    <section class="wt-info">
        <div class="wt-info__container">
            <div class="wt-info__heading">
                <h1>Política de privacidad</h1>
                <p>Información sobre el tratamiento de datos en la versión académica de World Travel.</p>
            </div>

            <div class="wt-info__card">
                <h2>1. Datos que utiliza la plataforma</h2>
                <p>Durante las pruebas se pueden registrar datos proporcionados por los usuarios, entre ellos:</p>
                <ul>
                    <li>Nombre y correo electrónico de la cuenta.</li>
                    <li>Teléfono, documento de identidad y datos de pasajeros.</li>
                    <li>Paquetes seleccionados, fechas y detalles de reservas.</li>
                    <li>Importes, métodos y resultados de pagos simulados.</li>
                </ul>
            </div>

            <div class="wt-info__card">
                <h2>2. Finalidad</h2>
                <p>Los registros se utilizan para desarrollar, probar y demostrar funciones de autenticación, reservas, gestión administrativa y simulación de pagos de World Travel.</p>
            </div>

            <div class="wt-info__card">
                <h2>3. Almacenamiento y acceso</h2>
                <p>Los datos de la demostración se almacenan en la base de datos MySQL configurada para el proyecto. El sistema restringe determinadas pantallas según la autenticación y el rol del usuario.</p>
                <p>Estas medidas no sustituyen una auditoría de seguridad ni una evaluación legal previa a la puesta en producción.</p>
            </div>

            <div class="wt-info__card">
                <h2>4. Pagos y datos financieros</h2>
                <p>La pasarela actual es un simulador académico: no necesita números de tarjeta, códigos CVV ni contraseñas bancarias. No introduzcas información financiera real.</p>
            </div>

            <div class="wt-info__card">
                <h2>5. Sesiones y seguridad</h2>
                <p>La aplicación utiliza mecanismos de sesión y autenticación de Laravel. Para las pruebas deben usarse cuentas y datos ficticios, y nunca deben publicarse contraseñas ni credenciales del entorno en GitHub.</p>
            </div>

            <div class="wt-info__card">
                <h2>6. Consultas sobre tus datos</h2>
                <p>Para consultas relacionadas con la información utilizada durante las pruebas puedes acceder al Centro de Ayuda. Antes de operar comercialmente, World Travel deberá establecer responsables, plazos de conservación y procedimientos para atender los derechos de los titulares de datos.</p>
            </div>

            <div class="wt-info__card">
                <h2>7. Alcance de este documento</h2>
                <p>Este texto describe únicamente el proyecto académico actual. No constituye una política legal definitiva para la prestación comercial de servicios turísticos y deberá revisarse antes de su publicación en producción.</p>
            </div>

            <div class="wt-info__footer">
                <a href="{{ route('ayuda') }}" class="wt-info__button">Contactar al Centro de Ayuda</a>
            </div>
        </div>
    </section>
</x-layout>
