# Ejemplos Completos de Endpoints para API Find Tutors

## 🎯 Endpoint con TODOS los parámetros

### URL Base:
```
GET /api/find-tutors
```

### Endpoint Completo con Todos los Filtros:

```
GET /api/find-tutors?keyword=matemáticas&tutor_name=María&group_id=1&min_courses=3&min_rating=4.5&page=1
```

## 📋 Explicación de cada parámetro:

| Parámetro | Valor | Descripción |
|-----------|-------|-------------|
| `keyword` | `matemáticas` | Busca tutores que enseñen materias que contengan "matemáticas" |
| `tutor_name` | `María` | Busca tutores cuyo nombre contenga "María" |
| `group_id` | `1` | Solo tutores que enseñen materias del grupo/categoría ID 1 |
| `min_courses` | `3` | Solo tutores con al menos 3 cursos completados |
| `min_rating` | `4.5` | Solo tutores con calificación promedio >= 4.5 |
| `page` | `1` | Página 1 de resultados (10 tutores por página) |

## 🔍 Ejemplos Prácticos por Caso de Uso:

### 1. **Búsqueda Específica de Tutor de Matemáticas**
```
GET /api/find-tutors?keyword=álgebra&tutor_name=Carlos&min_rating=4.0&min_courses=2
```
*Busca tutores llamados Carlos que enseñen álgebra, con rating mínimo 4.0 y al menos 2 cursos completados*

### 2. **Búsqueda por Categoría con Filtros de Calidad**
```
GET /api/find-tutors?group_id=2&min_rating=4.8&min_courses=5&page=1
```
*Busca tutores de la categoría 2 (ej: Ciencias) con excelente calificación y experiencia*

### 3. **Búsqueda General con Filtros Mínimos**
```
GET /api/find-tutors?keyword=física&min_rating=3.5&min_courses=1
```
*Busca tutores de física con calificación aceptable y al menos 1 curso completado*

### 4. **Búsqueda por Nombre Específico**
```
GET /api/find-tutors?tutor_name=Ana&min_courses=3&min_rating=4.2
```
*Busca tutores llamados Ana con buena experiencia y calificación*

### 5. **Navegación por Páginas**
```
GET /api/find-tutors?keyword=química&page=2&min_rating=4.0
```
*Página 2 de tutores de química con buen rating*

## 🛠️ Ejemplos con cURL:

### Ejemplo 1: Búsqueda Completa
```bash
curl -X GET "http://localhost/ClassGoweb/public/api/find-tutors?keyword=matemáticas&tutor_name=María&group_id=1&min_courses=3&min_rating=4.5&page=1" \
  -H "Accept: application/json"
```

### Ejemplo 2: Con Autenticación
```bash
curl -X GET "http://localhost/ClassGoweb/public/api/find-tutors?keyword=física&min_rating=4.0&min_courses=2" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

### Ejemplo 3: JavaScript/Fetch
```javascript
const params = new URLSearchParams({
  keyword: 'matemáticas',
  tutor_name: 'María',
  group_id: '1',
  min_courses: '3',
  min_rating: '4.5',
  page: '1'
});

fetch(`/api/find-tutors?${params}`, {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'Authorization': 'Bearer TU_TOKEN_AQUI' // opcional
  }
})
.then(response => response.json())
.then(data => {
  console.log('Tutores encontrados:', data.data.list);
  console.log('Paginación:', data.data.pagination);
});
```

## 📊 Respuesta Esperada:

```json
{
  "data": {
    "list": [
      {
        "id": 15,
        "is_favorite": false,
        "avg_rating": 4.7,
        "min_price": "$30.00",
        "active_students": 8,
        "total_reviews": 25,
        "completed_courses_count": 5,
        "is_online": true,
        "sessions": 120,
        "profile": {
          "first_name": "María",
          "last_name": "González",
          "image": "maria-gonzalez.jpg",
          "slug": "maria-gonzalez",
          "tagline": "Especialista en matemáticas avanzadas",
          "description": "Profesora con 10 años de experiencia..."
        },
        "country": {
          "id": 1,
          "name": "España",
          "short_code": "ES"
        },
        "languages": [
          {
            "id": 1,
            "name": "Español"
          },
          {
            "id": 2,
            "name": "Inglés"
          }
        ],
        "subjects": [
          {
            "id": 1,
            "name": "Matemáticas",
            "hour_rate": 30.00,
            "sessions": 45
          },
          {
            "id": 2,
            "name": "Álgebra",
            "hour_rate": 35.00,
            "sessions": 30
          }
        ]
      }
    ],
    "pagination": {
      "total": 1,
      "count": 1,
      "perPage": 10,
      "currentPage": 1,
      "totalPages": 1
    }
  }
}
```

## 🎯 Casos de Uso Comunes:

### **Para Aplicación Móvil:**
```
GET /api/find-tutors?keyword=matemáticas&min_rating=4.0&page=1
```

### **Para Filtros Avanzados:**
```
GET /api/find-tutors?group_id=1&min_courses=5&min_rating=4.5&page=1
```

### **Para Búsqueda por Nombre:**
```
GET /api/find-tutors?tutor_name=Juan&min_rating=3.5
```

### **Para Exploración General:**
```
GET /api/find-tutors?page=1
```

## ⚡ Tips de Optimización:

1. **Usa `keyword`** para búsquedas específicas de materias
2. **Usa `tutor_name`** cuando conozcas el nombre del tutor
3. **Usa `group_id`** para filtrar por categorías de materias
4. **Usa `min_rating`** para asegurar calidad
5. **Usa `min_courses`** para tutores con experiencia
6. **Usa `page`** para navegar por resultados

## 🔧 Parámetros Opcionales:

Todos los parámetros son opcionales. Puedes usar solo los que necesites:

- Solo keyword: `?keyword=matemáticas`
- Solo rating: `?min_rating=4.0`
- Solo paginación: `?page=2`
- Combinación: `?keyword=física&min_rating=4.5&page=1` 