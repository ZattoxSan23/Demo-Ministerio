@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <i class="bi bi-shield-check fs-1 mb-2 d-block"></i>
                    <h4 class="mb-0">{{ config('app.name', 'MPFN Demo') }}</h4>
                    <small>Sistema de Gestión de Documentos</small>
                </div>
                <div class="card-body p-4">
                    <h5 class="text-center mb-4">Iniciar Sesión</h5>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="usuario@ejemplo.com"
                                       required 
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="••••••••"
                                       required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    ¿Olvidó su contraseña?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0">¿No tiene cuenta? 
                        <a href="{{ route('register') }}" class="text-decoration-none">Regístrese aquí</a>
                    </p>
                </div>
            </div>

            <!-- Credenciales de prueba -->
            <div class="alert alert-info mt-3">
                <small>
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Credenciales de prueba:</strong><br>
                    Admin: admin@demo.com / 12345678<br>
                    Fiscal: fiscal1@demo.com / 12345678<br>
                    Ciudadano: ciudadano1@demo.com / 12345678
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
