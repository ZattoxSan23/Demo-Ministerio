<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * RegisteredUserController - Controlador de Registro de Usuarios
 * 
 * Gestiona el registro de nuevos usuarios en el sistema,
 * incluyendo la asignación de roles.
 */
class RegisteredUserController extends Controller
{
    /**
     * Muestra el formulario de registro
     */
    public function create(): View
    {
        // Roles disponibles para registro público
        $roles = [
            'Ciudadano' => 'Ciudadano - Consultar mis trámites',
        ];

        return view('auth.register', compact('roles'));
    }

    /**
     * Procesa el registro de un nuevo usuario
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dni' => ['nullable', 'string', 'size:8', 'unique:users'],
            'telefono' => ['nullable', 'string', 'max:15'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dni' => $request->dni,
            'telefono' => $request->telefono,
            'activo' => true,
        ]);

        // Asignar rol de Ciudadano por defecto
        $user->assignRole('Ciudadano');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
