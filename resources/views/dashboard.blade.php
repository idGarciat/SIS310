@extends('layouts.app')

@section('content')
<section class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
    <div class="rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-cyan-950/30 backdrop-blur">
        <span class="inline-flex rounded-full border border-cyan-400/30 bg-cyan-400/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-200">Sistema principal</span>
        <h1 class="mt-6 text-4xl font-semibold tracking-tight text-white md:text-6xl">Analisis de produccion con una interfaz mas limpia y moderna</h1>
        <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 md:text-lg">Este panel centraliza los modulos de pronosticos, indicadores de preferencia, diagrama de Pareto y plan agregado de produccion en una sola experiencia clara, rapida y preparada para seguir creciendo en Laravel.</p>
        <div class="mt-8 flex flex-wrap gap-3 text-sm">
            <a href="{{ route('pronosticos') }}" class="rounded-full bg-cyan-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">Abrir pronosticos</a>
            <a href="{{ route('indicadores') }}" class="rounded-full border border-white/15 bg-white/5 px-5 py-3 font-semibold text-white transition hover:bg-white/10">Ver indicadores</a>
        </div>
    </div>

    <div class="grid gap-4">
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Modulos</p>
            <p class="mt-2 text-3xl font-semibold text-white">4</p>
            <p class="mt-1 text-sm text-slate-400">Funcionalidades principales listas para trabajar.</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Estado</p>
            <p class="mt-2 text-3xl font-semibold text-white">Base publica</p>
            <p class="mt-1 text-sm text-slate-400">La autenticacion se deja para el final, como pediste.</p>
        </div>
    </div>
</section>

<section class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
    @foreach ([
        ['title' => 'Pronosticos', 'text' => 'Promedios moviles, suavizado exponencial y regresion lineal.'],
        ['title' => 'Indicadores', 'text' => 'Utilidad, rentabilidad e indice comercial por producto.'],
        ['title' => 'Pareto', 'text' => 'Ordena productos por ingreso y calcula acumulados.'],
        ['title' => 'PAP', 'text' => 'Plan agregado de produccion con costos y alternativas.'],
    ] as $card)
        <article class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-lg shadow-black/10 transition hover:-translate-y-1 hover:bg-white/10">
            <h2 class="text-xl font-semibold text-white">{{ $card['title'] }}</h2>
            <p class="mt-2 text-sm leading-6 text-slate-300">{{ $card['text'] }}</p>
        </article>
    @endforeach
</section>
@endsection
