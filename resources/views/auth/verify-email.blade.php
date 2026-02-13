@extends('layouts.app')

@section('title', 'Verificar Email')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <i class="bi bi-envelope-check fs-1 mb-2 d-block"></i>
                    <h4 class="mb-0">Verificar Email</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <p class="mb-4">
                        Gracias por registrarse. Antes de comenzar, ¿podría verificar su dirección de correo electrónico haciendo clic en el enlace que le acabamos de enviar?
                    </p>

                    @if(session('status') == 'verification-link-sent')
                        <div class="alert alert-success">
                            Se ha enviado un nuevo enlace de verificación a su correo electrónico.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-send me-2"></i> Reenviar Enlace
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
