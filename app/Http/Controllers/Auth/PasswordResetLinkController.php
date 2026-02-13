<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * PasswordResetLinkController - Controlador de recuperación de contraseña
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Muestra el formulario para solicitar reset de contraseña
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el link de reset de contraseña
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
