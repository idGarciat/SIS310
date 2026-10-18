<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function userHistory(User $user)
    {
        $calculations = Calculation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('history', compact('calculations', 'user'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'user_id.required' => 'Debe seleccionar un usuario.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->password = $validated['password'];
        $user->save();

        return back()->with('success', "Contraseña de {$user->name} actualizada correctamente.");
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes quitarte los permisos de admin a ti mismo.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return back()->with('success', 'Permisos actualizados.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }
}
