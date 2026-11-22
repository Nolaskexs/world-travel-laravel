<x-layout>
    <link rel="stylesheet" href="{{ asset('css/informativas.css') }}">

    <section class="wt-info">
        <div class="wt-info__container">
            <div class="wt-info__heading">
                <h1>Preguntas frecuentes</h1>
                <p>Resuelve tus dudas sobre las reservas, los paquetes turísticos y los pagos de demostración de World Travel.</p>
            </div>

            <details class="wt-info__faq">
                <summary>¿Cómo puedo reservar un viaje?</summary>
                <p>Selecciona un paquete turístico, indica la fecha de viaje y el número de pasajeros, completa los datos solicitados y confirma la reserva. Después pasarás a la pantalla de pago.</p>
            </details>

            <details class="wt-info__faq">
                <summary>¿Necesito crear una cuenta para reservar?</summary>
                <p>Sí. Debes registrarte e iniciar sesión con una cuenta de cliente para guardar una reserva y acceder al proceso de pago.</p>
            </details>

            <details class="wt-info__faq">
                <summary>¿Qué métodos de pago puedo elegir?</summary>
                <p>En la versión académica de World Travel puedes simular operaciones con tarjeta, Yape, Plin o transferencia bancaria. Ninguna de estas opciones realiza cobros reales.</p>
            </details>

            <details class="wt-info__faq">
                <summary>¿Qué ocurre si se rechaza mi pago?</summary>
                <p>La operación se registra como rechazada, tu reserva sigue pendiente y puedes volver a intentar con otro método de pago simulado.</p>
            </details>

            <details class="wt-info__faq">
                <summary>¿Cómo obtengo mi comprobante?</summary>
                <p>Cuando la simulación de pago es aprobada, se genera una constancia de prueba que puedes imprimir o guardar como PDF. No constituye una boleta, una factura ni una prueba de pago bancario.</p>
            </details>

            <details class="wt-info__faq">
                <summary>¿Puedo cambiar o cancelar mi reserva?</summary>
                <p>La plataforma permite que la administración gestione los estados de las reservas durante las pruebas. Las políticas comerciales de cambios, devoluciones y cancelaciones todavía no han sido definidas para servicios reales.</p>
            </details>

            <details class="wt-info__faq">
                <summary>¿Se pueden consultar precios en soles o euros?</summary>
                <p>Los paquetes muestran actualmente su moneda configurada. La conversión entre USD, PEN y EUR es una función prevista para una etapa posterior de desarrollo.</p>
            </details>

            <details class="wt-info__faq">
                <summary>¿Dónde puedo registrar una queja o reclamo?</summary>
                <p>Puedes acceder al Libro de Reclamaciones desde el pie de página y completar el formulario disponible en World Travel.</p>
            </details>

            <div class="wt-info__footer">
                <p>¿No encontraste la respuesta que buscabas?</p>
                <a href="{{ route('ayuda') }}" class="wt-info__button">Ir al Centro de Ayuda</a>
            </div>
        </div>
    </section>
</x-layout>
