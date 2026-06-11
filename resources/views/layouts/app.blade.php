<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistemas Produccion' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.18),_transparent_40%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.18),_transparent_32%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)]"></div>
    <header class="border-b border-white/10 bg-slate-950/70 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-wide text-white">SistemasProduccion</a>
            @auth
                <nav class="flex flex-wrap items-center gap-3 text-sm text-slate-300">
                    <a href="{{ route('pronosticos') }}" class="rounded-full px-4 py-2 transition hover:bg-white/10 hover:text-white">Pronosticos</a>
                    <a href="{{ route('indicadores') }}" class="rounded-full px-4 py-2 transition hover:bg-white/10 hover:text-white">Indicadores</a>
                    <a href="{{ route('pareto') }}" class="rounded-full px-4 py-2 transition hover:bg-white/10 hover:text-white">Pareto</a>
                    <a href="{{ route('pap') }}" class="rounded-full px-4 py-2 transition hover:bg-white/10 hover:text-white">PAP</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-white/10 px-4 py-2 text-slate-300 transition hover:bg-white/10 hover:text-white">Salir</button>
                    </form>
                </nav>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        @yield('content')
    </main>
</body>
</html>
