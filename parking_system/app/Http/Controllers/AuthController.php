<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;


class AuthController extends Controller
{
    public function showRegister(Request $request)
    {
        session(['type' => $request->type]);
        return view('auth.register');
    }
    

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $type = session('type');

        if ($type === 'user') {
            $role = Role::where('name', 'user')->firstOrFail();
        } else {
            $role = Role::where('name', 'client')->firstOrFail();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        Auth::login($user);

        if ($role->name === 'user') {
            return redirect()->route('user.agent.create');
        }

        return redirect()->route('client.dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth::attempt($data)) {
            return back()->withErrors('email ou password incorrect');
        }

        $role = Auth::user()->role->name;

        $routes = [
            'admin' => 'admin.dashboard',
            'agent' => 'agent.dashboard',
            'client' => 'client.dashboard',
            'user' => 'user.agent.create',
        ];

        return redirect()->route($routes[$role] ?? 'login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('/');
    }
}
