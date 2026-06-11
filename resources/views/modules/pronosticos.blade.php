@extends('layouts.app')

@section('content')
<div class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">
    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur">
        <p class="text-sm uppercase tracking-[0.2em] text-cyan-200">Pronosticos</p>
        <h1 class="mt-3 text-3xl font-semibold text-white">Calcula tendencias con datos historicos</h1>
        <p class="mt-3 text-sm leading-6 text-slate-300">Ingresa la serie separada por comas o saltos de linea y elige el metodo a aplicar. La pagina devuelve una vista inmediata con los valores calculados.</p>

        <form method="POST" class="mt-8 space-y-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-200">Metodo</label>
                <select name="metodo" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none ring-0 focus:border-cyan-400" required>
                    <option value="promediomovilsimple" @selected(($form['metodo'] ?? '') === 'promediomovilsimple')>Promedio movil simple</option>
                    <option value="promediomovilponderado" @selected(($form['metodo'] ?? '') === 'promediomovilponderado')>Promedio movil ponderado</option>
                    <option value="regresionlineal" @selected(($form['metodo'] ?? '') === 'regresionlineal')>Regresion lineal</option>
                    <option value="suavisadoexponencialsimple" @selected(($form['metodo'] ?? '') === 'suavisadoexponencialsimple')>Suavizado exponencial simple</option>
                    <option value="suavisadoexponencialdoble" @selected(($form['metodo'] ?? '') === 'suavisadoexponencialdoble')>Suavizado exponencial doble</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-200">Demanda</label>
                <textarea name="demanda" rows="6" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none ring-0 focus:border-cyan-400" placeholder="120, 135, 128, 141">{{ $form['demanda'] ?? '' }}</textarea>
                <p class="mt-2 text-xs text-slate-400">Separa los valores con comas, espacios o saltos de linea.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">Número de períodos (n)</label>
                    <input type="number" name="n" min="2" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" value="{{ $form['n'] ?? '' }}">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">Alcance</label>
                    <input type="number" name="alcance" min="0" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" value="{{ $form['alcance'] ?? '' }}">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">Alpha</label>
                    <input type="number" step="0.01" min="0" max="1" name="alpha" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" value="{{ $form['alpha'] ?? '' }}">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">Beta</label>
                    <input type="number" step="0.01" min="0" max="1" name="beta" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" value="{{ $form['beta'] ?? '' }}">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-200">Pesos</label>
                    <input type="text" name="pesos" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" value="{{ $form['pesos'] ?? '' }}" placeholder="0.5, 0.3, 0.2">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-200">Periodo estacional</label>
                    <input type="number" name="proporcion" min="1" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-cyan-400" value="{{ $form['proporcion'] ?? '' }}">
                </div>
            </div>

            <button class="rounded-full bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">Calcular</button>
        </form>
    </section>

    <section class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-8 shadow-xl shadow-black/20">
        <h2 class="text-2xl font-semibold text-white">Resultado</h2>
        @if($result)
            <p class="mt-2 text-sm text-slate-300">Metodo aplicado: <span class="font-semibold text-cyan-200">{{ $result['method'] }}</span></p>

            @if(!empty($result['summary']))
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach (['mad' => 'MAD', 'mse' => 'MSE', 'mape' => 'MAPE'] as $key => $label)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $label }}</p>
                            <p class="mt-2 text-xl font-semibold text-white">{{ is_null($result['summary'][$key] ?? null) ? 'N/A' : number_format($result['summary'][$key], 2) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-6 overflow-hidden rounded-2xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm text-slate-200">
                    <thead class="bg-white/5 text-slate-300">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/40">
                        @foreach (($result['series']['forecast'] ?? $result['series'] ?? []) as $index => $value)
                            <tr>
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ is_null($value) ? 'N/A' : number_format((float) $value, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="mt-4 text-sm leading-6 text-slate-300">Aun no has ejecutado un calculo. Completa el formulario para ver la serie y los indicadores de error.</p>
        @endif
    </section>
</div>
@endsection
