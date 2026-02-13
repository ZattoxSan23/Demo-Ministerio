#!/bin/bash

# =============================================================================
# Script de Instalación - Sistema de Gestión de Documentos MPFN
# =============================================================================
# Este script automatiza la instalación del sistema en entornos locales
# =============================================================================

set -e

echo "=========================================="
echo "  Sistema de Gestión de Documentos MPFN"
echo "  Script de Instalación"
echo "=========================================="
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para imprimir mensajes
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[OK]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[ADVERTENCIA]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Verificar PHP
print_status "Verificando PHP..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
    print_success "PHP encontrado: versión $PHP_VERSION"
else
    print_error "PHP no está instalado. Por favor instale PHP 8.2 o superior."
    exit 1
fi

# Verificar Composer
print_status "Verificando Composer..."
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version | cut -d " " -f 3)
    print_success "Composer encontrado: versión $COMPOSER_VERSION"
else
    print_error "Composer no está instalado. Por favor instale Composer."
    exit 1
fi

# Verificar MySQL
print_status "Verificando MySQL/MariaDB..."
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version | awk '{print $3}' | sed 's/,//g')
    print_success "MySQL encontrado: versión $MYSQL_VERSION"
else
    print_warning "MySQL no está instalado o no está en el PATH."
fi

echo ""
print_status "Iniciando instalación..."
echo ""

# Instalar dependencias de Composer
print_status "Instalando dependencias de Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader
print_success "Dependencias instaladas correctamente"

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    print_status "Creando archivo .env..."
    cp .env.example .env
    print_success "Archivo .env creado"
else
    print_warning "El archivo .env ya existe, se mantendrá la configuración actual"
fi

# Generar clave de aplicación
print_status "Generando clave de aplicación..."
php artisan key:generate --force
print_success "Clave generada"

# Configuración de base de datos
echo ""
print_status "Configuración de Base de Datos"
echo "----------------------------------------"
echo "Por defecto se usará:"
echo "  - Host: 127.0.0.1"
echo "  - Puerto: 3306"
echo "  - Base de datos: sistema_gestion_mpfn"
echo "  - Usuario: root"
echo ""

read -p "¿Desea modificar estos valores? (s/N): " MODIFY_DB

if [[ $MODIFY_DB =~ ^[Ss]$ ]]; then
    read -p "Host [127.0.0.1]: " DB_HOST
    DB_HOST=${DB_HOST:-127.0.0.1}
    
    read -p "Puerto [3306]: " DB_PORT
    DB_PORT=${DB_PORT:-3306}
    
    read -p "Nombre de base de datos [sistema_gestion_mpfn]: " DB_DATABASE
    DB_DATABASE=${DB_DATABASE:-sistema_gestion_mpfn}
    
    read -p "Usuario [root]: " DB_USERNAME
    DB_USERNAME=${DB_USERNAME:-root}
    
    read -sp "Contraseña: " DB_PASSWORD
    echo ""
    
    # Actualizar .env
    sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
    sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    
    print_success "Configuración de base de datos actualizada"
else
    DB_DATABASE="sistema_gestion_mpfn"
    DB_USERNAME="root"
    DB_PASSWORD=""
fi

# Crear base de datos
print_status "Creando base de datos (si no existe)..."
mysql -h ${DB_HOST:-127.0.0.1} -P ${DB_PORT:-3306} -u $DB_USERNAME ${DB_PASSWORD:+-p$DB_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || {
    print_warning "No se pudo crear la base de datos automáticamente."
    print_warning "Por favor créela manualmente con: CREATE DATABASE $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
}

# Ejecutar migraciones
print_status "Ejecutando migraciones..."
php artisan migrate --force
print_success "Migraciones ejecutadas"

# Ejecutar seeders
print_status "Ejecutando seeders (datos de prueba)..."
php artisan db:seed --force
print_success "Datos de prueba cargados"

# Crear enlace simbólico para storage
print_status "Creando enlace simbólico para storage..."
php artisan storage:link 2>/dev/null || print_warning "El enlace simbólico ya existe o no se pudo crear"

echo ""
print_success "¡Instalación completada exitosamente!"
echo ""
echo "=========================================="
echo "  Credenciales de acceso:"
echo "=========================================="
echo ""
echo "  Admin:     admin@demo.com / 12345678"
echo "  Fiscal:    fiscal1@demo.com / 12345678"
echo "  Asistente: asistente1@demo.com / 12345678"
echo "  Ciudadano: ciudadano1@demo.com / 12345678"
echo ""
echo "=========================================="
echo "  Para iniciar el servidor:"
echo "=========================================="
echo ""
echo "  php artisan serve"
echo ""
echo "  Accede a: http://localhost:8000"
echo ""
echo "=========================================="
