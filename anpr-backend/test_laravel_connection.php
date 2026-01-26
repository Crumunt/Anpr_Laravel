<?php
/**
 * Simple PHP script to test Laravel → Flask connection
 * 
 * Run this from Laravel project root:
 * php test_laravel_connection.php
 * 
 * Or create a route in Laravel to test:
 * Route::get('/test-flask', function() {
 *     return testFlaskConnection();
 * });
 */

function testFlaskConnection() {
    $flaskUrl = env('ANPR_API_URL', 'http://localhost:5000');
    $apiKey = env('ANPR_API_KEY', '');
    
    echo "Testing Flask Connection\n";
    echo "=======================\n";
    echo "Flask URL: {$flaskUrl}\n";
    echo "API Key: " . ($apiKey ? 'Set' : 'Not set') . "\n\n";
    
    // Test 1: Status endpoint
    echo "Test 1: GET /api/anpr/status\n";
    try {
        $response = file_get_contents("{$flaskUrl}/api/anpr/status");
        $data = json_decode($response, true);
        
        if ($data && isset($data['success'])) {
            echo "✓ SUCCESS: Flask is responding\n";
            echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
        } else {
            echo "✗ FAILED: Invalid response\n\n";
        }
    } catch (Exception $e) {
        echo "✗ ERROR: " . $e->getMessage() . "\n\n";
    }
    
    // Test 2: Cameras endpoint
    echo "Test 2: GET /api/anpr/cameras\n";
    try {
        $response = file_get_contents("{$flaskUrl}/api/anpr/cameras");
        $data = json_decode($response, true);
        
        if ($data && isset($data['success'])) {
            echo "✓ SUCCESS: Cameras endpoint working\n";
            echo "Cameras found: " . ($data['total'] ?? 0) . "\n\n";
        } else {
            echo "✗ FAILED: Invalid response\n\n";
        }
    } catch (Exception $e) {
        echo "✗ ERROR: " . $e->getMessage() . "\n\n";
    }
    
    echo "=======================\n";
    echo "Testing complete!\n";
}

// Run if executed directly
if (php_sapi_name() === 'cli') {
    // Load Laravel environment
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    testFlaskConnection();
}
