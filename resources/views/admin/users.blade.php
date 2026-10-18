@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm dark:shadow-md dark:shadow-black/20 backdrop-blur-sm">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Administración de Usuarios</h1>
        <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
            Gestiona los permisos y accesos de los usuarios registrados en el sistema.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-700 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-700 dark:text-red-400">
            <ul class="list-inside list-disc space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 shadow-sm backdrop-blur-sm">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-sm">
            <thead class="bg-slate-50/50 dark:bg-slate-950/60 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-6 py-4">Usuario</th>
                    <th class="px-6 py-4">Correo</th>
                    <th class="px-6 py-4">Contraseña</th>
                    <th class="px-6 py-4">Rol</th>
                    <th class="px-6 py-4">Registro</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white/50 dark:bg-transparent">
                @foreach($users as $user)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-mono tracking-widest text-slate-400 dark:text-slate-500 select-none" title="La contraseña está cifrada y no puede mostrarse">••••••••</span>
                                <button type="button" onclick="openPasswordModal({{ $user->id }}, @js($user->name))" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 px-2.5 py-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition-all border border-slate-200 dark:border-slate-700 shadow-sm" title="Cambiar contraseña">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                    Cambiar
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->is_admin)
                                <span class="inline-flex items-center rounded-md bg-indigo-50 dark:bg-indigo-500/10 px-2 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-400 ring-1 ring-inset ring-indigo-700/10">Administrador</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-slate-50 dark:bg-slate-500/10 px-2 py-1 text-xs font-medium text-slate-600 dark:text-slate-400 ring-1 ring-inset ring-slate-500/10">Usuario</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                            <!-- Historial -->
                            <a href="{{ route('admin.users.history', $user) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 px-3 py-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition-all border border-slate-200 dark:border-slate-700 shadow-sm" title="Ver historial">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Historial
                            </a>

                            <!-- Toggle Admin -->
                            <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-bold transition-all border shadow-sm {{ $user->is_admin ? 'bg-orange-50 text-orange-700 border-orange-200 hover:bg-orange-100 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20 dark:hover:bg-orange-500/20' : 'bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 dark:hover:bg-indigo-500/20' }}">
                                    {{ $user->is_admin ? 'Quitar Admin' : 'Hacer Admin' }}
                                </button>
                            </form>

                            <!-- Delete -->
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20 dark:hover:bg-red-500/20 px-3 py-1.5 text-xs font-bold transition-all shadow-sm">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal para cambiar contraseña -->
<div id="passwordModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
    <div class="w-full sm:w-1/2 max-w-2xl rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-xl">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Cambiar contraseña</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    Usuario: <span id="passwordModalUser" class="font-semibold text-slate-700 dark:text-slate-300"></span>
                </p>
            </div>
            <button type="button" onclick="closePasswordModal()" class="rounded-lg p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:text-slate-200 dark:hover:bg-slate-800 transition-all" aria-label="Cerrar">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="passwordForm" method="POST" action="{{ route('admin.users.update-password') }}" class="mt-5 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="passwordUserId" name="user_id" value="{{ old('user_id') }}">
            <input type="hidden" id="passwordUserName" name="user_name" value="{{ old('user_name') }}">

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Nueva contraseña</label>
                <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password"
                    class="mt-1.5 w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password"
                    class="mt-1.5 w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="closePasswordModal()" class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 transition hover:bg-slate-50 dark:hover:bg-slate-800">
                    Cancelar
                </button>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-500">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPasswordModal(id, name) {
        const modal = document.getElementById('passwordModal');
        const idField = document.getElementById('passwordUserId');
        const nameField = document.getElementById('passwordUserName');

        if (id !== undefined) {
            idField.value = id;
            nameField.value = name;
        }

        document.getElementById('passwordModalUser').textContent = nameField.value;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

@if($errors->has('password'))
    <script>
        document.addEventListener('DOMContentLoaded', () => openPasswordModal());
    </script>
@endif
@endsection
