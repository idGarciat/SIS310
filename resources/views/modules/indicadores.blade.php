@extends('layouts.app')

@section('content')
<div class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">
    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur">
        <p class="text-sm uppercase tracking-[0.2em] text-cyan-200">Indicadores</p>
        <h1 class="mt-3 text-3xl font-semibold text-white">Rentabilidad y preferencia por producto</h1>
        <p class="mt-3 text-sm leading-6 text-slate-300">Pega las listas en el mismo orden: nombres, ingresos, costos de produccion, costos fijos y costos administrativos.</p>

        <form method="POST" class="mt-8 space-y-5">
            @csrf
            @foreach ([
                ['name' => 'nombresProducto', 'label' => 'Nombres de producto', 'placeholder' => 'Producto A, Producto B, Producto C'],
                ['name' => 'ingresos', 'label' => 'Ingresos', 'placeholder' => '1500, 2300, 1800'],
                ['name' => 'costosProduccion', 'label' => 'Costos de produccion', 'placeholder' => '900, 1200, 1000'],
                ['name' => 'costosFijos', 'label' => 'Costos fijos', 'placeholder' => '200, 300, 250'],
                ['name' => 'costosAdministrativos', 'label' => 'Costos administrativos', 'placeholder' => '100, 150, 120'],
            ] as $field)
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">{{ $field['label'] }}</label>
                    <textarea name="{{ $field['name'] }}" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" placeholder="{{ $field['placeholder'] }}">{{ $form[$field['name']] ?? '' }}</textarea>
                </div>
            @endforeach
            <button class="rounded-full bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">Calcular indicadores</button>
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
                            <th class="px-4 py-3">Utilidad</th>
                            <th class="px-4 py-3">Rentabilidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/40">
                        @foreach ($result['items'] as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item['nombre'] }}</td>
                                <td class="px-4 py-3">{{ number_format($item['utilidad'], 2) }}</td>
                                <td class="px-4 py-3">{{ number_format($item['rentabilidadVentas'], 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($result['totales'] as $label => $value)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ str_replace('_', ' ', ucfirst($label)) }}</p>
                        <p class="mt-2 text-xl font-semibold text-white">{{ number_format($value, 2) }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="mt-4 text-sm leading-6 text-slate-300">Todavia no hay resultados calculados.</p>
        @endif
    </section>
</div>
@endsection
