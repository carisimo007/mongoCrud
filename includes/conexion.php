<?php
// Opción 1: Usar __DIR__ para ruta absoluta
require_once __DIR__ . '/../vendor/autoload.php';

// Opción 2: Si no funciona, prueba con ruta diferente
// require_once dirname(__DIR__) . '/vendor/autoload.php';

use MongoDB\Client;

try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->selectDatabase("crud");
    $librosCollection = $db->libros;
    echo "✅ Conexión exitosa a MongoDB";
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>