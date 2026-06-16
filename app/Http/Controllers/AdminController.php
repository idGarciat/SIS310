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
