<?php
// Activa errores para debug (temporal)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
use MongoDB\Client;

echo "<h2>Seed Mejorado: Agregando libros de prueba (sin borrar existentes)</h2>";

// Paso 1: Conexión
try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->biblioteca;
    $collection = $db->libros;
    echo "<p style='color: green;'>✓ Conexión exitosa.</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error de conexión: " . $e->getMessage() . "</p>";
    die();
}

// Paso 2: Estado actual
$countAntes = $collection->countDocuments();
echo "<p><strong>Estado antes:</strong> Hay $countAntes libros.</p>";
if ($countAntes > 0) {
    echo "<p>Libros actuales:</p><ul>";
    $existing = $collection->find([], ['limit' => 5])->toArray();
    foreach ($existing as $doc) {
        echo "<li>" . htmlspecialchars($doc['titulo'] ?? 'Sin título') . " por " . htmlspecialchars($doc['autor'] ?? 'Sin autor') . "</li>";
    }
    echo "</ul>";
}

// Paso 3: Datos nuevos (8 libros + El Quijote si no existe, pero como ya hay 1, solo agrega los 8)
$nuevosLibros = [
    ['titulo' => 'Cien años de soledad', 'autor' => 'Gabriel García Márquez', 'isbn' => '978-0307474728', 'año' => 1967, 'genero' => 'Realismo mágico', 'editorial' => 'Editorial Sudamericana'],
    ['titulo' => '1984', 'autor' => 'George Orwell', 'isbn' => '978-0451524935', 'año' => 1949, 'genero' => 'Distopía', 'editorial' => 'Secker & Warburg'],
    ['titulo' => 'El gran Gatsby', 'autor' => 'F. Scott Fitzgerald', 'isbn' => '978-0743273565', 'año' => 1925, 'genero' => 'Novela clásica', 'editorial' => 'Scribner'],
    ['titulo' => 'Sapiens: De animales a dioses', 'autor' => 'Yuval Noah Harari', 'isbn' => '978-0062316097', 'año' => 2011, 'genero' => 'No ficción / Historia', 'editorial' => 'Harper'],
    ['titulo' => 'Harry Potter y la piedra filosofal', 'autor' => 'J.K. Rowling', 'isbn' => '978-8478884452', 'año' => 1997, 'genero' => 'Fantasía juvenil', 'editorial' => 'Salamandra'],
    ['titulo' => 'El nombre del viento', 'autor' => 'Patrick Rothfuss', 'isbn' => '978-8401339113', 'año' => 2007, 'genero' => 'Fantasía épica', 'editorial' => 'Minotauro'],
    ['titulo' => 'Orgullo y prejuicio', 'autor' => 'Jane Austen', 'isbn' => '978-0141439518', 'año' => 1813, 'genero' => 'Novela romántica', 'editorial' => 'Penguin Classics'],
    ['titulo' => 'El alquimista', 'autor' => 'Paulo Coelho', 'isbn' => '978-0062315007', 'año' => 1988, 'genero' => 'Ficción espiritual', 'editorial' => 'HarperOne']
];

// Verifica si ya existen (por título, para evitar duplicados exactos)
$titulosExistentes = [];
foreach ($collection->find(['titulo' => ['$in' => array_column($nuevosLibros, 'titulo')]] )->toArray() as $doc) {
    $titulosExistentes[] = $doc['titulo'];
}

$librosAAgregar = [];
foreach ($nuevosLibros as $libro) {
    if (!in_array($libro['titulo'], $titulosExistentes)) {
        $librosAAgregar[] = $libro;
    }
}

if (!empty($librosAAgregar)) {
    try {
        echo "<p><strong>Agregando " . count($librosAAgregar) . " libros nuevos (evitando duplicados)...</strong></p>";
        $result = $collection->insertMany($librosAAgregar);
        echo "<p style='color: green;'>✓ ¡Agregados exitosamente! IDs: " . implode(', ', array_keys($result->getInsertedIds())) . "</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error al agregar: " . $e->getMessage() . "</p>";
        die();
    }
} else {
    echo "<p><strong>No se agregó nada:</strong> Todos los libros ya existen (o el único es duplicado).</p>";
}

// Paso 4: Estado final
$countDespues = $collection->countDocuments();
echo "<p><strong>Estado después:</strong> Ahora hay $countDespues libros (agregados: " . ($countDespues - $countAntes) . ").</p>";

// Muestra todos los libros ahora
echo "<p><strong>Todos los libros ahora:</strong></p><ul>";
$allLibros = $collection->find([], ['sort' => ['titulo' => 1]])->toArray();
foreach ($allLibros as $libro) {
    echo "<li><strong>" . htmlspecialchars($libro['titulo']) . "</strong> por " . htmlspecialchars($libro['autor']) . 
         " (Año: " . ($libro['año'] ?? 'N/A') . ", ID: " . $libro['_id'] . ")</li>";
}
echo "</ul>";

// Enlaces
echo "<hr><p><a href='index.php'>Volver a index.php (ver lista completa)</a> | ";
echo "<a href='seed.php?force=1'>Forzar: Borrar todo y re-agregar 8 nuevos</a> | ";
echo "<a href='test.php'>Probar conexión</a></p>";
?>
