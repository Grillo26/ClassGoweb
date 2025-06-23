# API Endpoint: Find Tutors

## Descripción
Endpoint para buscar tutores con múltiples filtros y parámetros de búsqueda.

## URL
```
GET /api/find-tutors
```

## Parámetros (Query Parameters)

| Parámetro | Tipo | Opcional | Descripción |
|-----------|------|----------|-------------|
| `keyword` | String | Sí | Para el buscador principal. Busca por nombre de materia. |
| `tutor_name` | String | Sí | Para el buscador del modal. Busca por nombre del tutor. |
| `group_id` | Integer | Sí | El ID de la categoría de materia seleccionada. |
| `min_courses` | Integer | Sí | El número mínimo de cursos completados que debe tener el tutor. |
| `min_rating` | Double | Sí | La calificación mínima (ej. 4.0, 4.5) que debe tener el tutor. |
| `page` | Integer | Sí | Para la paginación (ej. 1, 2, 3...). |

## Ejemplos de Uso

### Ejemplo 1: Búsqueda básica
```
GET /api/find-tutors
```

### Ejemplo 2: Búsqueda por nombre de tutor
```
GET /api/find-tutors?tutor_name=alvaro
```

### Ejemplo 3: Búsqueda por categoría de materia
```
GET /api/find-tutors?group_id=2
```

### Ejemplo 4: Búsqueda con múltiples filtros
```
GET /api/find-tutors?tutor_name=alvaro&group_id=2&min_rating=4.5&page=1
```

### Ejemplo 5: Búsqueda por keyword (materia)
```
GET /api/find-tutors?keyword=matemáticas
```

### Ejemplo 6: Búsqueda con filtro de cursos mínimos
```
GET /api/find-tutors?min_courses=5
```

## Respuesta

La respuesta incluye:
- Lista paginada de tutores que cumplen con los criterios de búsqueda
- Información del perfil del tutor
- Materias que imparte
- Calificaciones y reseñas
- Estado de favorito (si el usuario está autenticado)

## Notas Técnicas

- Todos los tutores devueltos están verificados (`verified_at` no es null)
- La búsqueda por `tutor_name` busca en `first_name`, `last_name` y la concatenación de ambos
- La búsqueda por `keyword` busca en el nombre de las materias
- El filtro `min_courses` cuenta las reseñas con estado 'completed'
- El filtro `min_rating` calcula el promedio de calificaciones del tutor
- La paginación por defecto es de 10 elementos por página

## Códigos de Error

- `200`: Éxito
- `400`: Parámetros inválidos
- `500`: Error interno del servidor

## Logs

El endpoint registra logs detallados de:
- Parámetros de búsqueda recibidos
- Número de tutores encontrados
- Errores que puedan ocurrir durante la búsqueda 