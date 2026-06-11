<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistemas de Producción' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Script inline para prevenir destellos blancos en carga rápida -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 antialiased selection:bg-indigo-500/30 selection:text-white transition-colors duration-150">
    <!-- Fondo adaptable con cuadrícula sutil y máscara radial -->
    <div class="fixed inset-0 -z-10 bg-[linear-gradient(to_right,#0f172a05_1px,transparent_1px),linear-gradient(to_bottom,#0f172a05_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#1e293b05_1px,transparent_1px),linear-gradient(to_bottom,#1e293b05_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
    <div class="fixed inset-0 -z-10 bg-slate-50 dark:bg-slate-950"></div>

    <header class="border-b border-slate-200 dark:border-slate-900 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md sticky top-0 z-50">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 text-slate-900 dark:text-white transition hover:opacity-90">
                <!-- Icono geométrico -->
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm shadow-indigo-500/10">
                    <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.656 48.656 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3M4.5 12l3 3m-3-3l-3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-base font-semibold tracking-tight">Sistemas de Producción</span>
            </a>
            @auth
                <nav class="flex items-center gap-1 text-sm">
                    <a href="{{ route('pronosticos') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('pronosticos') ? 'text-slate-900 bg-slate-100 border-slate-200/80 dark:text-white dark:bg-slate-900 dark:border-slate-800' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/50 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-900/50' }} border border-transparent">Pronósticos</a>
                    <a href="{{ route('indicadores') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('indicadores') ? 'text-slate-900 bg-slate-100 border-slate-200/80 dark:text-white dark:bg-slate-900 dark:border-slate-800' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/50 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-900/50' }} border border-transparent">Indicadores</a>
                    <a href="{{ route('pareto') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('pareto') ? 'text-slate-900 bg-slate-100 border-slate-200/80 dark:text-white dark:bg-slate-900 dark:border-slate-800' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/50 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-900/50' }} border border-transparent">Pareto</a>
                    <a href="{{ route('pap') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('pap') ? 'text-slate-900 bg-slate-100 border-slate-200/80 dark:text-white dark:bg-slate-900 dark:border-slate-800' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/50 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-900/50' }} border border-transparent">PAP</a>
                    
                    <div class="h-4 w-px bg-slate-200 dark:bg-slate-800 mx-2"></div>

                    <!-- Botón Selector de Tema -->
                    <button onclick="toggleTheme()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all border border-transparent" aria-label="Cambiar tema">
                        <!-- Moon Icon (se muestra en modo claro) -->
                        <svg class="h-4.5 w-4.5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <!-- Sun Icon (se muestra en modo oscuro) -->
                        <svg class="h-4.5 w-4.5 hidden dark:block text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>

                    <div class="h-4 w-px bg-slate-200 dark:bg-slate-800 mx-2"></div>

                    <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-sm font-medium text-slate-500 hover:text-red-600 hover:bg-red-500/10 dark:text-slate-400 dark:hover:text-red-400 dark:hover:bg-red-500/10 border border-transparent transition-all">Salir</button>
                    </form>
                </nav>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-8">
        @yield('content')
    </main>

    <!-- Script de toggle de tema -->
    <script>
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
</body>
</html>
