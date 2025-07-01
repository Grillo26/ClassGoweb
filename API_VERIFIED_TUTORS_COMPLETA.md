# API Verified Tutors - Documentación Completa

## 🎯 **Endpoint Actualizado:**
```
GET /api/verified-tutors
```

## 📋 **Descripción:**
La API `verified-tutors` ahora incluye **TODA** la funcionalidad de `find-tutors` más características adicionales específicas para tutores verificados.

## ✅ **Características Especiales:**
- ✅ **Solo tutores verificados** (`verified_at` no es null)
- ✅ **Solo tutores con materias registradas** (tienen al menos una materia)
- ✅ **Todos los filtros de find-tutors**
- ✅ **Conteo de cursos completados**
- ✅ **Paginación completa**
- ✅ **Logging detallado**

## 🔍 **Parámetros Disponibles:**

| Parámetro | Tipo | Descripción | Ejemplo |
|-----------|------|-------------|---------|
| `keyword` | string | Busca en nombre de materias | `?keyword=matemáticas` |
| `tutor_name` | string | Busca en nombre del tutor | `?tutor_name=María` |
| `group_id` | integer | Filtra por categoría de materias | `?group_id=1` |
| `subject_id` | integer | Filtra por materia específica | `?subject_id=5` |
| `min_courses` | integer | Mínimo de cursos completados | `?min_courses=3` |
| `min_rating` | float | Calificación mínima promedio | `?min_rating=4.5` |
| `page` | integer | Número de página | `?page=2` |

## 🚀 **Ejemplos de Uso:**

### **1. Búsqueda Básica (Solo tutores verificados con materias):**
```
GET /api/verified-tutors
```

### **2. Búsqueda por Materia:**
```
GET /api/verified-tutors?keyword=física
```

### **3. Búsqueda por Nombre de Tutor:**
```
GET /api/verified-tutors?tutor_name=Carlos
```

### **4. Búsqueda por Categoría:**
```
GET /api/verified-tutors?group_id=2
```

### **5. Búsqueda por Materia Específica:**
```
GET /api/verified-tutors?subject_id=10
```

### **6. Filtros de Calidad:**
```
GET /api/verified-tutors?min_rating=4.5&min_courses=3
```

### **7. Búsqueda Completa:**
```
GET /api/verified-tutors?keyword=matemáticas&tutor_name=Ana&group_id=1&min_courses=2&min_rating=4.0&page=1
```

## 📊 **Respuesta de la API:**

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
          }
        ],
        "subjects": [
          {
            "id": 1,
            "name": "Matemáticas",
            "hour_rate": 30.00,
            "sessions": 45
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

## 🔄 **Diferencias con find-tutors:**

| Característica | find-tutors | verified-tutors |
|----------------|-------------|-----------------|
| **Tutores verificados** | ✅ | ✅ |
| **Tutores con materias** | ✅ | ✅ (Obligatorio) |
| **Todos los filtros** | ✅ | ✅ |
| **Cursos completados** | ✅ | ✅ |
| **Paginación** | ✅ | ✅ |
| **Logging** | ✅ | ✅ |
| **Filtro subject_id** | ❌ | ✅ |

## 🛠️ **Ejemplos con cURL:**

### **Búsqueda Completa:**
```bash
curl -X GET "http://localhost/ClassGoweb/public/api/verified-tutors?keyword=matemáticas&tutor_name=María&group_id=1&min_courses=3&min_rating=4.5&page=1" \
  -H "Accept: application/json"
```

### **Con Autenticación:**
```bash
curl -X GET "http://localhost/ClassGoweb/public/api/verified-tutors?keyword=física&min_rating=4.0&min_courses=2" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

### **JavaScript/Fetch:**
```javascript
const params = new URLSearchParams({
  keyword: 'matemáticas',
  tutor_name: 'María',
  group_id: '1',
  subject_id: '5',
  min_courses: '3',
  min_rating: '4.5',
  page: '1'
});

fetch(`/api/verified-tutors?${params}`, {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'Authorization': 'Bearer TU_TOKEN_AQUI' // opcional
  }
})
.then(response => response.json())
.then(data => {
  console.log('Tutores verificados:', data.data.list);
  console.log('Paginación:', data.data.pagination);
});
```

## 🎯 **Casos de Uso Recomendados:**

### **Para Aplicación Móvil (Tutores de Calidad):**
```
GET /api/verified-tutors?min_rating=4.0&min_courses=2&page=1
```

### **Para Búsqueda Específica:**
```
GET /api/verified-tutors?subject_id=5&min_rating=4.5
```

### **Para Exploración por Categoría:**
```
GET /api/verified-tutors?group_id=1&min_courses=1
```

### **Para Búsqueda por Nombre:**
```
GET /api/verified-tutors?tutor_name=Juan&min_rating=3.5
```

## ⚡ **Ventajas de verified-tutors:**

1. **Calidad Garantizada**: Solo tutores verificados
2. **Materias Confirmadas**: Solo tutores con materias registradas
3. **Filtro Específico**: Puedes buscar por `subject_id` específico
4. **Todos los Filtros**: Incluye toda la funcionalidad de find-tutors
5. **Mejor Rendimiento**: Menos resultados = más rápido

## 🔧 **Logging:**

La API ahora incluye logging detallado:
- Parámetros recibidos
- Número de tutores encontrados
- Errores detallados

## 📝 **Notas Técnicas:**

- **Paginación**: 10 tutores por página
- **Ordenamiento**: Alfabético por nombre
- **Filtros**: Todos son opcionales
- **Compatibilidad**: Mantiene la estructura de respuesta de find-tutors
- **Performance**: Optimizada para tutores verificados 