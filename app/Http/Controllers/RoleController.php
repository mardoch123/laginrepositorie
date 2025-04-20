<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('roles.index', compact('users'));
    }

    public function manageUsers()
    {
        $users = User::all();
        return view('roles.manage-users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,farm_manager,user',
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.manage')->with('success', 'Rôle mis à jour avec succès');
    }
}