@extends('layouts.app')

@section('content')
<div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
    <!-- Formulario de Configuración -->
    <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
        <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2.5 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 ring-1 ring-inset ring-indigo-500/20">
            Indicadores
        </span>
        <h1 class="mt-4 text-xl font-bold tracking-tight text-slate-900 dark:text-white">Análisis de Rentabilidad</h1>
        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Ingresa las listas en el mismo orden correlativo. Separa los valores con comas o saltos de línea.
        </p>

        <form method="POST" class="mt-6 space-y-4">
            @csrf
            @foreach ([
                ['name' => 'nombresProducto', 'label' => 'Nombres de Productos', 'placeholder' => 'Producto A, Producto B, Producto C'],
                ['name' => 'ingresos', 'label' => 'Ingresos Totales', 'placeholder' => '1500, 2300, 1800'],
                ['name' => 'costosProduccion', 'label' => 'Costos de Producción (Mano de Obra + Materiales)', 'placeholder' => '900, 1200, 1000'],
                ['name' => 'costosFijos', 'label' => 'Costos Fijos Asignados', 'placeholder' => '200, 300, 250'],
                ['name' => 'costosAdministrativos', 'label' => 'Costos Administrativos', 'placeholder' => '100, 150, 120'],
            ] as $field)
                <div>
                    <label for="{{ $field['name'] }}" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ $field['label'] }}</label>
                    <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="2" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-1.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" placeholder="{{ $field['placeholder'] }}" required>{{ $form[$field['name']] ?? '' }}</textarea>
                </div>
            @endforeach
            
            <div class="pt-2">
                <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500 active:scale-[0.98] shadow-md shadow-indigo-600/15">
                    Calcular indicadores
                </button>
            </div>
        </form>
    </section>

    <!-- Sección de Resultados -->
    <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm flex flex-col">
        <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Resultados Financieros</h2>
        
        @if($result)
            <!-- Gráfico de Indicadores -->
            <div class="mt-6 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/40 p-4 min-h-[350px]">
                <div id="indicatorsChart"></div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const isDark = document.documentElement.classList.contains('dark');
                    const items = @json($result['items']);

                    const options = {
                        series: [{
                            name: 'Utilidad Neta',
                            type: 'column',
                            data: items.map(i => i.utilidad)
                        }, {
                            name: 'Rentabilidad (%)',
                            type: 'line',
                            data: items.map(i => i.rentabilidadVentas)
                        }],
                        chart: {
                            height: 350,
                            type: 'line',
                            toolbar: { show: false },
                            background: 'transparent',
                            foreColor: isDark ? '#94a3b8' : '#64748b'
                        },
                        colors: ['#6366f1', '#10b981'],
                        stroke: {
                            width: [0, 4],
                            curve: 'smooth'
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: '50%'
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        labels: items.map(i => i.nombre),
                        yaxis: [{
                            title: { text: 'Utilidad ($)' },
                            labels: {
                                formatter: (val) => val.toFixed(2)
                            }
                        }, {
                            opposite: true,
                            title: { text: 'Rentabilidad (%)' },
                            labels: {
                                formatter: (val) => val.toFixed(2) + '%'
                            }
                        }],
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
                                formatter: function (val, { seriesIndex }) {
                                    return seriesIndex === 0 ? '$ ' + val.toFixed(2) : val.toFixed(2) + ' %';
                                }
                            }
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#indicatorsChart"), options);
                    chart.render();
                });
            </script>

            <!-- Tabla de Márgenes por Producto -->
            <div class="mt-6 flex-1 overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-850 text-left text-sm">
                    <thead class="bg-slate-100/80 dark:bg-slate-950/80 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3 font-medium">Producto</th>
                            <th class="px-4 py-3 font-medium text-right">Utilidad Neta</th>
                            <th class="px-4 py-3 font-medium text-right">Rentabilidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-900 bg-slate-50/10 dark:bg-slate-900/10">
                        @foreach ($result['items'] as $item)
                            <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-900/35 transition-colors">
                                <td class="px-4 py-2.5 font-medium text-slate-900 dark:text-white">{{ $item['nombre'] }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-600 dark:text-slate-300">{{ number_format($item['utilidad'], 2) }}</td>
                                <td class="px-4 py-2.5 text-right text-indigo-600 dark:text-indigo-400 font-semibold">
                                    {{ number_format($item['rentabilidadVentas'], 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Resumen de Totales Consolidados -->
            <div class="mt-6">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-550 dark:text-slate-500 mb-3">Totales Consolidados</h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ($result['totales'] as $label => $value)
                        <div class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/60 p-3.5">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                {{ str_replace('costos', 'C.', str_replace('Produccion', 'Prod.', str_replace('_', ' ', ucfirst($label)))) }}
                            </p>
                            <p class="mt-1.5 text-base font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ number_format($value, 2) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-slate-800 rounded-lg p-6 mt-4 bg-slate-50/20 dark:bg-slate-900/10">
                <svg class="h-8 w-8 text-slate-400 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-1.971-.659-1.171-.879-1.171-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0L15 8.818M12 3v18" />
                </svg>
                <p class="text-xs text-slate-550 dark:text-slate-400 text-center max-w-[240px]">
                    Completa el formulario lateral e ingresa los datos de costos/ingresos para evaluar la rentabilidad.
                </p>
            </div>
        @endif
    </section>
</div>
@endsection
