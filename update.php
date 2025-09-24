<?php
require 'vendor/autoload.php';
use MongoDB\Client;
use MongoDB\BSON\ObjectId;

$id = $_GET['id'] ?? '';
if (empty($id)) {
    die("ID no proporcionado. <a href='index.php'>Volver</a>");
}

try {
    $client = new Client("mongodb://localhost:27017");
    $collection = $client->biblioteca->libros;
    $libroActual = $collection->findOne(['_id' => new ObjectId($id)]);
    if (!$libroActual) {
        die("Libro no encontrado. <a href='index.php'>Volver</a>");
    }
} catch (Exception $e) {
    die("Error al cargar: " . $e->getMessage());
}

if ($_POST) {
    $updateData = [
        'titulo' => $_POST['titulo'] ?? '',
        'autor' => $_POST['autor'] ?? '',
        'isbn' => $_POST['isbn'] ?? '',
        'año' => (int)($_POST['año'] ?? 0),
        'genero' => $_POST['genero'] ?? '',
        'editorial' => $_POST['editorial'] ?? ''
    ];
    
    try {
        $result = $collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $updateData]
        );
        if ($result->getModifiedCount() > 0) {
            echo "<div class='alert alert-success'>¡Libro actualizado!</div>";
            $libroActual = array_merge($libroActual, $updateData);  // Actualiza vista
        } else {
            echo "<div class='alert alert-warning'>No se modificó nada.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error al actualizar: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Editar Libro</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Título:</label>
            <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($libroActual['titulo'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Autor:</label>
            <input type="text" name="autor" class="form-control" value="<?php echo htmlspecialchars($libroActual['autor'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">ISBN:</label>
            <input type="text" name="isbn" class="form-control" value="<?php echo htmlspecialchars($libroActual['isbn'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Año:</label>
            <input type="number" name="año" class="form-control" value="<?php echo htmlspecialchars($libroActual['año'] ?? ''); ?>" min="1000" max="2025">
        </div>
        <div class="mb-3">
            <label class="form-label">Género:</label>
            <input type="text" name="genero" class="form-control" value="<?php echo htmlspecialchars($libroActual['genero'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Editorial:</label>
            <input type="text" name="editorial" class="form-control" value="<?php echo htmlspecialchars($libroActual['editorial'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="details.php?id=<?php echo $id; ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
