# API de Tutoría Instantánea

## Descripción
Se ha implementado un nuevo filtro para el endpoint de búsqueda de tutores que permite encontrar solo aquellos tutores que tienen tiempo disponible en este momento para tutorías instantáneas.

## Endpoints Modificados

### 1. Búsqueda de Tutores Verificados con Filtro Instantáneo
**Endpoint:** `GET /api/verified-tutors`

**Parámetros:**
- `instant` (opcional): `true` para filtrar solo tutores disponibles ahora
- `keyword` (opcional): Buscar por nombre de materia
- `tutor_name` (opcional): Buscar por nombre del tutor
- `group_id` (opcional): Filtrar por categoría de materia
- `subject_id` (opcional): Filtrar por materia específica
- `min_courses` (opcional): Número mínimo de cursos completados
- `min_rating` (opcional): Calificación mínima
- `page` (opcional): Número de página para paginación

**Ejemplo de uso:**
```
GET /api/verified-tutors?instant=true&keyword=matemáticas&min_rating=4.5
```

**Respuesta cuando `instant=true`:**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "name": "Juan Pérez",
                "email": "juan@example.com",
                "profile": {
                    "first_name": "Juan",
                    "last_name": "Pérez",
                    "image": "profiles/juan.jpg"
                },
                "subjects": [...],
                "completed_courses_count": 5,
                "available_instant_slots": [
                    {
                        "id": 123,
                        "start_time": "14:00:00",
                        "end_time": "15:00:00",
                        "duration": "60",
                        "session_fee": "25.00",
                        "description": "Tutoría de matemáticas",
                        "date": "2024-01-15"
                    }
                ],
                "available_instant_slots_count": 1,
                "is_favorite": false
            }
        ],
        "current_page": 1,
        "total": 5
    }
}
```

### 2. Obtener Slots Instantáneos de un Tutor Específico
**Endpoint:** `GET /api/tutor/{id}/instant-slots`

**Parámetros:**
- `id`: ID del tutor

**Ejemplo de uso:**
```
GET /api/tutor/1/instant-slots
```

**Respuesta:**
```json
{
    "success": true,
    "data": {
        "tutor": {
            "id": 1,
            "name": "Juan Pérez",
            "image": "http://example.com/public/storage/profiles/juan.jpg"
        },
        "current_time": "2024-01-15 14:30:00",
        "available_slots": [
            {
                "id": 123,
                "start_time": "14:00:00",
                "end_time": "15:00:00",
                "duration": "60",
                "session_fee": "25.00",
                "description": "Tutoría de matemáticas",
                "date": "2024-01-15"
            }
        ],
        "total_available_slots": 1
    }
}
```

## Lógica del Filtro Instantáneo

Cuando se envía el parámetro `instant=true`, el sistema:

1. **Filtra por fecha actual:** Solo considera slots del día actual
2. **Filtra por hora actual:** Solo slots donde:
   - `start_time <= hora_actual`
   - `end_time >= hora_actual`
3. **Verifica tutor verificado:** Solo tutores con perfil verificado

## Campos de la Tabla user_subject_slots

- `id`: ID único del slot
- `start_time`: Hora de inicio (formato: HH:MM:SS)
- `end_time`: Hora de fin (formato: HH:MM:SS)
- `date`: Fecha del slot (formato: YYYY-MM-DD)
- `user_id`: ID del tutor
- `session_fee`: Precio de la sesión
- `description`: Descripción del slot
- `total_booked`: Número de reservas (0 = disponible)
- `duracion`: Duración en minutos

## Consideraciones Técnicas

1. **Zona horaria:** El sistema usa la zona horaria del servidor
2. **Concurrencia:** Se recomienda implementar bloqueos para evitar reservas simultáneas
3. **Cache:** Para mejor rendimiento, considerar cachear los resultados
4. **Logs:** Se registran logs detallados para debugging

## Ejemplos de Uso

### Frontend - React/Vue
```javascript
// Buscar tutores verificados instantáneos
const searchInstantTutors = async () => {
    const response = await fetch('/api/verified-tutors?instant=true');
    const data = await response.json();
    return data.data;
};

// Obtener slots de un tutor específico
const getTutorInstantSlots = async (tutorId) => {
    const response = await fetch(`/api/tutor/${tutorId}/instant-slots`);
    const data = await response.json();
    return data.data;
};
```

### Mobile App
```dart
// Flutter/Dart
Future<List<Tutor>> getInstantTutors() async {
    final response = await http.get(
        Uri.parse('${baseUrl}/api/verified-tutors?instant=true'),
        headers: {'Authorization': 'Bearer $token'},
    );
    
    if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return (data['data']['data'] as List)
            .map((tutor) => Tutor.fromJson(tutor))
            .toList();
    }
    throw Exception('Error al obtener tutores verificados instantáneos');
}
```

## Testing

### Casos de Prueba

1. **Tutor con slots en fecha y hora actual**
   - Crear slot: fecha actual, hora actual dentro del rango
   - Resultado: Tutor aparece en la lista

2. **Tutor sin slots en fecha/hora actual**
   - Crear slot: fecha pasada o hora fuera de rango
   - Resultado: Tutor no aparece en la lista

3. **Tutor no verificado**
   - Tutor sin `verified_at` en perfil
   - Resultado: Tutor no aparece en la lista

### Comandos de Testing
```bash
# Probar endpoint de búsqueda de tutores verificados
curl "http://localhost:8000/api/verified-tutors?instant=true"

# Probar endpoint de slots específicos
curl "http://localhost:8000/api/tutor/1/instant-slots"
``` 