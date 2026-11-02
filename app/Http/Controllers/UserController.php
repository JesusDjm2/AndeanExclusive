<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->with('roles')->orderBy('id')->get();

        return view('auth.users.index', compact('users'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function create()
    {
        return view('auth.register', [
            'formAction' => route('users.store'),
            'formHeading' => 'Nuevo usuario del panel',
            'submitLabel' => 'Crear usuario',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', \Illuminate\Validation\Rule::in(array_keys(\App\Support\Permisos::AREAS))],
        ]);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        $user->syncRoles([$request->input('role')]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuario nuevo creado exitosamente.');
    }

    public function edit($id)
    {
        $user = User::query()->with('roles')->findOrFail($id);

        return view('auth.users.edit')->with('user', $user);
    }

    public function update(Request $request, $id)
    {
        $user = User::query()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => ['required', \Illuminate\Validation\Rule::in(array_keys(\App\Support\Permisos::AREAS))],
        ]);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->save();
        $rolAnterior = $user->roles->first()?->name;
        $user->syncRoles([$request->input('role')]);
        if ($rolAnterior !== $request->input('role')) {
            \App\Support\Bitacora::anotar('cambió el rol de', $user, [['campo' => 'Área', 'de' => $rolAnterior, 'a' => $request->input('role')]]);
        }

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $user = User::query()->findOrFail($id);
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuario borrado exitosamente.');
    }
}
