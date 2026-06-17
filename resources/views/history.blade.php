@extends('layouts.app')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 dark:border-emerald-800/30 bg-emerald-50/50 dark:bg-emerald-500/10 p-4 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif
    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
            Historial de Cálculos {{ isset($user) ? ' - ' . $user->name : '' }}
        </h1>
        <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
            {{ isset($user) ? 'Revisando las simulaciones guardadas de este usuario.' : 'Revisa tus análisis previos y recupera la información de simulaciones anteriores.' }}
        </p>
        @if(isset($user))
            <div class="mt-4">
                <a href="{{ route('admin.users') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                    &larr; Volver a la lista de usuarios
                </a>
            </div>
        @endif
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 shadow-sm backdrop-blur-sm">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-sm">
            <thead class="bg-slate-50/50 dark:bg-slate-950/60 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-6 py-4">Módulo</th>
                    <th class="px-6 py-4">Fecha</th>
                    <th class="px-6 py-4">Resumen de entrada</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white/50 dark:bg-transparent">
                @forelse($calculations as $calc)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{
                                $calc->module === 'pap' ? 'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-500/10 dark:text-rose-400' :
                                ($calc->module === 'pareto' ? 'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-500/10 dark:text-orange-400' :
                                ($calc->module === 'pronosticos' ? 'bg-indigo-50 text-indigo-700 ring-indigo-600/20 dark:bg-indigo-500/10 dark:text-indigo-400' :
                                'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400'))
                            }} uppercase">
                                {{ $calc->module }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            {{ $calc->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                            <div class="max-w-xs truncate text-[11px] opacity-75">
                                @if($calc->module === 'pronosticos')
                                    Método: {{ $calc->input_data['metodo'] ?? 'N/A' }} | Datos: {{ Str::limit($calc->input_data['demanda'] ?? '', 30) }}
                                @elseif($calc->module === 'pareto')
                                    Productos: {{ Str::limit($calc->input_data['nombreProductos'] ?? '', 30) }}
                                @else
                                    Simulación técnica almacenada.
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route($calc->module, ['recall' => $calc->id]) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20 transition-all">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Cargar datos
                                </a>
                                <form action="{{ route('calculations.destroy', $calc) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20 transition-all">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            No se han encontrado cálculos registrados en tu historial.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $calculations->links() }}
    </div>
</div>
@endsection
