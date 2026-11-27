<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') - World Travel</title>

        <style>
            html { line-height: 1.15; -webkit-text-size-adjust: 100%; }
            body {
                margin: 0;
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                background-color: #ffffff;
                color: #0f172a;
            }
            .min-h-screen { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem 1rem; }
            .error-box { max-width: 32rem; width: 100%; text-align: center; }
            .error-main { display: inline-flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 1.5rem; }
            .error-code { font-size: 1.5rem; font-weight: 700; color: #0f172a; border-right: 1px solid #cbd5e1; padding-right: 1rem; }
            .error-msg { font-size: 1.05rem; font-weight: 500; color: #475569; }
            .error-btn { display: inline-block; padding: 10px 22px; background: #0c1b26; color: #ffffff; border-radius: 9px; font-size: 14px; font-weight: 600; text-decoration: none; transition: background 0.2s; }
            .error-btn:hover { background: #162e40; }
        </style>
    </head>
    <body>
        <main class="min-h-screen">
            <div class="error-box">
                <div class="error-main">
                    <h1 class="error-code">@yield('code')</h1>
                    <div class="error-msg">@yield('message')</div>
                </div>
                <div>
                    <a href="{{ url('/') }}" class="error-btn">Volver al inicio</a>
                </div>
            </div>
        </main>
    </body>
</html>
