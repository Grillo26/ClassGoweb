<?php
/**
 * Script de prueba para la API verified-tutors actualizada
 * 
 * Instrucciones:
 * 1. Guarda el archivo como test_verified_tutors_api.php
 * 2. Ejecuta: php test_verified_tutors_api.php
 */

// Configuración
$baseUrl = 'http://localhost/ClassGoweb/public/api'; // Ajusta según tu configuración

function makeRequest($url, $params = [], $description = '') {
    $fullUrl = $url;
    if (!empty($params)) {
        $fullUrl .= '?' . http_build_query($params);
    }
    
    echo "🌐 Probando: $fullUrl\n";
    if ($description) {
        echo "📝 Descripción: $description\n";
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "📊 Código de respuesta: $httpCode\n";
    
    if ($response) {
        $data = json_decode($response, true);
        if ($data) {
            echo "✅ Respuesta válida JSON\n";
            
            // Verificar estructura de respuesta
            if (isset($data['data']['list']) && is_array($data['data']['list'])) {
                echo "📋 Número de tutores verificados encontrados: " . count($data['data']['list']) . "\n";
                
                // Verificar que cada tutor tenga el campo completed_courses_count
                $tutorsWithCompletedCourses = 0;
                $tutorsWithSubjects = 0;
                foreach ($data['data']['list'] as $index => $tutor) {
                    if (isset($tutor['completed_courses_count'])) {
                        $tutorsWithCompletedCourses++;
                    }
                    if (isset($tutor['subjects']) && !empty($tutor['subjects'])) {
                        $tutorsWithSubjects++;
                    }
                    if ($index < 3) { // Mostrar solo los primeros 3
                        echo "   👨‍🏫 Tutor " . ($index + 1) . ": " . 
                             ($tutor['profile']['first_name'] ?? 'N/A') . " " . 
                             ($tutor['profile']['last_name'] ?? 'N/A') . 
                             " - Cursos completados: " . ($tutor['completed_courses_count'] ?? 0) . 
                             " - Materias: " . count($tutor['subjects'] ?? []) . "\n";
                    }
                }
                
                echo "✅ Tutores con campo completed_courses_count: $tutorsWithCompletedCourses/" . count($data['data']['list']) . "\n";
                echo "✅ Tutores con materias registradas: $tutorsWithSubjects/" . count($data['data']['list']) . "\n";
                
                // Verificar paginación
                if (isset($data['data']['pagination'])) {
                    echo "📄 Paginación:\n";
                    echo "   - Total: " . ($data['data']['pagination']['total'] ?? 'N/A') . "\n";
                    echo "   - Página actual: " . ($data['data']['pagination']['currentPage'] ?? 'N/A') . "\n";
                    echo "   - Por página: " . ($data['data']['pagination']['perPage'] ?? 'N/A') . "\n";
                }
            } else {
                echo "❌ Estructura de respuesta inesperada\n";
                echo "📄 Respuesta: " . substr($response, 0, 500) . "...\n";
            }
        } else {
            echo "❌ Respuesta no es JSON válido\n";
            echo "📄 Respuesta: " . substr($response, 0, 500) . "...\n";
        }
    } else {
        echo "❌ Sin respuesta del servidor\n";
    }
    
    echo "\n" . str_repeat("-", 80) . "\n\n";
}

echo "=== PRUEBAS DE LA API VERIFIED-TUTORS ACTUALIZADA ===\n\n";

// Prueba 1: Búsqueda básica (solo tutores verificados con materias)
echo "🔍 PRUEBA 1: Búsqueda básica (solo tutores verificados con materias)\n";
makeRequest($baseUrl . '/verified-tutors', [], 'Debe mostrar solo tutores verificados con materias registradas');

// Prueba 2: Con filtro de cursos mínimos
echo "🔍 PRUEBA 2: Con filtro de cursos mínimos\n";
makeRequest($baseUrl . '/verified-tutors', [
    'min_courses' => 1
], 'Debe mostrar tutores verificados con al menos 1 curso completado');

// Prueba 3: Con filtro de rating mínimo
echo "🔍 PRUEBA 3: Con filtro de rating mínimo\n";
makeRequest($baseUrl . '/verified-tutors', [
    'min_rating' => 4.0
], 'Debe mostrar tutores verificados con rating >= 4.0');

// Prueba 4: Con búsqueda por keyword
echo "🔍 PRUEBA 4: Con búsqueda por keyword\n";
makeRequest($baseUrl . '/verified-tutors', [
    'keyword' => 'matemáticas'
], 'Debe mostrar tutores verificados que enseñen matemáticas');

// Prueba 5: Con búsqueda por nombre de tutor
echo "🔍 PRUEBA 5: Con búsqueda por nombre de tutor\n";
makeRequest($baseUrl . '/verified-tutors', [
    'tutor_name' => 'María'
], 'Debe mostrar tutores verificados llamados María');

// Prueba 6: Con filtro por categoría (group_id)
echo "🔍 PRUEBA 6: Con filtro por categoría (group_id)\n";
makeRequest($baseUrl . '/verified-tutors', [
    'group_id' => 1
], 'Debe mostrar tutores verificados de la categoría 1');

// Prueba 7: Con filtro por materia específica (subject_id)
echo "🔍 PRUEBA 7: Con filtro por materia específica (subject_id)\n";
makeRequest($baseUrl . '/verified-tutors', [
    'subject_id' => 1
], 'Debe mostrar tutores verificados que enseñen la materia ID 1');

// Prueba 8: Con paginación
echo "🔍 PRUEBA 8: Con paginación\n";
makeRequest($baseUrl . '/verified-tutors', [
    'page' => 2
], 'Debe mostrar la página 2 de tutores verificados');

// Prueba 9: Filtros combinados
echo "🔍 PRUEBA 9: Filtros combinados\n";
makeRequest($baseUrl . '/verified-tutors', [
    'keyword' => 'física',
    'min_rating' => 4.0,
    'min_courses' => 2,
    'page' => 1
], 'Debe mostrar tutores verificados de física con rating >= 4.0 y al menos 2 cursos completados');

// Prueba 10: Búsqueda completa con todos los parámetros
echo "🔍 PRUEBA 10: Búsqueda completa con todos los parámetros\n";
makeRequest($baseUrl . '/verified-tutors', [
    'keyword' => 'matemáticas',
    'tutor_name' => 'Ana',
    'group_id' => 1,
    'subject_id' => 5,
    'min_courses' => 3,
    'min_rating' => 4.5,
    'page' => 1
], 'Debe mostrar tutores verificados que cumplan TODOS los criterios especificados');

echo "✅ Todas las pruebas completadas\n";
echo "📝 Verifica que:\n";
echo "   - Todos los tutores devueltos tengan el campo 'completed_courses_count'\n";
echo "   - Todos los tutores tengan materias registradas\n";
echo "   - Los filtros funcionen correctamente\n";
echo "   - La paginación funcione\n";
echo "   - El filtro subject_id sea exclusivo de verified-tutors\n"; 