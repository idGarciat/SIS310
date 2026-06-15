<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sistemas de Producción</title>
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

    <!-- Botón flotante selector de tema en la esquina superior derecha -->
    <div class="absolute top-6 right-6">
        <button onclick="toggleTheme()" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950" aria-label="Cambiar tema">
            <!-- Moon Icon (se muestra en modo claro) -->
            <svg class="h-4.5 w-4.5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <!-- Sun Icon (se muestra en modo oscuro) -->
            <svg class="h-4.5 w-4.5 hidden dark:block text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </button>
    </div>

    <main class="flex min-h-screen flex-col items-center justify-center p-6">
        <div class="w-full max-w-[340px]">
            <!-- Encabezado de la Marca -->
            <div class="flex flex-col items-center mb-6">
                <!-- Icono geométrico minimalista -->
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-md shadow-indigo-500/10">
                    <svg class="h-5.5 w-5.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.656 48.656 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3M4.5 12l3 3m-3-3l-3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-xl font-semibold text-slate-900 dark:text-white tracking-tight">Sistemas de Producción</h1>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 text-center">Accede a las herramientas de control y pronósticos</p>
            </div>

            <!-- Tarjeta de Login -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/40 backdrop-blur-sm">
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 p-3 text-xs text-red-700 dark:text-red-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.authenticate') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus 
                            placeholder="nombre@correo.com"
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Contraseña</label>
                        </div>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="rounded border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-white dark:focus:ring-offset-slate-900">
                            Recordar sesión
                        </label>
                    </div>
                    <button class="w-full rounded-lg bg-indigo-600 py-2 text-sm font-medium text-white transition-all hover:bg-indigo-500 active:scale-[0.98] shadow-md shadow-indigo-600/15">
                        Iniciar sesión
                    </button>
                </form>
            </div>
        </div>
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