<?php
require 'vendor/autoload.php'; // Si usas Composer
use MongoDB\Client;

try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->biblioteca;
    echo "Conexión exitosa a MongoDB!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
