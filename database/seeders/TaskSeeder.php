<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Configurar entorno de desarrollo',
                'description' => 'Instalar dependencias y validar conexión a PostgreSQL',
                'status' => 'pending',
                'items' => [
                    [
                        'title' => 'Instalar Composer dependencies',
                        'is_completed' => true,
                        'priority' => 'medium',
                    ],
                    [
                        'title' => 'Configurar variables de entorno',
                        'is_completed' => false,
                        'priority' => 'high',
                    ],
                ],
            ],
            [
                'title' => 'Definir estructura de tareas',
                'description' => 'Revisar entidades Task e Item para la API',
                'status' => 'pending',
                'items' => [
                    [
                        'title' => 'Definir campos de Task',
                        'is_completed' => true,
                        'priority' => 'medium',
                    ],
                    [
                        'title' => 'Definir campos de Item',
                        'is_completed' => false,
                        'priority' => 'high',
                    ],
                ],
            ],
            [
                'title' => 'Implementar endpoint de listado',
                'description' => 'Exponer GET /tasks con respuesta JSON',
                'status' => 'pending',
                'items' => [
                    [
                        'title' => 'Agregar relación items en Task',
                        'is_completed' => true,
                        'priority' => 'medium',
                    ],
                    [
                        'title' => 'Ordenar tareas por created_at',
                        'is_completed' => false,
                        'priority' => 'low',
                    ],
                ],
            ],
            [
                'title' => 'Crear migraciones iniciales',
                'description' => 'Migraciones de tasks e items aplicadas correctamente',
                'status' => 'done',
                'items' => [
                    [
                        'title' => 'Crear tabla tasks',
                        'is_completed' => true,
                        'priority' => 'high',
                    ],
                    [
                        'title' => 'Crear tabla items',
                        'is_completed' => true,
                        'priority' => 'high',
                    ],
                ],
            ],
            [
                'title' => 'Probar endpoints en Postman',
                'description' => 'Validar CRUD base de tareas',
                'status' => 'done',
                'items' => [
                    [
                        'title' => 'Probar GET /tasks',
                        'is_completed' => true,
                        'priority' => 'medium',
                    ],
                    [
                        'title' => 'Probar POST /tasks',
                        'is_completed' => true,
                        'priority' => 'medium',
                    ],
                ],
            ],
        ];

        foreach ($tasks as $data) {
            $items = $data['items'];
            unset($data['items']);

            $task = Task::query()->updateOrCreate(
                ['title' => $data['title']],
                $data
            );

            $task->items()->delete();
            $task->items()->createMany($items);
        }
    }
}
