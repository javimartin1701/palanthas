<!-- resources/views/libros/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Palanthas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Agrega los enlaces a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <link rel="icon" type="image/png" sizes="16x16"  href="/img/favicon-16x16.png">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
    <livewire:navigation-menu />

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="titulo">Mis Libros</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form id="ordenarForm" action="{{ route('libros.index') }}" method="GET" class="form-inline mb-3">
                    <label for="ordenarPor" class="mr-2">Ordenar por:</label>
                    <select name="ordenarPor" id="ordenarPor" class="form-control mr-2">
                        <option value="titulo" {{ request('ordenarPor', 'titulo') === 'titulo' ? 'selected' : '' }}>Título</option>
                        <option value="autor" {{ request('ordenarPor') === 'autor' ? 'selected' : '' }}>Autor</option>
                        <option value="editorial" {{ request('ordenarPor') === 'editorial' ? 'selected' : '' }}>Editorial</option>
                    </select>

                    <a href="{{ route('libros.exportar') }}" class="btn btn-success">Exportar CSV</a>

                </form>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="num-libros">
                    <span style="font-weight:bold">TOTAL LIBROS:</span> {{ $cantidadLibros }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
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
                                        <img src="/public/portadas/{{ $libro->portada }}" alt="Portada" width="50" class="img-thumbnail">
                                    </td>
                                    <td>
                                        <!-- Botón para abrir el modal de confirmación de borrado -->
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal" data-id="{{ $libro->id }}">Eliminar</button>
    
                                        <!-- Botón de edición -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit-libro-modal" data-id="{{ $libro->id }}">Editar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Botón para añadir nuevo libro -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-libro-modal">Añadir Libro</button>
            </div>
        </div>
    </div>
    
    
   

    <!-- Modal de confirmación de borrado -->
<div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-delete-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-delete-modal-label">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este libro?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <!-- Dentro del foreach de la tabla -->
                @if (isset($bookToDelete))
                    <form id="delete-form" action="{{ route('libros.destroy', $bookToDelete->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>


    <!-- Modal para añadir nuevo libro -->
    <div class="modal fade" id="add-libro-modal" tabindex="-1" role="dialog" aria-labelledby="add-libro-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-libro-modal-label">Añadir Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-crear">
                    <form action="{{ route('libros.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="titulo">Título:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="form-group">
                            <label for="autor">Autor:</label>
                            <input type="text" class="form-control" id="autor" name="autor" required>
                        </div>
                        <div class="form-group">
                            <label for="editorial">Editorial:</label>
                            <input type="text" class="form-control" id="editorial" name="editorial" required>
                        </div>
                        <div class="form-group">
                            <label for="paginas">Páginas:</label>
                            <input type="number" class="form-control" id="paginas" name="paginas" required>
                        </div>
                        <div class="form-group">
                            <label for="paginas">ISBN:</label>
                            <input type="text" class="form-control" id="isbn" name="isbn">
                        </div>
                        <div id="drop-area">
                            <p>Haz click aquí para subir la portada del libro</p>
                            <input style="display:none" type="file" id="portada" name="portada" onchange="mostrarVistaPreviaPortada()" accept="image/*">
                            <img id="preview-image" src="#" alt="Vista previa" style="display: none;">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                    <div class="caja_busqueda_api">
                        <form id="buscar-form" action="{{ route('libros.buscargoogle') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="isbn_buscar">Buscar en OpenLibrary por ISBN:</label>
                                <input type="text" class="form-control" id="isbn_buscar" name="isbn" required>
                            </div>
                            <button type="submit" class="btn btn-success">Buscar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Editar libros -->
    <div class="modal fade" id="edit-libro-modal" tabindex="-1" role="dialog" aria-labelledby="edit-libro-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-libro-modal-label">Editar Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de edición -->
                    <form method="POST" action="{{ route('libros.update', '__id') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Campos del formulario para editar los datos del libro -->
                        <div class="form-group">
                            <label for="titulo">Título:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="form-group">
                            <label for="autor">Autor:</label>
                            <input type="text" class="form-control" id="autor" name="autor" required>
                        </div>
                        <div class="form-group">
                            <label for="editorial">Editorial:</label>
                            <input type="text" class="form-control" id="editorial" name="editorial" required>
                        </div>
                        <div class="form-group">
                            <label for="paginas">Páginas:</label>
                            <input type="number" class="form-control" id="paginas" name="paginas" required>
                        </div>
                        <div class="form-group">
                            <label for="paginas">ISBN:</label>
                            <input type="text" class="form-control" id="isbn" name="isbn">
                        </div>
                        <div id="drop-area">
                            <p>Haz click aquí para subir la portada del libro</p>
                            <input style="display:none" type="file" id="portada" name="portada" onchange="mostrarVistaPreviaPortada()" accept="image/*">
                            <img id="preview-image" src="#" alt="Vista previa" style="display: none;">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Agrega los scripts de Bootstrap y jQuery -->
    <!-- Agrega la biblioteca completa de jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- Agrega el script principal -->
    <script src="{{ asset('js/scripts.js') }}"></script>

</body>
</html>
