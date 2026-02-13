@extends('layouts.app')

@section('title', 'Confirmar Contraseña')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-secondary text-white text-center py-4">
                    <i class="bi bi-shield-lock fs-1 mb-2 d-block"></i>
                    <h4 class="mb-0">Confirmar Contraseña</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        Esta es un área segura de la aplicación. Por favor, confirme su contraseña antes de continuar.
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Ingrese su contraseña"
                                       required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-secondary btn-lg">
                                <i class="bi bi-check-circle me-2"></i> Confirmar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
