<?php
require 'vendor/autoload.php'; // Para Composer
use MongoDB\Client;
use MongoDB\Driver\Exception\AuthenticationException;

$uri = "mongodb://localhost:27017"; // Cambia si usas usuario/contraseña
try {
    $client = new Client($uri);
    $db = $client->biblioteca;
    $collection = $db->libros;
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
