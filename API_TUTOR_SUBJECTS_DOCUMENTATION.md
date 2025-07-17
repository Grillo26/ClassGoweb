# API de Gestión de Materias para Tutores

Esta documentación describe las APIs disponibles para que los tutores puedan gestionar sus materias en la plataforma ClassGo.

## Autenticación

**Las APIs funcionan sin restricciones de autenticación.** Puedes acceder a todos los endpoints sin necesidad de token de autenticación.

**Nota:** Estas APIs están diseñadas para ser públicas y permiten gestionar materias de cualquier usuario especificando el `user_id` en los parámetros.

## Endpoints Disponibles

### 1. Obtener Materias del Tutor

**GET** `/api/tutor-subjects`

Obtiene todas las materias asignadas al tutor autenticado.

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "subject_id": 5,
            "description": "Especialista en matemáticas avanzadas",
            "image": "subjects/math_tutor.jpg",
            "status": "active",
            "subject": {
                "id": 5,
                "name": "Matemáticas",
                "subject_group_id": 2
            }
        }
    ],
    "message": "Materias del tutor obtenidas exitosamente"
}
```

### 2. Obtener Materia Específica

**GET** `/api/tutor-subjects/{id}`

Obtiene una materia específica del tutor.

**Parámetros:**
- `id` (integer): ID de la materia del tutor

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "subject_id": 5,
        "description": "Especialista en matemáticas avanzadas",
        "image": "subjects/math_tutor.jpg",
        "status": "active",
        "subject": {
            "id": 5,
            "name": "Matemáticas",
            "subject_group_id": 2
        }
    },
    "message": "Materia obtenida exitosamente"
}
```

### 3. Agregar Nueva Materia

**POST** `/api/tutor-subjects`

Agrega una nueva materia a un usuario específico.

**Body (multipart/form-data):**
```json
{
    "user_id": 123,
    "subject_id": 5,
    "description": "Especialista en matemáticas avanzadas con 5 años de experiencia",
    "image": "[archivo de imagen opcional]"
}
```

**Parámetros:**
- `user_id` (integer, requerido): ID del usuario al que se le asignará la materia
- `subject_id` (integer, requerido): ID de la materia a agregar
- `description` (string, opcional): Descripción de la experiencia en la materia
- `image` (file, opcional): Imagen relacionada con la materia (máx. 3MB, formatos: jpeg, png, jpg, gif, webp)

**Respuesta exitosa (201):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "subject_id": 5,
        "description": "Especialista en matemáticas avanzadas con 5 años de experiencia",
        "image": "subjects/math_tutor.jpg",
        "status": "active",
        "subject": {
            "id": 5,
            "name": "Matemáticas",
            "subject_group_id": 2
        }
    },
    "message": "Materia agregada exitosamente"
}
```

**Errores posibles:**
- `409 Conflict`: "Ya tienes esta materia asignada"
- `422 Unprocessable Entity`: Errores de validación

### 4. Actualizar Materia

**PUT/PATCH** `/api/tutor-subjects/{id}`

Actualiza una materia existente del tutor.

**Parámetros:**
- `id` (integer): ID de la materia del tutor

**Body (multipart/form-data):**
```json
{
    "description": "Nueva descripción actualizada",
    "image": "[nuevo archivo de imagen opcional]",
    "status": "inactive"
}
```

**Parámetros:**
- `description` (string, opcional): Nueva descripción
- `image` (file, opcional): Nueva imagen (máx. 3MB)
- `status` (string, opcional): "active" o "inactive"

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "subject_id": 5,
        "description": "Nueva descripción actualizada",
        "image": "subjects/new_math_tutor.jpg",
        "status": "inactive",
        "subject": {
            "id": 5,
            "name": "Matemáticas",
            "subject_group_id": 2
        }
    },
    "message": "Materia actualizada exitosamente"
}
```

### 5. Eliminar Materia

**DELETE** `/api/tutor-subjects/{id}`

Elimina una materia del tutor.

**Parámetros:**
- `id` (integer): ID de la materia del tutor

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": null,
    "message": "Materia eliminada exitosamente"
}
```

### 6. Obtener Grupos de Materias

**GET** `/api/tutor-subjects/groups`

Obtiene todos los grupos de materias disponibles.

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Ciencias"
        },
        {
            "id": 2,
            "name": "Matemáticas"
        },
        {
            "id": 3,
            "name": "Idiomas"
        }
    ],
    "message": "Grupos de materias obtenidos exitosamente"
}
```

### 7. Obtener Materias por Grupo

**GET** `/api/tutor-subjects/groups/{groupId}/subjects`

Obtiene todas las materias de un grupo específico.

**Parámetros:**
- `groupId` (integer): ID del grupo de materias

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Matemáticas Básicas"
        },
        {
            "id": 2,
            "name": "Álgebra"
        },
        {
            "id": 3,
            "name": "Cálculo"
        }
    ],
    "message": "Materias del grupo obtenidas exitosamente"
}
```

### 8. Obtener Materias Disponibles

**GET** `/api/tutor-subjects/available`

Obtiene las materias disponibles para agregar (opcionalmente excluye las que ya tiene un usuario específico).

**Query Parameters:**
- `group_id` (integer, opcional): Filtrar por grupo de materias
- `keyword` (string, opcional): Buscar por nombre de materia
- `user_id` (integer, opcional): ID del usuario para excluir sus materias ya asignadas
- `per_page` (integer, opcional): Número de resultados por página (default: 20)

**Ejemplo de uso:**
```
GET /api/tutor-subjects/available?group_id=2&keyword=matemáticas&user_id=123&per_page=10
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 4,
                "name": "Geometría",
                "subject_group_id": 2,
                "group": {
                    "id": 2,
                    "name": "Matemáticas"
                }
            }
        ],
        "per_page": 10,
        "total": 1
    },
    "message": "Materias disponibles obtenidas exitosamente"
}
```

## Códigos de Error

| Código | Descripción |
|--------|-------------|
| 400 | Parámetros inválidos |
| 404 | Materia no encontrada |
| 409 | Materia ya asignada |
| 422 | Errores de validación |
| 500 | Error interno del servidor |

## Ejemplos de Uso

### Ejemplo 1: Agregar una nueva materia

```bash
curl -X POST "https://classgoapp.com/api/tutor-subjects" \
  -H "Content-Type: multipart/form-data" \
  -F "user_id=123" \
  -F "subject_id=5" \
  -F "description=Especialista en matemáticas avanzadas" \
  -F "image=@/path/to/math_image.jpg"
```

### Ejemplo 2: Obtener materias disponibles filtradas

```bash
curl -X GET "https://classgoapp.com/api/tutor-subjects/available?group_id=2&keyword=álgebra&user_id=123"
```

### Ejemplo 3: Actualizar descripción de una materia

```bash
curl -X PUT "https://classgoapp.com/api/tutor-subjects/1" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Nueva descripción actualizada"
  }'
```

## Notas Importantes

1. **Sin Autenticación**: Las APIs funcionan sin restricciones de autenticación
2. **Imágenes**: Las imágenes se almacenan en el directorio `storage/app/public/subjects/`
3. **Validación**: Se valida que no se dupliquen materias para el mismo usuario
4. **Paginación**: Las rutas que devuelven listas soportan paginación
5. **Filtros**: Se pueden filtrar materias por grupo y buscar por palabra clave
6. **Estados**: Las materias pueden tener estado "active" o "inactive"
7. **user_id**: Para operaciones específicas de usuario, se debe especificar el `user_id` en los parámetros 