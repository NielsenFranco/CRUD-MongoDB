<?php
require 'config.php';
// Lista inicial (búsqueda se hace vía JS)
$books = $collection->find([], ['limit' => 50])->toArray();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Libros</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gestión de Libros</h1>
    <?php if (isset($_GET['success'])) echo "<p style='color:green;'>{$_GET['success']}</p>"; ?>
    
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Buscar por título o autor..." oninput="searchBooks()">
    </div>
    
    <a href="create.php"><button>Agregar Libro</button></a>
    
    <table id="booksTable">
        <thead>
            <tr><th>Título</th><th>Autor</th><th>Año</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['titulo']) ?></td>
                <td><?= htmlspecialchars($book['autor']) ?></td>
                <td><?= $book['año'] ?? 'N/A' ?></td>
                <td>
                    <button class="edit-btn" onclick="editBook('<?= $book['_id'] ?>')">Editar</button>
                    <button class="delete-btn" onclick="deleteBook('<?= $book['_id'] ?>')">Eliminar</button>
                    <a href="details.php?id=<?= $book['_id'] ?>">Detalles</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <script src="script.js"></script>
</body>
</html>
