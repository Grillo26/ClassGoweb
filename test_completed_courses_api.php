<?php
/**
 * Script de prueba para verificar que la API find-tutors devuelve el conteo de cursos completados
 * 
 * Instrucciones:
 * 1. Guarda el archivo como test_completed_courses_api.php
 * 2. Ejecuta: php test_completed_courses_api.php
 */

// Configuración
$baseUrl = 'http://localhost/ClassGoweb/public/api'; // Ajusta según tu configuración

function makeRequest($url, $params = []) {
    $fullUrl = $url;
    if (!empty($params)) {
        $fullUrl .= '?' . http_build_query($params);
    }
    
    echo "🌐 Probando: $fullUrl\n";
    
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
                echo "📋 Número de tutores encontrados: " . count($data['data']['list']) . "\n";
                
                // Verificar que cada tutor tenga el campo completed_courses_count
                $tutorsWithCompletedCourses = 0;
                foreach ($data['data']['list'] as $index => $tutor) {
                    if (isset($tutor['completed_courses_count'])) {
                        $tutorsWithCompletedCourses++;
                        if ($index < 3) { // Mostrar solo los primeros 3
                            echo "   👨‍🏫 Tutor " . ($index + 1) . ": " . 
                                 ($tutor['profile']['first_name'] ?? 'N/A') . " " . 
                                 ($tutor['profile']['last_name'] ?? 'N/A') . 
                                 " - Cursos completados: " . $tutor['completed_courses_count'] . "\n";
                        }
                    }
                }
                
                echo "✅ Tutores con campo completed_courses_count: $tutorsWithCompletedCourses/" . count($data['data']['list']) . "\n";
                
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

echo "=== PRUEBAS DE LA API FIND-TUTORS CON CURSOS COMPLETADOS ===\n\n";

// Prueba 1: Búsqueda básica
echo "🔍 PRUEBA 1: Búsqueda básica\n";
makeRequest($baseUrl . '/find-tutors');

// Prueba 2: Con filtro de cursos mínimos
echo "🔍 PRUEBA 2: Con filtro de cursos mínimos\n";
makeRequest($baseUrl . '/find-tutors', [
    'min_courses' => 1
]);

// Prueba 3: Con filtro de rating mínimo
echo "🔍 PRUEBA 3: Con filtro de rating mínimo\n";
makeRequest($baseUrl . '/find-tutors', [
    'min_rating' => 4.0
]);

// Prueba 4: Con búsqueda por keyword
echo "🔍 PRUEBA 4: Con búsqueda por keyword\n";
makeRequest($baseUrl . '/find-tutors', [
    'keyword' => 'matemáticas'
]);

// Prueba 5: Con paginación
echo "🔍 PRUEBA 5: Con paginación\n";
makeRequest($baseUrl . '/find-tutors', [
    'page' => 2
]);

echo "✅ Todas las pruebas completadas\n";
echo "📝 Verifica que todos los tutores devueltos tengan el campo 'completed_courses_count'\n"; 