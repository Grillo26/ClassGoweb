<?php
/**
 * Script de prueba para verificar que min_rating=0 funciona correctamente
 * 
 * Instrucciones:
 * 1. Guarda el archivo como test_min_rating_zero.php
 * 2. Ejecuta: php test_min_rating_zero.php
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
                echo "📋 Número de tutores encontrados: " . count($data['data']['list']) . "\n";
                
                // Analizar ratings de los tutores
                $tutorsWithRating = 0;
                $tutorsWithoutRating = 0;
                $ratings = [];
                
                foreach ($data['data']['list'] as $index => $tutor) {
                    if (isset($tutor['avg_rating']) && $tutor['avg_rating'] !== null) {
                        $tutorsWithRating++;
                        $ratings[] = $tutor['avg_rating'];
                    } else {
                        $tutorsWithoutRating++;
                    }
                    
                    if ($index < 5) { // Mostrar solo los primeros 5
                        echo "   👨‍🏫 Tutor " . ($index + 1) . ": " . 
                             ($tutor['profile']['first_name'] ?? 'N/A') . " " . 
                             ($tutor['profile']['last_name'] ?? 'N/A') . 
                             " - Rating: " . ($tutor['avg_rating'] ?? 'Sin rating') . "\n";
                    }
                }
                
                echo "✅ Tutores con rating: $tutorsWithRating\n";
                echo "✅ Tutores sin rating: $tutorsWithoutRating\n";
                
                if (!empty($ratings)) {
                    echo "📊 Estadísticas de ratings:\n";
                    echo "   - Rating mínimo: " . min($ratings) . "\n";
                    echo "   - Rating máximo: " . max($ratings) . "\n";
                    echo "   - Rating promedio: " . round(array_sum($ratings) / count($ratings), 2) . "\n";
                }
                
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

echo "=== PRUEBAS DE MIN_RATING=0 ===\n\n";

// Prueba 1: find-tutors sin filtro de rating
echo "🔍 PRUEBA 1: find-tutors sin filtro de rating\n";
makeRequest($baseUrl . '/find-tutors', [], 'Debe mostrar todos los tutores sin importar rating');

// Prueba 2: find-tutors con min_rating=0
echo "🔍 PRUEBA 2: find-tutors con min_rating=0\n";
makeRequest($baseUrl . '/find-tutors', [
    'min_rating' => 0
], 'Debe mostrar todos los tutores (incluyendo sin rating)');

// Prueba 3: find-tutors con min_rating=4.0
echo "🔍 PRUEBA 3: find-tutors con min_rating=4.0\n";
makeRequest($baseUrl . '/find-tutors', [
    'min_rating' => 4.0
], 'Debe mostrar solo tutores con rating >= 4.0');

// Prueba 4: verified-tutors sin filtro de rating
echo "🔍 PRUEBA 4: verified-tutors sin filtro de rating\n";
makeRequest($baseUrl . '/verified-tutors', [], 'Debe mostrar todos los tutores verificados sin importar rating');

// Prueba 5: verified-tutors con min_rating=0
echo "🔍 PRUEBA 5: verified-tutors con min_rating=0\n";
makeRequest($baseUrl . '/verified-tutors', [
    'min_rating' => 0
], 'Debe mostrar todos los tutores verificados (incluyendo sin rating)');

// Prueba 6: verified-tutors con min_rating=4.5
echo "🔍 PRUEBA 6: verified-tutors con min_rating=4.5\n";
makeRequest($baseUrl . '/verified-tutors', [
    'min_rating' => 4.5
], 'Debe mostrar solo tutores verificados con rating >= 4.5');

// Prueba 7: Comparación de resultados
echo "🔍 PRUEBA 7: Comparación de resultados\n";
echo "Comparando resultados entre min_rating=0 y sin filtro...\n";

// Hacer ambas peticiones y comparar
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/find-tutors');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
$response1 = curl_exec($ch);
curl_close($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/find-tutors?min_rating=0');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
$response2 = curl_exec($ch);
curl_close($ch);

$data1 = json_decode($response1, true);
$data2 = json_decode($response2, true);

if ($data1 && $data2) {
    $count1 = count($data1['data']['list'] ?? []);
    $count2 = count($data2['data']['list'] ?? []);
    
    echo "📊 Resultados:\n";
    echo "   - Sin filtro: $count1 tutores\n";
    echo "   - Con min_rating=0: $count2 tutores\n";
    
    if ($count1 === $count2) {
        echo "✅ Los resultados son idénticos - min_rating=0 funciona correctamente\n";
    } else {
        echo "❌ Los resultados son diferentes - hay un problema\n";
    }
}

echo "\n✅ Todas las pruebas completadas\n";
echo "📝 Verifica que:\n";
echo "   - min_rating=0 muestre TODOS los tutores (con y sin rating)\n";
echo "   - min_rating=4.0 solo muestre tutores con rating >= 4.0\n";
echo "   - Los resultados sean consistentes entre ambas APIs\n"; 