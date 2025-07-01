# API de Reseñas - Documentación

## Descripción
API para gestionar el sistema de reseñas entre usuarios. Permite crear, leer, actualizar y eliminar reseñas, así como obtener estadísticas.

## Estructura de la Base de Datos

### Tabla `reviews` (Principal)
- `id` - ID único de la reseña
- `rating` - Valoración (0.0 a 5.0)
- `comment` - Comentario (opcional)
- `status` - Estado (active/inactive)
- `created_at` / `updated_at` - Timestamps

### Tabla `user_reviews` (Intermedia)
- `id` - ID único de la relación
- `user_id` - Usuario que recibe la reseña
- `reviewer_id` - Usuario que hace la reseña
- `review_id` - ID de la reseña
- `created_at` / `updated_at` - Timestamps

## Endpoints

### 1. Obtener Reseñas Recibidas por un Usuario
**GET** `/api/reviews`

Obtiene todas las reseñas que ha recibido un usuario específico.

#### Parámetros de Query
- `user_id` (requerido) - ID del usuario que recibe las reseñas
- `page` (opcional) - Número de página (default: 1)
- `per_page` (opcional) - Elementos por página (default: 10, max: 50)

#### Ejemplo de Request
```bash
GET /api/reviews?user_id=1&page=1&per_page=10
Authorization: Bearer {token}
```

#### Ejemplo de Response
```json
{
    "success": true,
    "message": "Reseñas recibidas obtenidas exitosamente",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "reviewer_id": 2,
                "review_id": 1,
                "created_at": "2024-01-15T10:30:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z",
                "reviewer": {
                    "id": 2,
                    "name": "Juan Pérez",
                    "email": "juan.perez@email.com",
                    "created_at": "2024-01-01T00:00:00.000000Z",
                    "profile": {
                        "id": 2,
                        "user_id": 2,
                        "first_name": "Juan",
                        "last_name": "Pérez",
                        "avatar": "path/to/avatar.jpg",
                        "phone": "+1234567890",
                        "address": "Calle Principal 123",
                        "city": "Madrid",
                        "state": "Madrid",
                        "country": "España",
                        "zip_code": "28001",
                        "bio": "Tutor experimentado en matemáticas"
                    }
                },
                "review": {
                    "id": 1,
                    "rating": 4.5,
                    "comment": "Excelente tutor, muy paciente y claro en sus explicaciones.",
                    "status": "active",
                    "created_at": "2024-01-15T10:30:00.000000Z",
                    "updated_at": "2024-01-15T10:30:00.000000Z"
                }
            }
        ],
        "total": 1,
        "per_page": 10
    }
}
```

### 2. Obtener Reseñas Realizadas por un Usuario
**GET** `/api/reviews/given`

Obtiene todas las reseñas que ha realizado un usuario específico.

#### Parámetros de Query
- `user_id` (requerido) - ID del usuario que realizó las reseñas
- `page` (opcional) - Número de página (default: 1)
- `per_page` (opcional) - Elementos por página (default: 10, max: 50)

#### Ejemplo de Request
```bash
GET /api/reviews/given?user_id=2&page=1&per_page=10
Authorization: Bearer {token}
```

#### Ejemplo de Response
```json
{
    "success": true,
    "message": "Reseñas realizadas obtenidas exitosamente",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "reviewer_id": 2,
                "review_id": 1,
                "created_at": "2024-01-15T10:30:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z",
                "user": {
                    "id": 1,
                    "name": "María García",
                    "email": "maria.garcia@email.com",
                    "created_at": "2024-01-01T00:00:00.000000Z",
                    "profile": {
                        "id": 1,
                        "user_id": 1,
                        "first_name": "María",
                        "last_name": "García",
                        "avatar": "path/to/avatar.jpg",
                        "phone": "+0987654321",
                        "address": "Avenida Central 456",
                        "city": "Barcelona",
                        "state": "Cataluña",
                        "country": "España",
                        "zip_code": "08001",
                        "bio": "Estudiante de ingeniería"
                    }
                },
                "review": {
                    "id": 1,
                    "rating": 4.5,
                    "comment": "Excelente tutor, muy paciente y claro en sus explicaciones.",
                    "status": "active",
                    "created_at": "2024-01-15T10:30:00.000000Z",
                    "updated_at": "2024-01-15T10:30:00.000000Z"
                }
            }
        ],
        "total": 1,
        "per_page": 10
    }
}
```

### 3. Obtener Reseñas Recibidas (Alias)
**GET** `/api/reviews/received`

Alias del endpoint principal para obtener reseñas recibidas.

#### Parámetros de Query
- `user_id` (requerido) - ID del usuario que recibe las reseñas
- `page` (opcional) - Número de página (default: 1)
- `per_page` (opcional) - Elementos por página (default: 10, max: 50)

### 4. Crear Nueva Reseña
**POST** `/api/reviews`

Crea una nueva reseña para un usuario.

#### Parámetros del Body
- `user_id` (requerido) - ID del usuario que recibe la reseña
- `rating` (requerido) - Valoración (0.0 a 5.0)
- `comment` (opcional) - Comentario de la reseña

#### Ejemplo de Request
```bash
POST /api/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
    "user_id": 1,
    "rating": 4.5,
    "comment": "Excelente tutor, muy paciente y claro en sus explicaciones."
}
```

#### Ejemplo de Response
```json
{
    "success": true,
    "message": "Reseña creada exitosamente",
    "data": {
        "id": 1,
        "user_id": 1,
        "reviewer_id": 2,
        "review_id": 1,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z",
        "reviewer": {
            "id": 2,
            "name": "Juan Pérez",
            "email": "juan.perez@email.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "profile": {
                "id": 2,
                "user_id": 2,
                "first_name": "Juan",
                "last_name": "Pérez",
                "avatar": "path/to/avatar.jpg",
                "phone": "+1234567890",
                "address": "Calle Principal 123",
                "city": "Madrid",
                "state": "Madrid",
                "country": "España",
                "zip_code": "28001",
                "bio": "Tutor experimentado en matemáticas"
            }
        },
        "user": {
            "id": 1,
            "name": "María García",
            "email": "maria.garcia@email.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "profile": {
                "id": 1,
                "user_id": 1,
                "first_name": "María",
                "last_name": "García",
                "avatar": "path/to/avatar.jpg",
                "phone": "+0987654321",
                "address": "Avenida Central 456",
                "city": "Barcelona",
                "state": "Cataluña",
                "country": "España",
                "zip_code": "08001",
                "bio": "Estudiante de ingeniería"
            }
        },
        "review": {
            "id": 1,
            "rating": 4.5,
            "comment": "Excelente tutor, muy paciente y claro en sus explicaciones.",
            "status": "active",
            "created_at": "2024-01-15T10:30:00.000000Z",
            "updated_at": "2024-01-15T10:30:00.000000Z"
        }
    }
}
```

### 5. Obtener Reseña Específica
**GET** `/api/reviews/{id}`

Obtiene una reseña específica por su ID.

#### Ejemplo de Request
```bash
GET /api/reviews/1
Authorization: Bearer {token}
```

#### Ejemplo de Response
```json
{
    "success": true,
    "message": "Reseña obtenida exitosamente",
    "data": {
        "id": 1,
        "user_id": 1,
        "reviewer_id": 2,
        "review_id": 1,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "reviewer": {
            "id": 2,
            "name": "Juan Pérez",
            "profile": {
                "avatar": "path/to/avatar.jpg"
            }
        },
        "user": {
            "id": 1,
            "name": "María García",
            "profile": {
                "avatar": "path/to/avatar.jpg"
            }
        },
        "review": {
            "id": 1,
            "rating": 4.5,
            "comment": "Excelente tutor, muy paciente y claro en sus explicaciones.",
            "status": "active"
        }
    }
}
```

### 6. Actualizar Reseña
**PUT** `/api/reviews/{id}`

Actualiza una reseña existente (solo el autor puede editarla).

#### Parámetros del Body
- `rating` (opcional) - Nueva valoración (0.0 a 5.0)
- `comment` (opcional) - Nuevo comentario

#### Ejemplo de Request
```bash
PUT /api/reviews/1
Authorization: Bearer {token}
Content-Type: application/json

{
    "rating": 5.0,
    "comment": "Actualizado: Excelente tutor, muy paciente y claro en sus explicaciones."
}
```

#### Ejemplo de Response
```json
{
    "success": true,
    "message": "Reseña actualizada exitosamente",
    "data": {
        "id": 1,
        "user_id": 1,
        "reviewer_id": 2,
        "review_id": 1,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T11:00:00.000000Z",
        "reviewer": {
            "id": 2,
            "name": "Juan Pérez",
            "profile": {
                "avatar": "path/to/avatar.jpg"
            }
        },
        "user": {
            "id": 1,
            "name": "María García",
            "profile": {
                "avatar": "path/to/avatar.jpg"
            }
        },
        "review": {
            "id": 1,
            "rating": 5.0,
            "comment": "Actualizado: Excelente tutor, muy paciente y claro en sus explicaciones.",
            "status": "active"
        }
    }
}
```

### 7. Eliminar Reseña
**DELETE** `/api/reviews/{id}`

Elimina una reseña (la marca como inactiva, solo el autor puede eliminarla).

#### Ejemplo de Request
```bash
DELETE /api/reviews/1
Authorization: Bearer {token}
```

#### Ejemplo de Response
```json
{
    "success": true,
    "message": "Reseña eliminada exitosamente"
}
```

### 8. Obtener Estadísticas de Reseñas
**GET** `/api/reviews/stats/{userId}`

Obtiene estadísticas completas de las reseñas de un usuario.

#### Ejemplo de Request
```bash
GET /api/reviews/stats/1
Authorization: Bearer {token}
```

#### Ejemplo de Response
```json
{
    "success": true,
    "message": "Estadísticas obtenidas exitosamente",
    "data": {
        "received": {
            "total": 5,
            "average_rating": 4.2,
            "rating_distribution": {
                "5": 2,
                "4": 2,
                "3": 1,
                "2": 0,
                "1": 0,
                "0": 0
            },
            "recent_reviews": [
                {
                    "id": 1,
                    "rating": 4.5,
                    "comment": "Excelente tutor",
                    "reviewer": {
                        "id": 2,
                        "name": "Juan Pérez",
                        "profile": {
                            "avatar": "path/to/avatar.jpg"
                        }
                    },
                    "created_at": "2024-01-15T10:30:00.000000Z"
                }
            ]
        },
        "given": {
            "total": 3,
            "recent_reviews": [
                {
                    "id": 2,
                    "rating": 4.0,
                    "comment": "Buen estudiante",
                    "user": {
                        "id": 3,
                        "name": "Ana López",
                        "profile": {
                            "avatar": "path/to/avatar.jpg"
                        }
                    },
                    "created_at": "2024-01-14T15:20:00.000000Z"
                }
            ]
        }
    }
}
```

## Códigos de Error

### 400 - Bad Request
- Datos de entrada inválidos
- Validación fallida

### 401 - Unauthorized
- Token de autenticación faltante o inválido

### 403 - Forbidden
- No tienes permisos para editar/eliminar esta reseña

### 404 - Not Found
- Reseña no encontrada
- Usuario no encontrado

### 409 - Conflict
- Ya has reseñado a este usuario

### 422 - Unprocessable Entity
- No puedes reseñarte a ti mismo

### 500 - Internal Server Error
- Error interno del servidor

## Reglas de Negocio

1. **Autenticación**: Todos los endpoints requieren autenticación con Bearer Token
2. **Autoría**: Solo el autor de una reseña puede editarla o eliminarla
3. **Unicidad**: Un usuario solo puede hacer una reseña por usuario
4. **Auto-reseña**: Un usuario no puede reseñarse a sí mismo
5. **Eliminación suave**: Las reseñas se marcan como inactivas en lugar de eliminarse físicamente
6. **Paginación**: Los endpoints de listado soportan paginación
7. **Validación**: Todas las entradas se validan antes de procesarse

## Ejemplos de Uso

### Obtener reseñas recibidas por un usuario
```bash
curl -X GET "https://tu-dominio.com/api/reviews?user_id=1" \
  -H "Authorization: Bearer tu-token-aqui"
```

### Obtener reseñas realizadas por un usuario
```bash
curl -X GET "https://tu-dominio.com/api/reviews/given?user_id=2" \
  -H "Authorization: Bearer tu-token-aqui"
```

### Crear una nueva reseña
```bash
curl -X POST "https://tu-dominio.com/api/reviews" \
  -H "Authorization: Bearer tu-token-aqui" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "rating": 4.5,
    "comment": "Excelente tutor, muy paciente y claro en sus explicaciones."
  }'
```

### Obtener estadísticas de un usuario
```bash
curl -X GET "https://tu-dominio.com/api/reviews/stats/1" \
  -H "Authorization: Bearer tu-token-aqui"
```

## Notas Importantes

- Todas las fechas están en formato ISO 8601
- Los ratings se manejan como decimales de 0.0 a 5.0
- Las reseñas inactivas no aparecen en los listados
- Los perfiles de usuario incluyen información básica como avatar
- Las estadísticas incluyen tanto reseñas recibidas como realizadas
- La paginación es opcional y tiene límites para evitar sobrecarga 