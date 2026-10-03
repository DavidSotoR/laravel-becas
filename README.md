# API de becas — PHP 8.4 / Laravel 12

Copia independiente de `../laravel-becas`, preparada el 1 de octubre de 2026.
El proyecto original no se modificó. Se conservaron los controladores, modelos,
rutas (incluidos los cambios locales existentes), vistas, recursos y migraciones.

## Estado comprobado

- PHP 8.4.26 y Laravel 12.69.3.
- Dependencias instaladas en `vendor` y fijadas en `composer.lock`.
- Composer: configuración válida y requisitos de plataforma satisfechos.
- 10 pruebas aprobadas, con 26 aserciones: página inicial, validación del login,
  acceso protegido, CORS, login/JWT/me/logout, PDF básico, Excel de ida y vuelta,
  plantilla de correo mediante transporte en memoria y fábrica de usuarios.
- 160 rutas registradas; cachés de configuración, rutas y Blade generadas correctamente.
- El `.env` local tiene claves APP_KEY y JWT_SECRET nuevas, correo en modo `log`
  y el nombre de base de datos separado `becas_php84`.

Estas comprobaciones no certifican todos los procesos del negocio. Las pruebas
usan SQLite en memoria con un esquema mínimo de autenticación, sin ejecutar las
89 migraciones históricas ni acceder a la base MySQL original.

## Abrir con Herd

1. En Herd, agrega esta carpeta como sitio: `laravel-becas-php84`.
2. Selecciona PHP **8.4** para este sitio. El directorio público es `public`.
3. Configura en `.env` la conexión a una **copia local** de tu base de datos.
   El nombre propuesto es `becas_php84`. La base y los datos no están incluidos
   ni fueron creados/importados durante esta adaptación.
4. Abre el sitio asignado por Herd y usa su URL con el prefijo `/api` en tu cliente.
   El valor inicial de APP_URL es `http://laravel-becas-php84.test`;
   ajústalo si Herd asigna otro dominio.

Desde una terminal configurada con PHP 8.4, dentro de esta carpeta:

```powershell
php -v
composer install
composer check-platform-reqs
php artisan config:clear
php artisan route:list
php artisan test
```

La copia entregada ya tiene dependencias y claves locales. Si la instalas en otra
máquina, copia `.env.example` a `.env` cuando no exista y ejecuta:

```powershell
php artisan key:generate
php artisan jwt:secret
```

No regeneres las claves de una instalación existente sin revisar sus datos
cifrados y sesiones. Las claves nuevas de esta copia no descifran información
cifrada con las claves del proyecto original y requieren un nuevo login.

Si PHP no está en PATH, en esta computadora puedes comprobarlo directamente:

```powershell
& "$env:USERPROFILE\.config\herd\bin\php84\php.exe" artisan --version
& "$env:USERPROFILE\.config\herd\bin\php84\php.exe" vendor\bin\phpunit
& "$env:USERPROFILE\.config\herd\bin\php84\php.exe" "$env:USERPROFILE\.config\herd\bin\composer.phar" check-platform-reqs
```

Para servir temporalmente sin configurar el sitio de Herd:

```powershell
php artisan serve --host=127.0.0.1 --port=8084
```

Para archivos públicos, crea el enlace de almacenamiento con `php artisan storage:link`.

## Base de datos y correo

Importa un respaldo en una base independiente antes de probar los flujos reales.
Conserva la tabla `migrations` del respaldo y revisa `php artisan migrate:status`
antes de decidir qué migraciones faltan. No uses `migrate:fresh` sobre tus datos.
La reconstrucción completa desde cero de las migraciones históricas requiere
validación adicional con MySQL; no se ha dado por aprobada.

El correo local utiliza `MAIL_MAILER=log`. Para SMTP configura host, puerto,
usuario, contraseña y remitente. Usa `MAIL_SCHEME=smtp` (STARTTLS cuando esté
disponible) o `smtps` según tu proveedor; se adaptó la configuración a Symfony Mailer.

## Docker opcional

El Dockerfile usa PHP 8.4 FPM y las extensiones para MySQL, PDF y Excel.
`docker compose up --build -d` publica Nginx en `http://localhost:8084` y conecta
con FPM en el puerto interno 9000. Las dependencias deben instalarse antes
(`docker compose run --rm app composer install`, si no usas Composer de Herd).
Docker no fue ejecutado: el motor estaba apagado.

`docker-compose-ms.yml` ofrece una base MySQL de desarrollo separada en el puerto
33084. No contiene datos. Para Herd usa DB_HOST=127.0.0.1 y DB_PORT=33084 si
inicias ese servicio; para la aplicación en Docker conecta a tu servidor MySQL
con el host apropiado, por ejemplo host.docker.internal. Las credenciales de ese
archivo son exclusivamente de desarrollo.

## Alcance de la copia

Se excluyeron el historial `.git`, los directorios regenerables `vendor` y
`node_modules` del origen y los archivos de entorno `.env` / `env` del origen.
`vendor` se instaló de nuevo. Se generó un `.env` propio a partir del ejemplo,
sin reutilizar credenciales de conexión. No se copió ninguna base de datos.

Se conservó Laravel Mix y el frontend existente; no se verificó la compilación
Node ni la integración con un frontend externo. Las deficiencias de autorización
y recuperación de contraseña identificadas en la revisión anterior siguen
pendientes, salvo la corrección de `expires_in` y del generador de contraseña
global necesaria para recargar las rutas durante las pruebas.

Consulta `migration/NOTAS.md` para el detalle técnico y límites de verificación.
