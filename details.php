<?php
require 'vendor/autoload.php';
use MongoDB\Client;
use MongoDB\BSON\ObjectId;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID no proporcionado. <a href='index.php'>Volver</a>");
}

try {
    $client = new Client("mongodb://localhost:27017");
    $collection = $client->biblioteca->libros;
    
    // Convierte ID a ObjectId (resuelve el warning)
    $id = new ObjectId($_GET['id']);
    $libro = $collection->findOne(['_id' => $id]);
    
    if (!$libro) {
        die("Libro no encontrado. <a href='index.php'>Volver</a>");
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . ". <a href='index.php'>Volver</a>");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Detalles del Libro</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> <?php echo $libro['_id']; ?></p>
            <p><strong>Título:</strong> <?php echo htmlspecialchars($libro['titulo'] ?? 'N/A'); ?></p>
            <p><strong>Autor:</strong> <?php echo htmlspecialchars($libro['autor'] ?? 'N/A'); ?></p>
            <p><strong>ISBN:</strong> <?php echo htmlspecialchars($libro['isbn'] ?? 'N/A'); ?></p>
            <p><strong>Año:</strong> <?php echo htmlspecialchars($libro['año'] ?? 'N/A'); ?></p>
            <p><strong>Género:</strong> <?php echo htmlspecialchars($libro['genero'] ?? 'N/A'); ?></p>
            <p><strong>Editorial:</strong> <?php echo htmlspecialchars($libro['editorial'] ?? 'N/A'); ?></p>
        </div>
    </div>
    <p class="mt-3">
        <a href="update.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary">Editar</a>
        <a href="delete.php?id=<?php echo $_GET['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar?');">Eliminar</a>
        <a href="index.php" class="btn btn-secondary">Volver a Lista</a>
    </p>
</body>
</html>
