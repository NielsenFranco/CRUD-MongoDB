// Búsqueda en tiempo real
function searchBooks() {
    const query = document.getElementById('searchInput').value;
    fetch('search.php?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#booksTable tbody');
            tbody.innerHTML = '';
            data.forEach(book => {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td>${book.titulo}</td>
                    <td>${book.autor}</td>
                    <td>${book.año}</td>
                    <td>
                        <button class="edit-btn" onclick="editBook('${book._id}')">Editar</button>
                        <button class="delete-btn" onclick="deleteBook('${book._id}')">Eliminar</button>
                        <a href="details.php?id=${book._id}">Detalles</a>
                    </td>
                `;
            });
        });
}
// Confirmación para eliminar
function deleteBook(id) {
    if (confirm('¿Estás seguro de eliminar este libro?')) {
        window.location.href = 'delete.php?id=' + id;
    }
}
// Redirigir a editar
function editBook(id) {
    window.location.href = 'update.php?id=' + id;
}
