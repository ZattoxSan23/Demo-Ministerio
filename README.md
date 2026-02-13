# Sistema de Gestión de Documentos y Trámites Básico (Demo MPFN)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5.3">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

<p align="center">
  <strong>Sistema web de demostración para postulación CAS - Analista Programador</strong><br>
  Simulación de funcionalidades típicas del Ministerio Público del Perú
</p>

---

## 📋 Descripción del Proyecto

Este sistema es una **demostración técnica** desarrollada para una postulación laboral CAS en la Oficina de Sistemas del Ministerio Público. Implementa funcionalidades típicas de gestión documental incluyendo:

- **Mesa de Partes Virtual**: Registro de documentos y trámites
- **Control de Acceso por Roles**: Admin, Fiscal, Asistente y Ciudadano
- **Gestión de Documentos**: CRUD completo con upload de PDFs
- **Reportes**: Generación de PDFs con Dompdf
- **Dashboard**: Estadísticas con Chart.js
- **Seguridad**: Validaciones, middleware de roles, CSRF protection

---

## 🚀 Tecnologías Utilizadas

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **Laravel** | 12.x | Framework PHP Backend |
| **PHP** | 8.2+ | Lenguaje de programación |
| **Bootstrap** | 5.3 | Framework CSS Frontend |
| **MySQL/MariaDB** | 8.0+ | Base de datos |
| **Laravel Breeze** | 2.x | Autenticación |
| **Spatie Permission** | 6.x | Control de roles y permisos |
| **Dompdf** | 3.x | Generación de PDFs |
| **Chart.js** | 4.x | Gráficos estadísticos |

---

## 📦 Instalación Local

### Requisitos Previos

- PHP 8.2 o superior
- Composer 2.x
- MySQL 8.0+ o MariaDB 10.6+
- Node.js 18+ y NPM (opcional, para assets)
- Extensiones PHP: `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`, `gd`

### Pasos de Instalación

```bash
# 1. Clonar o descargar el proyecto
cd sistema-gestion-documentos-mpfn

# 2. Instalar dependencias de Composer
composer install

# 3. Crear archivo de configuración
cp .env.example .env

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Configurar base de datos en .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sistema_gestion_mpfn
# DB_USERNAME=root
# DB_PASSWORD=tu_password

# 6. Crear base de datos en MySQL
mysql -u root -p -e "CREATE DATABASE sistema_gestion_mpfn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Ejecutar migraciones y seeders
php artisan migrate --seed

# 8. Crear enlace simbólico para storage
php artisan storage:link

# 9. Iniciar servidor de desarrollo
php artisan serve
```

### Acceso al Sistema

Una vez instalado, accede a `http://localhost:8000` y utiliza las siguientes credenciales:

| Rol | Email | Contraseña |
|-----|-------|------------|
| **Admin** | admin@demo.com | 12345678 |
| **Fiscal** | fiscal1@demo.com | 12345678 |
| **Asistente** | asistente1@demo.com | 12345678 |
| **Ciudadano** | ciudadano1@demo.com | 12345678 |

---

## 🏗️ Estructura del Proyecto

```
sistema-gestion-documentos-mpfn/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Controladores de autenticación
│   │   │   ├── DocumentoController.php
│   │   │   └── HomeController.php
│   │   ├── Middleware/
│   │   │   └── CheckRole.php   # Middleware personalizado
│   │   └── Requests/
│   │       ├── StoreDocumentoRequest.php
│   │       └── UpdateDocumentoRequest.php
│   ├── Models/
│   │   ├── User.php            # Modelo con HasRoles
│   │   └── Documento.php       # Modelo principal
│   └── Providers/
│       ├── AppServiceProvider.php
│       ├── AuthServiceProvider.php
│       └── RouteServiceProvider.php
├── config/
│   ├── app.php
│   ├── permission.php          # Config Spatie
│   └── dompdf.php              # Config Dompdf
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_02_10_000001_create_documentos_table.php
│   │   └── 2026_02_10_000002_create_permission_tables.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolesAndPermissionsSeeder.php
│       ├── UserSeeder.php
│       └── DocumentoSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php   # Layout principal con Bootstrap
│       ├── auth/               # Vistas de autenticación
│       ├── documentos/         # CRUD de documentos
│       ├── pdf/                # Plantillas PDF
│       └── dashboard.blade.php
├── routes/
│   ├── web.php                 # Rutas principales
│   ├── auth.php                # Rutas de autenticación
│   └── api.php
├── storage/
│   └── app/public/documentos/  # Archivos PDF subidos
├── .env.example
├── composer.json
└── README.md
```

---

## 👥 Roles y Permisos

### Matriz de Permisos

| Permiso | Admin | Fiscal | Asistente | Ciudadano |
|---------|:-----:|:------:|:---------:|:---------:|
| Ver todos los documentos | ✅ | ✅ | ✅ | ❌ |
| Ver documentos propios | ✅ | ✅ | ✅ | ✅ |
| Crear documentos | ✅ | ✅ | ✅ | ✅ |
| Editar todos los documentos | ✅ | ✅ | ❌ | ❌ |
| Editar documentos propios | ✅ | ✅ | ✅ | ✅* |
| Eliminar documentos | ✅ | ❌ | ❌ | ❌** |
| Asignar documentos | ✅ | ❌ | ❌ | ❌ |
| Cambiar estado | ✅ | ✅ | ✅ | ❌ |
| Generar reportes PDF | ✅ | ✅ | ❌ | ❌ |

\* Solo documentos pendientes o en proceso  
\*\* Solo documentos propios en estado pendiente

---

## 📊 Funcionalidades Principales

### 1. Dashboard
- Cards con contadores por estado
- Gráficos Chart.js (Pie, Bar, Line)
- Últimos documentos registrados
- Documentos por prioridad

### 2. Gestión de Documentos
- Listado paginado con filtros
- Búsqueda por título/descripción/expediente
- Filtros por estado, tipo, fecha
- CRUD completo con validaciones
- Upload seguro de PDFs (máx. 5MB)

### 3. Reportes PDF
- Generación con Dompdf
- Header institucional
- Tabla de documentos filtrados
- Resumen por estado

### 4. Seguridad
- Autenticación con Laravel Breeze
- Middleware de roles (Spatie)
- Validación de Form Requests
- CSRF protection
- File upload con validación MIME
- Soft deletes

---

## 🔐 Buenas Prácticas Implementadas

### Seguridad
- ✅ Password hashing con bcrypt
- ✅ Prepared statements (Eloquent ORM)
- ✅ Validación estricta de inputs
- ✅ Middleware de autorización
- ✅ Protección CSRF en todos los formularios
- ✅ Validación de archivos (tipo, tamaño)
- ✅ Sanitización de datos

### Código Limpio
- ✅ Principio SOLID
- ✅ MVC estricto
- ✅ Repositorio de patrones
- ✅ Comentarios en español
- ✅ Nombres descriptivos
- ✅ Form Requests para validación
- ✅ Policies para autorización

### Frontend
- ✅ Bootstrap 5.3 responsive
- ✅ Mobile-first design
- ✅ Componentes reutilizables
- ✅ Chart.js para gráficos
- ✅ Iconos Bootstrap

---

## 🌐 Despliegue en Hosting Compartido

### Requisitos del Hosting
- PHP 8.2+
- MySQL 8.0+
- Extensiones PHP: mbstring, openssl, pdo_mysql, fileinfo, gd
- Acceso SSH (recomendado) o FTP
- Composer disponible

### Pasos de Despliegue

```bash
# 1. Subir archivos al servidor (vía FTP o Git)
# 2. Instalar dependencias
composer install --no-dev --optimize-autoloader

# 3. Configurar .env con datos del hosting
# DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
# APP_URL=https://tudominio.com
# APP_ENV=production
# APP_DEBUG=false

# 4. Ejecutar migraciones
php artisan migrate --force

# 5. Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Crear enlace simbólico (si el hosting lo permite)
php artisan storage:link
# O crear manualmente: public/storage -> ../storage/app/public
```

### Configuración .htaccess (Apache)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## 🧪 Testing

```bash
# Ejecutar tests
php artisan test

# Con cobertura
php artisan test --coverage
```

---

## 📸 Capturas de Pantalla

### Login
Pantalla de inicio de sesión con credenciales de prueba visibles.

### Dashboard
Vista principal con estadísticas, gráficos y últimos documentos.

### Listado de Documentos
Tabla paginada con filtros de búsqueda y botón de exportar PDF.

### Crear Documento
Formulario completo con validaciones y upload de archivos.

### Ver Documento
Detalle completo con acciones rápidas y descarga de PDF.

---

## 📝 Notas para el Evaluador

Este proyecto fue desarrollado como **demostración técnica** para una postulación CAS. Incluye:

1. **Arquitectura MVC** estricta con Laravel 12
2. **Control de acceso** basado en roles (RBAC) con Spatie
3. **CRUD completo** con validaciones server-side
4. **Reportes PDF** con Dompdf
5. **Gráficos dinámicos** con Chart.js
6. **Responsive design** con Bootstrap 5.3
7. **Código documentado** en español
8. **Buenas prácticas** de seguridad

El sistema está listo para ejecutarse localmente y puede adaptarse fácilmente a requerimientos específicos.

---

## 👨‍💻 Desarrollador

**Postulación:** SANTOS MEJIA VASQUEZ
**Tecnologías:** Laravel 12, PHP 8.2, Bootstrap 5.3, MySQL  
**Fecha:** Febrero 2026

---

## 📄 Licencia

Este proyecto es de uso exclusivo para fines de demostración en proceso de selección CAS. No está autorizado para uso comercial o distribución sin permiso expreso.

---

<p align="center">
  <strong>Desarrollado con ❤️ para el Ministerio Público del Perú</strong>
</p>
