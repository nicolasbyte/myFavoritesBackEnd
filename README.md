# MyFavorites Backend

Este es el backend para la aplicación MyFavorites, construido con Laravel 11. Proporciona una API RESTful para la gestión de usuarios, autenticación y futuras funcionalidades.

## Requisitos

- Docker
- Docker Compose

## Guía de Instalación y Puesta en Marcha

Sigue estos pasos para levantar el entorno de desarrollo local.

### 1. Clonar el Repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd myfavoritesbackend
```

### 2. Configurar el Entorno

El proyecto utiliza un archivo `.env` para gestionar las variables de entorno.

```bash
# Copia el archivo de ejemplo para crear tu configuración local
cp .env.example .env
```

Abre el archivo `.env` y asegúrate de configurar las siguientes variables:

- **Base de Datos:** Las credenciales deben coincidir con las que se usarán para crear el contenedor de la base de datos.
  ```env
  DB_CONNECTION=mysql
  DB_HOST=db
  DB_PORT=3306
  DB_DATABASE=myfavorites_db
  DB_USERNAME=user
  DB_PASSWORD=secret
  MYSQL_ROOT_PASSWORD=root_secret
  ```

- **Servidor de Correo (SMTP):** Configura tus credenciales para el envío de correos (ej. Mailtrap, Gmail con contraseña de aplicación, etc.).
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=sandbox.smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=tu_usuario
  MAIL_PASSWORD=tu_contraseña
  MAIL_ENCRYPTION=tls
  ```

- **URL del Usuario de prueba:** Especifica el correo y clave del usuario de prueba.
  ```env
  TEST_USER_EMAIL=correoAlquellegaranCorreos
  TEST_USER_PASSWORD=ClaveDelUsuarioDePruebas

  ```

- **reCAPTCHA:** Añade tu clave secreta de Google reCAPTCHA v3. Para pruebas locales sin un frontend, puedes usar la clave de prueba de Google.
  ```env
  # Clave real de producción/desarrollo
  RECAPTCHA_SECRET_KEY=TU_CLAVE_SECRETA_REAL
  
  # Clave para pruebas locales con Postman (v2)
  RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
  ```

- **URL del Frontend:** Especifica la URL donde correrá tu aplicación frontend.
  ```env
  FRONTEND_URL=http://localhost:5173
  CORS_ALLOWED_ORIGINS=http://localhost:5173
  ```

### 3. Construir y Levantar los Contenedores

Este comando construirá la imagen de la aplicación y levantará los contenedores de la app y la base de datos en segundo plano.

```bash
docker-compose up -d --build
```

### 4. Inicializar la Aplicación

Una vez que los contenedores estén corriendo, ejecuta los siguientes comandos para finalizar la instalación.

```bash
# Generar la clave de la aplicación
docker-compose exec app php artisan key:generate

# Ejecutar las migraciones de la base de datos y los seeders
docker-compose exec app php artisan migrate:fresh --seed
```

¡Listo! La aplicación ya está corriendo y accesible en `http://localhost:8001`.

## Uso de la API

### Autenticación (Login)

Para obtener un token de acceso, necesitas un cliente de Passport. Si no tienes uno, créalo con:

```bash
docker-compose exec app php artisan passport:client --password
```

Usa el `Client ID` y `Client Secret` generados para hacer una petición `POST` a `/oauth/token` con el siguiente cuerpo (`x-www-form-urlencoded`):

- `grant_type`: `password`
- `client_id`: (tu client_id)
- `client_secret`: (tu client_secret)
- `username`: (email del usuario, ej. `test@example.com`)
- `password`: (contraseña del usuario)

### Proceso de Jobs (Colas)

Para que funcionalidades como el envío de correos de recuperación de contraseña funcionen, necesitas tener un trabajador de colas corriendo.

Abre una nueva terminal y ejecuta:
```bash
docker-compose exec app php artisan queue:listen
```
Deja este proceso corriendo mientras desarrollas.

## Endpoints Principales

- `POST /api/register`: Registro de un nuevo usuario.
- `POST /oauth/token`: Login de usuario y obtención de token.
- `POST /api/forgot-password`: Solicitar correo de recuperación de contraseña.
- `POST /api/reset-password`: Cambiar la contraseña usando el token del correo.

### Rutas Protegidas (Requieren Bearer Token)

- `POST /api/logout`: Revocar el token de acceso actual.
- `GET /api/users`: Listar todos los usuarios.
- `GET /api/users/{uuid}`: Obtener un usuario específico.
- `PUT /api/users/{uuid}`: Actualizar un usuario.
- `DELETE /api/users/{uuid}`: Eliminar un usuario.
- `GET /api/favorites`: Listar los favoritos del usuario.
- `POST /api/favorites`: Añadir un favorito a la lista del usuario.
- `DELETE /api/favorites/{favorite}`: Eliminar un favorito de la lista del usuario.

## Licencia

El framework Laravel es un software de código abierto licenciado bajo la [licencia MIT](https://opensource.org/licenses/MIT).
