@extends('layouts.app')

@section('content')
@php
    $papAlternatives = [
        1 => 'Inventario 0',
        2 => 'Fuerza de trabajo constante',
        3 => 'Fuerza de trabajo mínima y subcontratación',
        4 => 'Contratar y despedir de acuerdo a la demanda mensual exacta',
        5 => 'Fuerza de trabajo constante con horas extra',
    ];
@endphp
<div class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">
    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur">
        <p class="text-sm uppercase tracking-[0.2em] text-cyan-200">PAP</p>
        <h1 class="mt-3 text-3xl font-semibold text-white">Plan agregado de produccion</h1>
        <p class="mt-3 text-sm leading-6 text-slate-300">Usa listas separadas por comas para los periodos, demandas y dias. Los costos se ingresan como un solo valor por campo.</p>

        <form method="POST" class="mt-8 space-y-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-200">Alternativa</label>
                <select name="alternativasPAP" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400">
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(($form['alternativasPAP'] ?? '') == $i)>Alternativa {{ $i }} ({{ $papAlternatives[$i] }})</option>
                    @endfor
                </select>
            </div>

            @foreach ([
                ['name' => 'mesesTotal', 'label' => 'Meses', 'placeholder' => 'Enero, Febrero, Marzo'],
                ['name' => 'demandas', 'label' => 'Demandas', 'placeholder' => '120, 140, 160'],
                ['name' => 'dias', 'label' => 'Dias por periodo', 'placeholder' => '20, 22, 21'],
            ] as $field)
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">{{ $field['label'] }}</label>
                    <textarea name="{{ $field['name'] }}" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" placeholder="{{ $field['placeholder'] }}">{{ $form[$field['name']] ?? '' }}</textarea>
                </div>
            @endforeach

            @foreach ([
                ['name' => 'trabIniciales', 'label' => 'Trabajadores iniciales'],
                ['name' => 'horasUnidad', 'label' => 'Horas por unidad'],
                ['name' => 'horasDia', 'label' => 'Horas trabajadas al dia'],
                ['name' => 'unidadesProducidas', 'label' => 'Unidades producidas por trabajador'],
                ['name' => 'costoProduccion', 'label' => 'Costo produccion'],
                ['name' => 'costoContratacion', 'label' => 'Costo contratacion'],
                ['name' => 'costoDespido', 'label' => 'Costo despido'],
                ['name' => 'costoHoraExtra', 'label' => 'Costo hora extra'],
                ['name' => 'costoSubcontratacion', 'label' => 'Costo subcontratacion'],
                ['name' => 'costoAlmacenamiento', 'label' => 'Costo almacenamiento'],
                ['name' => 'costoRotura', 'label' => 'Costo rotura'],
                ['name' => 'costoMOhr', 'label' => 'Costo mano de obra por hora'],
            ] as $field)
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">{{ $field['label'] }}</label>
                    <input type="number" name="{{ $field['name'] }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" value="{{ $form[$field['name']] ?? '' }}">
                </div>
            @endforeach

            <button class="rounded-full bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">Calcular PAP</button>
        </form>
    </section>

    <section class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-8 shadow-xl shadow-black/20">
        <h2 class="text-2xl font-semibold text-white">Resultado</h2>
        @if($result)
            @if(isset($form['alternativasPAP']))
                <p class="mt-2 text-sm text-slate-300">Alternativa seleccionada: <span class="font-semibold text-cyan-200">Alternativa {{ $form['alternativasPAP'] }} ({{ $papAlternatives[(int) $form['alternativasPAP']] ?? 'N/A' }})</span></p>
            @endif
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($result['totals'] as $label => $value)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ str_replace('_', ' ', ucfirst($label)) }}</p>
                        <p class="mt-2 text-lg font-semibold text-white">{{ is_numeric($value) ? number_format($value, 2) : $value }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 overflow-hidden rounded-2xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm text-slate-200">
                    <thead class="bg-white/5 text-slate-300">
                        <tr>
                            <th class="px-4 py-3">Periodo</th>
                            <th class="px-4 py-3">Demanda</th>
                            <th class="px-4 py-3">Trabajadores</th>
                            <th class="px-4 py-3">Costo total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/40">
                        @foreach ($result['months'] as $index => $month)
                            <tr>
                                <td class="px-4 py-3">{{ $month }}</td>
                                <td class="px-4 py-3">{{ number_format($result['demand'][$index] ?? 0, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format($result['workers'][$index] ?? 0, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format(($result['productionUnitCosts'][$index] ?? 0) + ($result['laborCosts'][$index] ?? 0) + ($result['storageCosts'][$index] ?? 0) + ($result['shortageCosts'][$index] ?? 0) + ($result['overtimeCosts'][$index] ?? 0) + ($result['subcontractingCosts'][$index] ?? 0), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="mt-4 text-sm leading-6 text-slate-300">Todavia no hay una corrida del plan.</p>
        @endif
    </section>
</div>
@endsection
