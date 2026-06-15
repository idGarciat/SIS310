@extends('layouts.app')

@section('content')
<div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
    <!-- Formulario de Configuración -->
    <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
        <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2.5 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 ring-1 ring-inset ring-indigo-500/20">
            Pronósticos
        </span>
        <h1 class="mt-4 text-xl font-bold tracking-tight text-slate-900 dark:text-white">Configuración del Modelo</h1>
        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Ingresa la serie de demanda histórica y define los parámetros del modelo matemático para proyectar la demanda futura.
        </p>

        <form method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="metodo" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Método de Pronóstico</label>
                <select id="metodo" name="metodo" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required>
                    <option value="promediomovilsimple" @selected(($form['metodo'] ?? '') === 'promediomovilsimple')>Promedio móvil simple</option>
                    <option value="promediomovilponderado" @selected(($form['metodo'] ?? '') === 'promediomovilponderado')>Promedio móvil ponderado</option>
                    <option value="regresionlineal" @selected(($form['metodo'] ?? '') === 'regresionlineal')>Regresión lineal con estacionalidad</option>
                    <option value="suavisadoexponencialsimple" @selected(($form['metodo'] ?? '') === 'suavisadoexponencialsimple')>Suavizado exponencial simple</option>
                    <option value="suavisadoexponencialdoble" @selected(($form['metodo'] ?? '') === 'suavisadoexponencialdoble')>Suavizado exponencial doble</option>
                </select>
            </div>

            <div>
                <label for="demanda" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Demanda Histórica</label>
                <textarea id="demanda" name="demanda" rows="4" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" placeholder="120, 135, 128, 141, 150" required>{{ $form['demanda'] ?? '' }}</textarea>
                <p class="mt-1.5 text-[11px] text-slate-500">Separa los valores numéricos mediante comas, espacios o saltos de línea.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="n" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Períodos (n)</label>
                    <input type="number" id="n" name="n" min="2" placeholder="Ej. 3" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-750 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" value="{{ $form['n'] ?? '' }}">
                </div>
                <div>
                    <label for="alcance" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Alcance (Horizonte)</label>
                    <input type="number" id="alcance" name="alcance" min="0" placeholder="Ej. 2" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-750 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" value="{{ $form['alcance'] ?? '' }}">
                </div>
                <div>
                    <label for="alpha" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Constante Alpha (α)</label>
                    <input type="number" id="alpha" step="0.01" min="0" max="1" placeholder="Ej. 0.3" name="alpha" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-750 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" value="{{ $form['alpha'] ?? '' }}">
                </div>
                <div>
                    <label for="beta" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Constante Beta (β)</label>
                    <input type="number" id="beta" step="0.01" min="0" max="1" placeholder="Ej. 0.2" name="beta" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-750 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" value="{{ $form['beta'] ?? '' }}">
                </div>
                <div class="sm:col-span-2">
                    <label for="pesos" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Pesos Ponderados</label>
                    <input type="text" id="pesos" name="pesos" placeholder="Ej. 0.5, 0.3, 0.2" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" value="{{ $form['pesos'] ?? '' }}">
                    <p class="mt-1 text-[10px] text-slate-500">Requerido solo para promedio móvil ponderado (deben sumar 1.0).</p>
                </div>
                <div class="sm:col-span-2">
                    <label for="proporcion" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Período Estacional (L)</label>
                    <input type="number" id="proporcion" name="proporcion" min="1" placeholder="Ej. 4 (trimestral), 12 (mensual)" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-750 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" value="{{ $form['proporcion'] ?? '' }}">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500 active:scale-[0.98] shadow-md shadow-indigo-600/15">
                    Ejecutar cálculo
                </button>
            </div>
        </form>
    </section>

    <!-- Sección de Resultados -->
    <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm flex flex-col">
        <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Análisis de Resultados</h2>
        
        @if($result)
            <div class="mt-3 flex items-center gap-2">
                <span class="text-xs text-slate-500 dark:text-slate-400">Método aplicado:</span>
                <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2 py-0.5 text-xs font-medium text-indigo-600 dark:text-indigo-300 ring-1 ring-inset ring-indigo-500/20">
                    {{ $result['method'] }}
                </span>
            </div>

            <!-- Resumen de Métricas de Error -->
            @if(!empty($result['summary']))
                <div class="mt-6 grid gap-4 grid-cols-3">
                    @foreach (['mad' => 'MAD', 'mse' => 'MSE', 'mape' => 'MAPE'] as $key => $label)
                        <div class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/60 p-3.5">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $label }}</p>
                            <p class="mt-1.5 text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ is_null($result['summary'][$key] ?? null) ? 'N/A' : number_format($result['summary'][$key], 2) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Gráfico de Pronóstico -->
            <div class="mt-6 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/40 p-4 min-h-[350px]">
                <div id="forecastChart"></div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const isDark = document.documentElement.classList.contains('dark');
                    const demand = @json($demandas ?? []);
                    const forecast = @json($result['series']['forecast'] ?? $result['series'] ?? []);
                    
                    // Asegurar que las series tengan la misma longitud para el eje X
                    const maxLen = Math.max(demand.length, forecast.length);
                    const labels = Array.from({length: maxLen}, (_, i) => `P${i + 1}`);

                    const options = {
                        series: [{
                            name: 'Demanda Real',
                            data: demand
                        }, {
                            name: 'Pronóstico',
                            data: forecast
                        }],
                        chart: {
                            height: 350,
                            type: 'line',
                            toolbar: { show: false },
                            background: 'transparent',
                            foreColor: isDark ? '#94a3b8' : '#64748b',
                            dropShadow: {
                                enabled: true,
                                color: '#000',
                                top: 18,
                                left: 7,
                                blur: 10,
                                opacity: 0.2
                            }
                        },
                        colors: ['#6366f1', '#f43f5e'],
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        markers: {
                            size: 4
                        },
                        xaxis: {
                            categories: labels,
                            title: { text: 'Período' }
                        },
                        yaxis: {
                            title: { text: 'Unidades' },
                            labels: {
                                formatter: (val) => val.toFixed(2)
                            }
                        },
                        grid: {
                            borderColor: isDark ? '#1e293b' : '#e2e8f0',
                            strokeDashArray: 4
                        },
                        theme: {
                            mode: isDark ? 'dark' : 'light'
                        },
                        tooltip: {
                            theme: isDark ? 'dark' : 'light',
                            y: {
                                formatter: (val) => val.toFixed(2) + ' Uds'
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#forecastChart"), options);
                    chart.render();
                });
            </script>

            <!-- Tabla de Serie Temporal Calculada -->
            <div class="mt-6 flex-1 overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-850 text-left text-sm">
                    <thead class="bg-slate-100/80 dark:bg-slate-950/80 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3 font-medium">Período</th>
                            <th class="px-4 py-3 font-medium text-right">Demanda Real</th>
                            <th class="px-4 py-3 font-medium text-right">Pronóstico</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-900 bg-slate-50/10 dark:bg-slate-900/10">
                        @php
                            $demandValues = $demandas ?? [];
                            $forecastValues = $result['series']['forecast'] ?? $result['series'] ?? [];
                            $maxRows = max(count($demandValues), count($forecastValues));
                        @endphp
                        @for ($i = 0; $i < $maxRows; $i++)
                            <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-900/35 transition-colors">
                                <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">P{{ $i + 1 }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-600 dark:text-slate-300">
                                    {{ isset($demandValues[$i]) ? number_format($demandValues[$i], 2) : '-' }}
                                </td>
                                <td class="px-4 py-2.5 text-right font-medium text-slate-900 dark:text-white">
                                    {{ isset($forecastValues[$i]) && !is_null($forecastValues[$i]) ? number_format((float) $forecastValues[$i], 2) : '-' }}
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-slate-800 rounded-lg p-6 mt-4 bg-slate-50/20 dark:bg-slate-900/10">
                <svg class="h-8 w-8 text-slate-400 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v5.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 013 18.375v-5.25zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125v-9.75zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v14.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <p class="text-xs text-slate-500 dark:text-slate-400 text-center max-w-[240px]">
                    Completa el formulario lateral y presiona "Ejecutar cálculo" para procesar la serie temporal.
                </p>
            </div>
        @endif
    </section>
</div>
@endsection
