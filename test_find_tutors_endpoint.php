<?php

/**
 * Script de prueba para el endpoint find-tutors
 * 
 * Este archivo contiene ejemplos de cómo probar el endpoint con diferentes parámetros
 */

// URL base de la API
$baseUrl = 'https://classgoapp.com/api';

// Función para hacer peticiones HTTP
function makeRequest($url, $params = []) {
    $fullUrl = $url;
    if (!empty($params)) {
        $fullUrl .= '?' . http_build_query($params);
    }
    
    echo "Probando: $fullUrl\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Código HTTP: $httpCode\n";
    echo "Respuesta: " . substr($response, 0, 500) . "...\n";
    echo "----------------------------------------\n";
    
    return [
        'code' => $httpCode,
        'response' => $response
    ];
}

// Casos de prueba

echo "=== PRUEBAS DEL ENDPOINT FIND-TUTORS ===\n\n";

// 1. Búsqueda básica (sin parámetros)
echo "1. Búsqueda básica:\n";
makeRequest($baseUrl . '/find-tutors');

// 2. Búsqueda por nombre de tutor
echo "2. Búsqueda por nombre de tutor:\n";
makeRequest($baseUrl . '/find-tutors', [
    'tutor_name' => 'alvaro'
]);

// 3. Búsqueda por categoría de materia
echo "3. Búsqueda por categoría de materia:\n";
makeRequest($baseUrl . '/find-tutors', [
    'group_id' => 2
]);

// 4. Búsqueda con múltiples filtros
echo "4. Búsqueda con múltiples filtros:\n";
makeRequest($baseUrl . '/find-tutors', [
    'tutor_name' => 'alvaro',
    'group_id' => 2,
    'min_rating' => 4.5,
    'page' => 1
]);

// 5. Búsqueda por keyword (materia)
echo "5. Búsqueda por keyword (materia):\n";
makeRequest($baseUrl . '/find-tutors', [
    'keyword' => 'matemáticas'
]);

// 6. Búsqueda con filtro de cursos mínimos
echo "6. Búsqueda con filtro de cursos mínimos:\n";
makeRequest($baseUrl . '/find-tutors', [
    'min_courses' => 5
]);

// 7. Búsqueda con filtro de calificación mínima
echo "7. Búsqueda con filtro de calificación mínima:\n";
makeRequest($baseUrl . '/find-tutors', [
    'min_rating' => 4.0
]);

// 8. Búsqueda con paginación
echo "8. Búsqueda con paginación:\n";
makeRequest($baseUrl . '/find-tutors', [
    'page' => 2
]);

// 9. Búsqueda combinando todos los filtros
echo "9. Búsqueda combinando todos los filtros:\n";
makeRequest($baseUrl . '/find-tutors', [
    'keyword' => 'matemáticas',
    'tutor_name' => 'alvaro',
    'group_id' => 2,
    'min_courses' => 3,
    'min_rating' => 4.5,
    'page' => 1
]);

echo "\n=== FIN DE LAS PRUEBAS ===\n";

/**
 * Para ejecutar este script:
 * 1. Guarda el archivo como test_find_tutors_endpoint.php
 * 2. Ejecuta: php test_find_tutors_endpoint.php
 * 
 * Nota: Asegúrate de que el servidor esté funcionando y la URL base sea correcta
 */ 