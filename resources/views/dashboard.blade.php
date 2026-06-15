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

    <!-- Fila Inferior: Tarjetas de los Módulos -->
    <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            [
                'title' => 'Pronósticos', 
                'text' => 'Cálculo de medias móviles simples y ponderadas, suavizado exponencial y regresión lineal con estacionalidad.',
                'route' => 'pronosticos'
            ],
            [
                'title' => 'Indicadores', 
                'text' => 'Cálculo de utilidad neta, rentabilidad sobre ventas, contribución a la utilidad e índice comercial por producto.',
                'route' => 'indicadores'
            ],
            [
                'title' => 'Diagrama de Pareto', 
                'text' => 'Clasificación de productos por volumen de ventas y porcentaje acumulado para identificar el 80/20 productivo.',
                'route' => 'pareto'
            ],
            [
                'title' => 'Plan Agregado (PAP)', 
                'text' => 'Simulación de costes en 5 estrategias de planificación agregada (fuerza constante, inventario cero, subcontratación).',
                'route' => 'pap'
            ],
        ] as $card)
            <article class="flex flex-col justify-between rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/20 p-5 shadow-sm transition-all hover:border-slate-350 dark:hover:border-slate-700/80 hover:bg-slate-100/30 dark:hover:bg-slate-900/30">
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white tracking-tight">{{ $card['title'] }}</h2>
                    <p class="mt-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $card['text'] }}</p>
                </div>
                <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-900">
                    <a href="{{ route($card['route']) }}" class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors">
                        Acceder al módulo
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </article>
        @endforeach
    </section>
</div>
@endsection
