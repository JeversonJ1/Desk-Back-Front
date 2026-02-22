<?php
// Test script for API filtering
$baseUrl = "http://localhost:4000/api/pedidos";
$userId = 64; // Itachi's ID

function testApi($url) {
    echo "Testing URL: $url\n";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    if ($httpCode === 200) {
        $json = json_decode($response, true);
        if ($json['status'] === 'success') {
            echo "Success! Found " . count($json['data']) . " orders.\n";
            foreach ($json['data'] as $p) {
                echo "- Pedido #{$p['id_pedido']} (User ID: {$p['id_usuarios']})\n";
            }
        } else {
            echo "API returned error: " . $json['message'] . "\n";
        }
    } else {
        echo "Request failed.\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
    }
    echo "-------------------\n";
}

// Test 1: Vitrine (should work)
testApi("http://localhost:4000/api/vitrine");

// Test 2: Pedidos without filter
testApi($baseUrl);

// Test 3: Pedidos with filter for Itachi
testApi($baseUrl . "?id_usuarios=$userId");
