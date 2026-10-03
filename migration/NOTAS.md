# Notas de adaptación a PHP 8.4

## Cambios

- Laravel 7.30.6 -> 12.69.3; PHP requerido ~8.4.0.
- JWT 2.3.0, DomPDF 3.1.2, Laravel Excel 3.1.70, PhpSpreadsheet 1.30.7.
- PHPUnit 11 y Collision 8; eliminados Ignition antiguo, Fideloper Proxy,
  Fruitcake Laravel CORS y Doctrine DBAL. Laravel proporciona ahora las funciones
  de proxy, CORS y modificación de columnas utilizadas por la aplicación.
- Conservada la estructura clásica de Laravel (Kernel, providers y namespace
  App de los modelos), compatible con Laravel 12. Se conservaron las rutas
  Controller@method mediante el namespace explícito del RouteServiceProvider.
- TrustProxies usa el middleware del framework y las cabeceras explícitas;
  mantenimiento usa PreventRequestsDuringMaintenance; CORS usa HandleCors.
- UserFactory convertido a clase, HasFactory incorporado y DatabaseSeeder
  con namespace y autoload PSR-4. Configuración PHPUnit actualizada.
- Correo actualizado de la configuración SwiftMailer a Symfony Mailer.
- Migración de caracteres de servicios_estudios conserva nullable en los campos
  opcionales porque Laravel moderno exige declarar los modificadores al cambiar columnas.
- Eliminada la función global del archivo de rutas que provocaba una redeclaración
  fatal al arrancar más de una aplicación durante las pruebas. Su uso se sustituyó
  por Str::random(8).
- Corregido expires_in: TTL en minutos multiplicado por 60; prueba contra exp/iat del JWT.
- Docker actualizado a PHP 8.4 FPM + Nginx, con puertos y servicios independientes.
- Entorno local aislado: APP_KEY/JWT_SECRET propios, cookie/prefijo de caché propios,
  base propuesta becas_php84 y transporte de correo log.

## Verificación realizada

- `composer update --no-interaction --prefer-dist`: instalación completa de 123 paquetes.
- `composer validate --strict`: correcto.
- `composer check-platform-reqs`: correcto con PHP 8.4.26 de Herd.
- Composer no informó avisos de vulnerabilidades durante la resolución.
- PHPUnit: 10 pruebas, 26 aserciones; sin errores ni avisos mostrados.
- 160 rutas registradas (`routes-php84.json`).
- `config:cache`, `route:cache`, `view:cache`: correctos; cachés limpiadas después.
- No se modificó el origen; continuó con sus cambios previos en routes/api.php y env.

## Pendiente con una base local representativa

- Importar una copia MySQL y verificar las migraciones pendientes y las 89 migraciones
  históricas si se necesita una instalación desde cero.
- Probar registro externo, familias, alumnos, encuestas, cálculos de becas y operaciones
  por perfil con datos representativos, además de PDFs completos y archivos reales.
- Validar correo SMTP, subida/descarga de documentos y frontend externo.
- Revisar autorizaciones y el tratamiento de contraseñas identificados previamente.
- Compilación frontend y arranque Docker (el daemon no estaba activo).

El PDF y Excel de prueba son pruebas de integración de dependencias, no una
validación visual de cada reporte. No se conectó, migró ni modificó una base real.

## Referencias de actualización

- https://laravel.com/docs/8.x/upgrade
- https://laravel.com/docs/9.x/upgrade
- https://laravel.com/docs/10.x/upgrade
- https://laravel.com/docs/11.x/upgrade
- https://laravel.com/docs/12.x/upgrade

El lock de Laravel 7 se conserva únicamente como referencia en composer-php74.lock.
El lock activo es el composer.lock de la raíz y se debe usar con composer install.

Comprobación de sintaxis: 206 archivos PHP revisados con PHP 8.4, sin errores.

