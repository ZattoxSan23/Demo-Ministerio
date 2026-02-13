<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * EmailVerificationPromptController - Controlador de verificación de email
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Muestra el prompt de verificación de email
     */
    public function __invoke(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard', absolute: false))
            : view('auth.verify-email');
    }
}
