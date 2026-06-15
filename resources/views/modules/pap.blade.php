@extends('layouts.app')

@section('content')
@php
    $papAlternatives = [
        1 => 'Inventario cero',
        2 => 'Fuerza de trabajo constante',
        3 => 'Fuerza de trabajo mínima con subcontratación',
        4 => 'Contratación y despido a demanda exacta',
        5 => 'Fuerza de trabajo constante con horas extra',
    ];
@endphp

<div class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <!-- Formulario de Entrada (Plan Agregado de Producción) -->
        <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
            <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2.5 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 ring-1 ring-inset ring-indigo-500/20">
                PAP
            </span>
            <h1 class="mt-4 text-xl font-bold tracking-tight text-slate-900 dark:text-white">Plan Agregado de Producción</h1>
            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Estima y compara las estrategias de planeación para determinar la alternativa de producción más económica.
            </p>

            <form method="POST" class="mt-6 space-y-5">
                @csrf
                
                <!-- Estrategia Alternativa -->
                <div>
                    <label for="alternativasPAP" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Estrategia Alternativa</label>
                    <select id="alternativasPAP" name="alternativasPAP" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected(($form['alternativasPAP'] ?? '') == $i)>Estrategia {{ $i }} - {{ $papAlternatives[$i] }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Series Correlativas -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3 border-b border-slate-100 dark:border-slate-800/60 pb-1.5">Series Temporales (Mismo Orden)</h3>
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach ([
                            ['name' => 'mesesTotal', 'label' => 'Periodos (Meses)', 'placeholder' => 'Ene, Feb, Mar'],
                            ['name' => 'demandas', 'label' => 'Demandas (Unidades)', 'placeholder' => '1200, 1400, 1600'],
                            ['name' => 'dias', 'label' => 'Días Laborables', 'placeholder' => '20, 22, 21'],
                        ] as $field)
                            <div>
                                <label for="{{ $field['name'] }}" class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">{{ $field['label'] }}</label>
                                <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="2" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-2.5 py-1.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" placeholder="{{ $field['placeholder'] }}" required>{{ $form[$field['name']] ?? '' }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Parámetros Operativos -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3 border-b border-slate-100 dark:border-slate-800/60 pb-1.5">Parámetros Operativos</h3>
                    <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-4">
                        @foreach ([
                            ['name' => 'trabIniciales', 'label' => 'Plantilla Inicial', 'placeholder' => 'Ej. 10'],
                            ['name' => 'horasUnidad', 'label' => 'Horas / Unidad', 'placeholder' => 'Ej. 4'],
                            ['name' => 'horasDia', 'label' => 'Horas / Día', 'placeholder' => 'Ej. 8'],
                            ['name' => 'unidadesProducidas', 'label' => 'Uds / Trabajador', 'placeholder' => 'Ej. 50'],
                        ] as $field)
                            <div>
                                <label for="{{ $field['name'] }}" class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">{{ $field['label'] }}</label>
                                <input type="number" step="any" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-2.5 py-1.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-750 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" value="{{ $form[$field['name']] ?? '' }}" placeholder="{{ $field['placeholder'] }}" required>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Estructura de Costos -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3 border-b border-slate-100 dark:border-slate-800/60 pb-1.5">Estructura de Costes (Por Unidad / Hora)</h3>
                    <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-4">
                        @foreach ([
                            ['name' => 'costoProduccion', 'label' => 'Costo Producción', 'placeholder' => 'Ej. 10'],
                            ['name' => 'costoContratacion', 'label' => 'Costo Contratación', 'placeholder' => 'Ej. 250'],
                            ['name' => 'costoDespido', 'label' => 'Costo Despido', 'placeholder' => 'Ej. 300'],
                            ['name' => 'costoHoraExtra', 'label' => 'Costo Hora Extra', 'placeholder' => 'Ej. 15'],
                            ['name' => 'costoSubcontratacion', 'label' => 'Costo Subcontrat.', 'placeholder' => 'Ej. 20'],
                            ['name' => 'costoAlmacenamiento', 'label' => 'Costo Almacén.', 'placeholder' => 'Ej. 2'],
                            ['name' => 'costoRotura', 'label' => 'Costo Escasez (Rotura)', 'placeholder' => 'Ej. 5'],
                            ['name' => 'costoMOhr', 'label' => 'Costo Mano Obra/Hr', 'placeholder' => 'Ej. 8'],
                        ] as $field)
                            <div>
                                <label for="{{ $field['name'] }}" class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">{{ $field['label'] }}</label>
                                <input type="number" step="any" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-2.5 py-1.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-750 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" value="{{ $form[$field['name']] ?? '' }}" placeholder="{{ $field['placeholder'] }}" required>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500 active:scale-[0.98] shadow-md shadow-indigo-600/15">
                        Calcular Plan Agregado
                    </button>
                </div>
            </form>
        </section>

        <!-- Resultados del Plan Agregado -->
        <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm flex flex-col">
            <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Costes del Plan</h2>

            @if($result)
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400">Alternativa:</span>
                    <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2 py-0.5 text-xs font-medium text-indigo-600 dark:text-indigo-300 ring-1 ring-inset ring-indigo-500/20">
                        {{ $papAlternatives[(int) $form['alternativasPAP']] ?? 'N/A' }}
                    </span>
                </div>

                <!-- Costo Total General (Destacado) -->
                @if(isset($result['totals']['Costo Total General']))
                    <div class="mt-5 rounded-lg border border-indigo-500/20 bg-indigo-500/5 p-4 shadow-sm">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Costo Total General</p>
                        <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                            ${{ number_format($result['totals']['Costo Total General'], 2) }}
                        </p>
                    </div>
                @endif

                <!-- Desglose de Costes Auxiliares -->
                <div class="mt-5">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Desglose de Costes Totales</h3>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($result['totals'] as $label => $value)
                            @if($label !== 'Costo Total General')
                                <div class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/60 p-3">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-450 dark:text-slate-500">
                                        {{ str_replace('Costo Total de ', 'C. ', str_replace('Mano de Obra', 'MO', str_replace('Subcontratación', 'Subcontr.', str_replace('Almacenamiento', 'Almacén', str_replace('Unidades Totales por trabajador', 'Uds/Trab. Totales', str_replace('Demanda Total', 'Demanda Acum.', $label)))))) }}
                                    </p>
                                    <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white tracking-tight">
                                        {{ is_numeric($value) ? number_format($value, 2) : $value }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-slate-800 rounded-lg p-6 mt-4 bg-slate-50/20 dark:bg-slate-900/10">
                    <svg class="h-8 w-8 text-slate-400 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75m0 .75v.75m0 .75v.75m0 .75V9m3.75-5.25v.75m0 .75v.75m0 .75v.75m0 .75V9m3.75-5.25v.75m0 .75v.75m0 .75v.75m0 .75V9m3.75-5.25v.75m0 .75v.75m0 .75v.75m0 .75V9m-11.25 9h16.5m-16.5 0a2.25 2.25 0 01-2.25-2.25V8.25A2.25 2.25 0 013.75 6h16.5a2.25 2.25 0 012.25 2.25v8.25a2.25 2.25 0 01-2.25 2.25" />
                    </svg>
                    <p class="text-xs text-slate-500 dark:text-slate-400 text-center max-w-[240px]">
                        Define los parámetros operativos y haz clic en "Calcular Plan Agregado" para estimar costes.
                    </p>
                </div>
            @endif
        </section>
    </div>

    <!-- Desglose Mensual (Fila Completa Inferior) -->
    @if($result)
        <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
            <h3 class="text-sm font-bold tracking-tight text-slate-900 dark:text-white mb-4">Desglose Operativo Mensual</h3>
            <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-850 text-left text-xs">
                    <thead class="bg-slate-100/80 dark:bg-slate-950/80 text-[10px] font-semibold uppercase tracking-wider text-slate-550 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3 font-medium">Periodo</th>
                            <th class="px-4 py-3 font-medium text-right">Demanda (Uds)</th>
                            <th class="px-4 py-3 font-medium text-right">Días</th>
                            <th class="px-4 py-3 font-medium text-right">Uds/Trab.</th>
                            <th class="px-4 py-3 font-medium text-right">Trabajadores</th>
                            <th class="px-4 py-3 font-medium text-right">Producción Real</th>
                            <th class="px-4 py-3 font-medium text-right">Contratados</th>
                            <th class="px-4 py-3 font-medium text-right">Despedidos</th>
                            <th class="px-4 py-3 font-medium text-right">Inventario Final</th>
                            <th class="px-4 py-3 font-medium text-right">Costo Operativo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-900 bg-slate-50/10 dark:bg-slate-900/10">
                        @foreach ($result['months'] as $index => $month)
                            <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-900/35 transition-colors">
                                <td class="px-4 py-2.5 font-semibold text-slate-900 dark:text-white">{{ $month }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-600 dark:text-slate-300">{{ number_format($result['demand'][$index] ?? 0, 0) }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-500 dark:text-slate-400">{{ $result['days'][$index] ?? 0 }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-500 dark:text-slate-400">{{ number_format($result['unitsPerWorker'][$index] ?? 0, 0) }}</td>
                                <td class="px-4 py-2.5 text-right font-medium text-slate-900 dark:text-white">{{ number_format($result['workers'][$index] ?? 0, 0) }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-600 dark:text-slate-300">{{ number_format($result['production'][$index] ?? 0, 0) }}</td>
                                <td class="px-4 py-2.5 text-right text-emerald-600 dark:text-emerald-400 font-medium">+{{ number_format($result['hired'][$index] ?? 0, 0) }}</td>
                                <td class="px-4 py-2.5 text-right text-rose-600 dark:text-rose-400 font-medium">-{{ number_format($result['fired'][$index] ?? 0, 0) }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold {{ ($result['inventory'][$index] ?? 0) < 0 ? 'text-red-600 dark:text-red-400' : (($result['inventory'][$index] ?? 0) > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-slate-500 dark:text-slate-400') }}">
                                    {{ number_format($result['inventory'][$index] ?? 0, 0) }}
                                </td>
                                <td class="px-4 py-2.5 text-right font-medium text-slate-900 dark:text-white">
                                    ${{ number_format(($result['productionUnitCosts'][$index] ?? 0) + ($result['laborCosts'][$index] ?? 0) + ($result['storageCosts'][$index] ?? 0) + ($result['shortageCosts'][$index] ?? 0) + ($result['overtimeCosts'][$index] ?? 0) + ($result['subcontractingCosts'][$index] ?? 0), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
</div>
@endsection
