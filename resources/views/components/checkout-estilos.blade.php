<style>
    .wt-checkout {
        min-height: 85vh;
        background: #f3f6f9;
        padding: 38px 20px 90px;
        color: #102331;
        font-family: 'Inter', Arial, sans-serif;
    }

    .wt-checkout * {
        box-sizing: border-box;
    }

    .checkout-container {
        max-width: 1120px;
        margin: 0 auto;
    }

    .checkout-volver {
        color: #236487;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
    }

    .checkout-heading {
        margin: 35px 0 28px;
    }

    .checkout-eyebrow {
        color: #287096;
        letter-spacing: 2px;
        font-size: 11px;
        font-weight: 800;
    }

    .checkout-heading h1 {
        font-size: clamp(28px, 4vw, 40px);
        margin: 10px 0;
        color: #0b2535;
    }

    .checkout-heading p {
        color: #718190;
        font-size: 15px;
    }

    .checkout-pasos {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        color: #72818e;
        font-size: 13px;
        font-weight: 600;
    }

    .paso-listo {
        color: #287096;
    }

    .paso-actual {
        color: #0b2535;
    }

    .paso-linea {
        width: 42px;
        height: 1px;
        background: #cbd6df;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        align-items: start;
        gap: 25px;
    }

    .checkout-card {
        background: #fff;
        border: 1px solid #e4eaf0;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 10px 28px rgba(20, 40, 60, .04);
    }

    .checkout-card-header {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 27px;
    }

    .checkout-numero {
        display: grid;
        place-items: center;
        min-width: 39px;
        height: 39px;
        border-radius: 11px;
        color: #236487;
        background: #eaf3f8;
        font-weight: 800;
    }

    .checkout-card h2 {
        margin: 0 0 6px;
        font-size: 21px;
    }

    .checkout-card-header p {
        margin: 0;
        font-size: 13px;
        color: #778694;
    }

    .checkout-campo {
        margin-bottom: 21px;
    }

    .checkout-campo label {
        display: block;
        font-weight: 600;
        margin-bottom: 9px;
        font-size: 13px;
    }

    .opcional {
        color: #82919d;
        font-weight: 400;
    }

    .checkout-campo input,
    .checkout-campo textarea,
    .checkout-campo select {
        width: 100%;
        padding: 13px;
        border: 1px solid #dbe3eb;
        border-radius: 9px;
        background: #fff;
        color: #102331;
        font: inherit;
    }

    .checkout-campo input:focus,
    .checkout-campo textarea:focus,
    .checkout-campo select:focus {
        outline: none;
        border-color: #287096;
        box-shadow: 0 0 0 3px rgba(40, 112, 150, .15);
    }

    .checkout-fila {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 0;
        border-bottom: 1px dashed #e4eaf0;
        font-size: 14px;
    }

    .checkout-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-top: 2px solid #0b2535;
        margin-top: 14px;
    }

    .checkout-total strong {
        font-size: 24px;
        color: #0b2535;
    }

    .checkout-submit {
        width: 100%;
        background: #102331;
        color: #fff;
        border: none;
        padding: 16px;
        font-size: 15px;
        font-weight: 700;
        border-radius: 9px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background .2s;
    }

    .checkout-submit:hover {
        background: #1b384d;
    }

    .checkout-imagen {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 14px;
        background: #e2e8f0;
    }

    .checkout-servicio {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        padding: 12px 14px;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 13px;
        line-height: 1.5;
    }

    .checkout-servicio strong {
        display: block;
        font-size: 14px;
    }

    .checkout-detalle p {
        color: #6c7c8c;
        font-size: 13px;
        margin: 0 0 20px;
    }

    .checkout-detalle h3 {
        margin: 0 0 6px;
        font-size: 19px;
        color: #0b2535;
        font-weight: 700;
    }

    .checkout-aclaracion {
        margin-top: 14px;
        font-size: 12px;
        color: #8898a6;
        line-height: 1.5;
    }

    .checkout-enlace {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
        margin: 8px 0;
    }

    .checkout-alerta--error {
        background: #fee2e2;
        border: 1px solid #f87171;
        color: #991b1b;
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 24px;
    }

    .checkout-alerta--error ul {
        margin: 8px 0 0 18px;
    }

    .checkout-nota {
        background: #eef8fc;
        border-color: #bce2f4;
        color: #155577;
        padding: 14px;
        border-radius: 8px;
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 20px;
        border: 1px solid #bce2f4;
    }

    .checkout-exito {
        max-width: 720px;
        margin: 0 auto;
        text-align: center;
    }

    .checkout-exito__icono {
        width: 72px;
        height: 72px;
        margin: 0 auto 20px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: #dcfce7;
        color: #166534;
    }

    .checkout-exito__icono svg {
        width: 38px;
        height: 38px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2.5;
    }

    .checkout-exito h1 {
        font-size: clamp(26px, 4vw, 36px);
        color: #0b2535;
        margin: 0 0 10px;
    }

    .checkout-exito__col {
        text-align: left;
        margin-top: 26px;
    }

    .checkout-exito__acciones {
        display: flex;
        justify-content: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 26px;
    }

    .checkout-exito__acciones a {
        padding: 13px 22px;
        border-radius: 9px;
        font-weight: 700;
        text-decoration: none;
    }

    .checkout-exito__acciones a:first-child {
        background: #102331;
        color: #fff;
    }

    .checkout-exito__acciones a:last-child {
        background: #eaf3f8;
        color: #236487;
    }

    @media (max-width: 800px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }

        .checkout-card {
            padding: 23px;
        }
    }
</style>
