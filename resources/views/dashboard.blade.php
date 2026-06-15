@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Fila Superior: Mensaje de Bienvenida y Estadísticas Rápidas -->
    <section class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
        <!-- Banner de Bienvenida -->
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
            <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2.5 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 ring-1 ring-inset ring-indigo-500/20">
                Panel de control
            </span>
            <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 dark:text-white md:text-3xl">Planificación y Control de Operaciones</h1>
            <p class="mt-2.5 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                Herramientas para estimación de demanda mediante series temporales, análisis de márgenes y preferencia de productos, distribución de Pareto y optimización del Plan Agregado de Producción (PAP).
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('pronosticos') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500 shadow-md shadow-indigo-600/15">
                    Iniciar pronóstico
                </a>
                <a href="{{ route('pap') }}" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-white hover:bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white px-4 py-2 text-sm font-medium transition shadow-sm">
                    Simular Plan Agregado
                </a>
            </div>
        </div>

        <!-- Módulo Estadístico Rápido -->
        <div class="flex flex-col justify-between rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Módulos habilitados</p>
                <p class="mt-2 text-4xl font-bold tracking-tight text-slate-900 dark:text-white">4</p>
            </div>
            <div class="mt-4 border-t border-slate-100 dark:border-slate-800/60 pt-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Todos los módulos matemáticos de planificación operativa se encuentran listos para trabajar.
                </p>
            </div>
        </div>
    </section>

    <!-- Fila Inferior: Módulos Educativos y de Cálculo -->
    <section class="grid gap-6 sm:grid-cols-2">
        <!-- Pronósticos -->
        <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/20 shadow-sm transition-all hover:shadow-lg hover:border-indigo-500/30">
            <div class="h-40 overflow-hidden">
                <img src="{{ asset('images/img1.jpg') }}" alt="Pronósticos" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100">
            </div>
            <div class="flex flex-1 flex-col p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Pronósticos de Demanda</h2>
                    <span class="rounded-full bg-indigo-500/10 px-2 py-0.5 text-[10px] font-bold uppercase text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-500/20">Series Temporales</span>
                </div>
                <p class="mt-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Utiliza modelos matemáticos como Promedios Móviles y Suavizado Exponencial para predecir la demanda futura basándose en datos históricos. Fundamental para la planificación de inventarios.
                </p>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/60">
                    <a href="{{ route('pronosticos') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:gap-3 transition-all">
                        Ir al módulo de cálculo
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </article>

        <!-- Indicadores -->
        <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/20 shadow-sm transition-all hover:shadow-lg hover:border-indigo-500/30">
            <div class="h-40 overflow-hidden">
                <img src="{{ asset('images/indicePre.jpg') }}" alt="Indicadores" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100">
            </div>
            <div class="flex flex-1 flex-col p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Indicadores de Gestión</h2>
                    <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/20">Rentabilidad</span>
                </div>
                <p class="mt-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Analiza la utilidad neta y la rentabilidad sobre ventas por producto. Evalúa la salud financiera de tu catálogo y toma decisiones basadas en márgenes reales.
                </p>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/60">
                    <a href="{{ route('indicadores') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:gap-3 transition-all">
                        Analizar rentabilidad
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </article>

        <!-- Pareto -->
        <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/20 shadow-sm transition-all hover:shadow-lg hover:border-indigo-500/30">
            <div class="h-40 overflow-hidden">
                <img src="{{ asset('images/80-20.jpg') }}" alt="Pareto" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100">
            </div>
            <div class="flex flex-1 flex-col p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Diagrama de Pareto</h2>
                    <span class="rounded-full bg-orange-500/10 px-2 py-0.5 text-[10px] font-bold uppercase text-orange-600 dark:text-orange-400 ring-1 ring-orange-500/20">Ley 80/20</span>
                </div>
                <p class="mt-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Identifica el "Pocos Vitales": aquellos productos que representan el 80% de tus ingresos. Optimiza tu enfoque logístico y comercial en lo que realmente genera valor.
                </p>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/60">
                    <a href="{{ route('pareto') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:gap-3 transition-all">
                        Distribución de ventas
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </article>

        <!-- PAP -->
        <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/20 shadow-sm transition-all hover:shadow-lg hover:border-indigo-500/30">
            <div class="h-40 overflow-hidden">
                <img src="{{ asset('images/Plan-agregado.png') }}" alt="PAP" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100">
            </div>
            <div class="flex flex-1 flex-col p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Planeación Agregada (PAP)</h2>
                    <span class="rounded-full bg-rose-500/10 px-2 py-0.5 text-[10px] font-bold uppercase text-rose-600 dark:text-rose-400 ring-1 ring-rose-500/20">Optimización</span>
                </div>
                <p class="mt-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Simula diferentes estrategias de producción (fuerza de trabajo constante, inventario cero, subcontratación) para encontrar el equilibrio de costo mínimo en un horizonte de tiempo.
                </p>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/60">
                    <a href="{{ route('pap') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:gap-3 transition-all">
                        Simular estrategias
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection
