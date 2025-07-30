# API para Cambiar Estado de Tutoría a "Cursando"

## Descripción
Esta API permite cambiar el estado de una tutoría específica a "Cursando" (ID: 6).

## Endpoint
```
POST /api/booking/change-to-cursando
```

## Autenticación
Esta API requiere autenticación. Debes incluir el token Bearer en el header de la petición.

## Parámetros

### Body (JSON)
```json
{
    "booking_id": 123
}
```

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| booking_id | integer | Sí | ID de la tutoría que se desea cambiar de estado |

## Respuesta Exitosa

### Status Code: 200
```json
{
    "success": true,
    "message": "Estado de la tutoría cambiado a \"Cursando\" exitosamente",
    "data": {
        "booking_id": 123,
        "status": "Cursando",
        "status_id": 6
    }
}
```

## Respuesta de Error

### Status Code: 422 (Validación)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "booking_id": [
            "The booking id field is required."
        ]
    }
}
```

### Status Code: 404 (Tutoría no encontrada)
```json
{
    "success": false,
    "message": "Error al cambiar el estado de la tutoría",
    "error": "No query results for model [App\\Models\\SlotBooking] 123"
}
```

### Status Code: 500 (Error del servidor)
```json
{
    "success": false,
    "message": "Error al cambiar el estado de la tutoría",
    "error": "Mensaje de error específico"
}
```

## Ejemplo de Uso

### cURL
```bash
curl -X POST \
  http://tu-dominio.com/api/booking/change-to-cursando \
  -H 'Authorization: Bearer TU_TOKEN_AQUI' \
  -H 'Content-Type: application/json' \
  -d '{
    "booking_id": 123
}'
```

### JavaScript (Fetch)
```javascript
fetch('/api/booking/change-to-cursando', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer TU_TOKEN_AQUI',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        booking_id: 123
    })
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

### PHP
```php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://tu-dominio.com/api/booking/change-to-cursando',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode([
        'booking_id' => 123
    ]),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer TU_TOKEN_AQUI',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
```

## Estados Disponibles

Según el modelo `BookingStatus`, los estados disponibles son:

| Estado | ID |
|--------|----|
| Aceptado | 1 |
| Pendiente | 2 |
| No completado | 3 |
| Rechazado | 4 |
| Completado | 5 |
| **Cursando** | **6** |

## Notas Importantes

1. **Autenticación**: Esta API requiere que el usuario esté autenticado.
2. **Validación**: El `booking_id` debe existir en la tabla `slot_bookings`.
3. **Estado Fijo**: Esta API siempre cambia el estado a "Cursando" (ID: 6).
4. **Respuesta**: La API devuelve información sobre el cambio realizado, incluyendo el ID de la tutoría y el nuevo estado.

## Códigos de Estado HTTP

- `200`: Operación exitosa
- `422`: Error de validación
- `404`: Tutoría no encontrada
- `500`: Error interno del servidor 