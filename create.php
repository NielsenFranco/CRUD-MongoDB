<?php
require 'config.php';

if ($_POST) {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $isbn = $_POST['isbn'];
    $año = (int)$_POST['año'];
    $genero = $_POST['genero'];
    $descripcion = $_POST['descripcion'];

    // Validación básica
    if (empty($titulo) || empty($autor)) {
        $error = "Título y autor son obligatorios.";
    } else {
        $result = $collection->insertOne([
            'titulo' => $titulo,
            'autor' => $autor,
            'isbn' => $isbn,
            'año' => $año,
            'genero' => $genero,
            'descripcion' => $descripcion
        ]);
        if ($result->getInsertedCount() > 0) {
            header('Location: index.php?success=Libro agregado');
            exit;
        } else {
            $error = "Error al agregar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Libro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Agregar Nuevo Libro</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Título: <input type="text" name="titulo" required></label><br>
        <label>Autor: <input type="text" name="autor" required></label><br>
        <label>ISBN: <input type="text" name="isbn"></label><br>
        <label>Año: <input type="number" name="año" min="0"></label><br>
        <label>Género: <select name="genero"><option value="Novela">Novela</option><option value="Ciencia Ficción">Ciencia Ficción</option><option value="Otros">Otros</option></select></label><br>
        <label>Descripción: <textarea name="descripcion" rows="4"></textarea></label><br>
        <button type="submit">Agregar Libro</button>
        <a href="index.php"><button type="button">Cancelar</button></a>
    </form>
</body>
</html>
