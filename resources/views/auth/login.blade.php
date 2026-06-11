<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.18),_transparent_40%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.18),_transparent_32%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)]"></div>
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
        <section class="w-full max-w-md rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur">
            <p class="text-sm uppercase tracking-[0.2em] text-cyan-200">Acceso</p>
            <h1 class="mt-3 text-3xl font-semibold text-white">Iniciar sesion</h1>
            <p class="mt-3 text-sm leading-6 text-slate-300">Usa tu correo y contraseña para entrar al sistema.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-red-400/30 bg-red-500/10 p-4 text-sm text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.authenticate') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">Password</label>
                    <input type="password" name="password" required class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400">
                </div>
                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="remember" class="rounded border-white/20 bg-slate-900 text-cyan-400 focus:ring-cyan-400">
                    Recordarme
                </label>
                <button class="w-full rounded-full bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">Entrar</button>
            </form>
        </section>
    </main>
</body>
</html>