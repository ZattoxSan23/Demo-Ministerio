<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistema de Gestión') - {{ config('app.name', 'MPFN Demo') }}</title>

    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --mpfn-primary: #1a5276;
            --mpfn-secondary: #2e86ab;
            --mpfn-success: #27ae60;
            --mpfn-warning: #f39c12;
            --mpfn-danger: #c0392b;
            --mpfn-info: #3498db;
            --mpfn-light: #ecf0f1;
            --mpfn-dark: #2c3e50;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-mpfn {
            background: linear-gradient(135deg, var(--mpfn-primary) 0%, var(--mpfn-secondary) 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 0.375rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--mpfn-primary);
            border-color: var(--mpfn-primary);
        }

        .btn-primary:hover {
            background-color: #154360;
            border-color: #154360;
        }

        .table {
            vertical-align: middle;
        }

        .table thead th {
            background-color: var(--mpfn-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--mpfn-primary);
            box-shadow: 0 0 0 0.2rem rgba(26, 82, 118, 0.25);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
        }

        .dropdown-menu {
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.pendientes::before { background-color: var(--mpfn-warning); }
        .stat-card.proceso::before { background-color: var(--mpfn-info); }
        .stat-card.resueltos::before { background-color: var(--mpfn-success); }
        .stat-card.archivados::before { background-color: var(--mpfn-secondary); }
        .stat-card.total::before { background-color: var(--mpfn-primary); }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .footer-mpfn {
            background-color: var(--mpfn-dark);
            color: #fff;
            padding: 1rem 0;
            margin-top: auto;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stat-number {
                font-size: 1.75rem;
            }
            
            .navbar-brand {
                font-size: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Navbar Principal -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-mpfn">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-shield-check me-2"></i>
                    {{ config('app.name', 'MPFN Demo') }}
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    @auth
                    <!-- Menú izquierdo -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documentos.*') ? 'active' : '' }}" 
                               href="{{ route('documentos.index') }}">
                                <i class="bi bi-folder me-1"></i> Documentos
                            </a>
                        </li>

                        @can('crear-documentos')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documentos.create') ? 'active' : '' }}" 
                               href="{{ route('documentos.create') }}">
                                <i class="bi bi-plus-circle me-1"></i> Nuevo
                            </a>
                        </li>
                        @endcan

                        @role('Admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear me-1"></i> Administración
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-people me-2"></i> Usuarios
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-graph-up me-2"></i> Reportes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-database me-2"></i> Configuración
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endrole
                    </ul>

                    <!-- Menú derecho - Usuario -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                               role="button" data-bs-toggle="dropdown">
                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 32px; height: 32px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                                <span class="badge bg-{{ Auth::user()->rolBadgeColor() }} ms-2 d-none d-lg-inline">
                                    {{ Auth::user()->rolPrincipal() }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="dropdown-header">
                                    <strong>{{ Auth::user()->name }}</strong><br>
                                    <small class="text-muted">{{ Auth::user()->email }}</small><br>
                                    <span class="badge bg-{{ Auth::user()->rolBadgeColor() }}">
                                        {{ Auth::user()->rolPrincipal() }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i> Mi Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('documentos.index') }}?estado=Pendiente">
                                        <i class="bi bi-folder me-2"></i> Mis Documentos
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @else
                    <!-- Menú para invitados -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Registrarse
                            </a>
                        </li>
                    </ul>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main class="content-wrapper py-4">
            <div class="container">
                <!-- Mensajes de alerta -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Errores de validación -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Contenido de la página -->
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer-mpfn text-center">
            <div class="container">
                <small>
                    <i class="bi bi-shield-check me-1"></i>
                    Sistema de Gestión de Documentos y Trámites - Demo MPFN
                </small><br>
                <small class="text-muted">
                    © {{ date('Y') }} - Desarrollado para demostración de postulación CAS
                </small>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Scripts globales -->
    <script>
        // Auto-cerrar alertas después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Confirmación para eliminaciones
        function confirmarEliminacion(event, mensaje = '¿Está seguro de eliminar este registro?') {
            if (!confirm(mensaje)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Mostrar nombre de archivo seleccionado
        document.addEventListener('DOMContentLoaded', function() {
            var fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    var label = this.nextElementSibling;
                    if (label && label.classList.contains('file-label')) {
                        label.textContent = this.files[0]?.name || 'Seleccionar archivo';
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
