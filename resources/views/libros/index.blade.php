<!DOCTYPE html>
<html>
<head>
    <title>Listado de Libros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <livewire:navigation-menu />

    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Listado de Libros</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="num-libros">
                    <span style="font-weight:bold">TOTAL LIBROS:</span> {{ $cantidadLibros }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Páginas</th>
                            <th>ISBN</th>
                            <th>Portada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($libros as $libro)
                            <tr>
                                <td>{{ $libro->titulo }}</td>
                                <td>{{ $libro->autor }}</td>
                                <td>{{ $libro->editorial }}</td>
                                <td>{{ $libro->paginas }}</td>
                                <td>{{ $libro->isbn }}</td>
                                <td>
                                    <img src="{{ $libro->portada }}" alt="Portada" class="img-thumbnail img-fluid">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal" data-id="{{ $libro->id }}">Eliminar</button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit-libro-modal" data-id="{{ $libro->id }}">Editar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-libro-modal">Añadir Libro</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de borrado -->
    <!-- Modal para añadir nuevo libro -->
    <!-- Modal para editar libro -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
