# Todo List Backend (Laravel 10 + PostgreSQL)

Repositorio: https://github.com/felipe507developer/todo-list-backend.git

API REST para gestión de tareas y sub-items (todo list).

## Stack

- PHP 8.2
- Laravel 10
- PostgreSQL 15

## Requisitos

- Composer 2+
- Docker Desktop (para PostgreSQL) o PostgreSQL local
- Git

## Instalación rápida

1. Clonar repositorio:

```bash
git clone https://github.com/felipe507developer/todo-list-backend.git
cd todo-list-backend
```

2. Instalar dependencias:

```bash
composer install
```

3. Configurar variables de entorno:

```bash
cp .env.example .env
php artisan key:generate
```

4. Levantar PostgreSQL con Docker:

```bash
docker compose up -d
```

5. Verificar credenciales en `.env`:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tasks_db
DB_USERNAME=tasks_user
DB_PASSWORD=secret
```

6. Ejecutar migraciones y seeders:

```bash
php artisan migrate --seed
```

## Ejecutar la API

### Opción A: Laragon

- Iniciar Laragon
- Acceder al proyecto por host local (ejemplo):

```text
http://backend.test
```

### Opción B: Servidor embebido de Laravel

```bash
php artisan serve
```

Base URL de API:

- Laragon: `http://backend.test/api`
- Artisan serve: `http://127.0.0.1:8000/api`

## Endpoints

### 1) Listar tareas

- `GET /tasks`

### 2) Crear tarea con items

- `POST /tasks`

Body JSON:

```json
{
	"title": "Comprar víveres",
	"description": "Lista del supermercado",
	"status": "pending",
	"items": [
		{
			"title": "Leche",
			"is_completed": false,
			"priority": "medium"
		}
	]
}
```

### 3) Actualizar tarea (incluye reemplazo de items si se envían)

- `PUT /tasks/{task}`

### 4) Actualizar estado de un item

- `PATCH /tasks/{task}/items/{item}/status`

Body JSON:

```json
{
	"is_completed": true
}
```

### 5) Eliminar tarea

- `DELETE /tasks/{task}`

## Regla de negocio

- Una `Task` no puede pasar a `done` si tiene `items` incompletos.
- Si se marca un item como incompleto en una tarea `done`, la tarea pasa a `inProgress`.

## Seeders incluidos

- `TaskSeeder` crea:
	- 5 tareas iniciales
	- items asociados por tarea

Comandos útiles:

```bash
php artisan db:seed
php artisan migrate:fresh --seed
```

## Validaciones

Se usa `FormRequest` en:

- `StoreTaskRequest`
- `UpdateTaskRequest`
- `UpdateItemStatusRequest`

## Pruebas recomendadas en Postman

Headers para requests con body JSON:

- `Accept: application/json`
- `Content-Type: application/json`

Flujo sugerido:

1. `GET /tasks`
2. `POST /tasks`
3. `PUT /tasks/{id}`
4. `PATCH /tasks/{task}/items/{item}/status`
5. `DELETE /tasks/{id}`

## Solución de problemas

### Error de conexión o auth a PostgreSQL en `127.0.0.1:5432`

Si existe PostgreSQL local activo en Windows, puede colisionar con Docker.

Opciones:

- Detener servicio local PostgreSQL, o
- Cambiar puerto en `docker-compose.yml` y `DB_PORT` en `.env`.

### `ECONNREFUSED 127.0.0.1:8000` en Postman

Significa que no hay servidor en ese puerto.

- Si usas Laragon: usar `http://backend.test/api`
- Si usas artisan: ejecutar `php artisan serve`
