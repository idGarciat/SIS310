@extends('layouts.app')

@section('content')
<div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
    <!-- Formulario de Configuración -->
    <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
        <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2.5 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 ring-1 ring-inset ring-indigo-500/20">
            Pareto
        </span>
        <h1 class="mt-4 text-xl font-bold tracking-tight text-slate-900 dark:text-white">Análisis de Distribución</h1>
        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Identifica el 20% de los productos que genera el 80% de los ingresos. Separa las listas en el mismo orden con comas o saltos de línea.
        </p>

        <form method="POST" class="mt-6 space-y-4">
            @csrf
            @foreach ([
                ['name' => 'nombreProductos', 'label' => 'Nombres de Productos', 'placeholder' => 'Producto A, Producto B, Producto C'],
                ['name' => 'unidades', 'label' => 'Unidades Vendidas', 'placeholder' => '100, 80, 50'],
                ['name' => 'precios', 'label' => 'Precios Unitarios', 'placeholder' => '12.50, 15.00, 9.00'],
            ] as $field)
                <div>
                    <label for="{{ $field['name'] }}" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ $field['label'] }}</label>
                    <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="2" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-1.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" placeholder="{{ $field['placeholder'] }}" required>{{ $form[$field['name']] ?? '' }}</textarea>
                </div>
            @endforeach
            
            <div class="pt-2">
                <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500 active:scale-[0.98] shadow-md shadow-indigo-600/15">
                    Calcular Pareto
                </button>
            </div>
        </form>
    </section>

    <!-- Sección de Resultados -->
    <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm flex flex-col">
        <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Distribución de Pareto</h2>
        
        @if($result)
            <!-- Tabla de Análisis -->
            <div class="mt-4 flex-1 overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-850 text-left text-sm">
                    <thead class="bg-slate-100/80 dark:bg-slate-950/80 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3 font-medium">Producto</th>
                            <th class="px-4 py-3 font-medium text-right">Ingreso Bruto</th>
                            <th class="px-4 py-3 font-medium text-right">% Acumulado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-900 bg-slate-50/10 dark:bg-slate-900/10">
                        @foreach ($result['items'] as $item)
                            <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-900/35 transition-colors">
                                <td class="px-4 py-2.5 font-medium text-slate-900 dark:text-white">{{ $item['nombre'] }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-600 dark:text-slate-300">{{ number_format($item['ingreso'], 2) }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold {{ $item['porcentajeAcumulado'] <= 80 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400' }}">
                                    {{ number_format($item['porcentajeAcumulado'], 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Resumen Total -->
            <div class="mt-6">
                <div class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/60 p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Ingresos Totales Acumulados</p>
                    <p class="mt-1.5 text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($result['totales']['ingresos'], 2) }}
                    </p>
                </div>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-slate-800 rounded-lg p-6 mt-4 bg-slate-50/20 dark:bg-slate-900/10">
                <svg class="h-8 w-8 text-slate-400 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                </svg>
                <p class="text-xs text-slate-500 dark:text-slate-400 text-center max-w-[240px]">
                    Completa la información lateral para obtener la tabla ordenada de mayor a menor contribución económica.
                </p>
            </div>
        @endif
    </section>
</div>
@endsection
