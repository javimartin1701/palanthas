<!-- resources/views/libros/create.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Añadir Libro</title>
</head>
<body>
    <h1>Añadir Libro</h1>

    <form method="POST" action="{{ route('libros.store') }}" enctype="multipart/form-data">
        @csrf

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" required>

        <label for="editorial">Editorial:</label>
        <input type="text" id="editorial" name="editorial" required>

        <label for="paginas">Páginas:</label>
        <input type="number" id="paginas" name="paginas" required>

        <label for="portada">Portada:</label>
        <input type="file" id="portada" name="portada" accept="image/*" required>

        <button type="submit">Agregar Libro</button>
    </form>
</body>
</html>
