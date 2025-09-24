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
    
    $result = $collection->deleteOne(['_id' => new ObjectId($_GET['id'])]);
    
    if ($result->getDeletedCount() > 0) {
        $mensaje = "¡Libro eliminado exitosamente!";
        $clase = "success";
    } else {
        $mensaje = "No se eliminó nada (ID inválido o no existe).";
        $clase = "warning";
    }
} catch (Exception $e) {
    $mensaje = "Error al eliminar: " . $e->getMessage();
    $clase = "danger";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Resultado de Eliminación</h2>
    <div class="alert alert-<?php echo $clase; ?>"><?php echo $mensaje; ?></div>
    <p><a href="index.php" class="btn btn-primary">Volver a Lista</a></p>
</body>
</html>
