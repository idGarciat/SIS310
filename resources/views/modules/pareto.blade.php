@extends('layouts.app')

@section('content')
<div class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">
    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur">
        <p class="text-sm uppercase tracking-[0.2em] text-cyan-200">Pareto</p>
        <h1 class="mt-3 text-3xl font-semibold text-white">Clasifica por ingreso y acumula el impacto</h1>
        <p class="mt-3 text-sm leading-6 text-slate-300">Pega las listas en el mismo orden: nombres, unidades vendidas y precios unitarios.</p>

        <form method="POST" class="mt-8 space-y-5">
            @csrf
            @foreach ([
                ['name' => 'nombreProductos', 'label' => 'Nombres de productos', 'placeholder' => 'Producto A, Producto B, Producto C'],
                ['name' => 'unidades', 'label' => 'Unidades vendidas', 'placeholder' => '10, 8, 5'],
                ['name' => 'precios', 'label' => 'Precios', 'placeholder' => '120, 150, 90'],
            ] as $field)
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">{{ $field['label'] }}</label>
                    <textarea name="{{ $field['name'] }}" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" placeholder="{{ $field['placeholder'] }}">{{ $form[$field['name']] ?? '' }}</textarea>
                </div>
            @endforeach
            <button class="rounded-full bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">Calcular Pareto</button>
        </form>
    </section>

    <section class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-8 shadow-xl shadow-black/20">
        <h2 class="text-2xl font-semibold text-white">Resultado</h2>
        @if($result)
            <div class="mt-6 overflow-hidden rounded-2xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm text-slate-200">
                    <thead class="bg-white/5 text-slate-300">
                        <tr>
                            <th class="px-4 py-3">Producto</th>
                            <th class="px-4 py-3">Ingreso</th>
                            <th class="px-4 py-3">% acumulado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/40">
                        @foreach ($result['items'] as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item['nombre'] }}</td>
                                <td class="px-4 py-3">{{ number_format($item['ingreso'], 2) }}</td>
                                <td class="px-4 py-3">{{ number_format($item['porcentajeAcumulado'], 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Ingresos totales</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ number_format($result['totales']['ingresos'], 2) }}</p>
            </div>
        @else
            <p class="mt-4 text-sm leading-6 text-slate-300">Aun no hay calculo realizado.</p>
        @endif
    </section>
</div>
@endsection
