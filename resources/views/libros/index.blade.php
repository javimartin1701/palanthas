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
                                        <img src="/public/portadas/portadas/{{ $libro->portada }}" alt="Portada" width="50" class="img-thumbnail">
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
                    <form id="delete-form" action="{{ route('libros.destroy', $libro->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>

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
                            
                            <!-- Nuevo botón para escanear código de barras -->
                            <button type="button" class="btn btn-primary" id="scan-barcode-btn">Escanear Código de Barras</button>    
                        
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
    
    <!-- Agrega el script para el escaneo de ISBN -->
    <script>
        function scanISBN() {
            // Verificar si el dispositivo es móvil
            if (/Mobi|Android/i.test(navigator.userAgent)) {
                // Solicitar permiso para acceder a la cámara
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                        .then(function(stream) {
                            // Creamos un elemento de video para mostrar el escaneo
                            const video = document.createElement('video');
                            document.body.appendChild(video);
                            video.srcObject = stream;
                            video.play();

                            // Creamos un elemento canvas para capturar el escaneo
                            const canvas = document.createElement('canvas');
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            const context = canvas.getContext('2d');

                            // Capturamos la imagen del video en el canvas
                            video.addEventListener('canplay', function() {
                                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                                stream.getTracks().forEach(function(track) {
                                    track.stop();
                                });

                                // Convertimos el canvas a una imagen base64
                                const imageData = canvas.toDataURL('image/png');
                                // Enviamos la imagen base64 al servidor para el procesamiento del código de barras
                                fetch('/api/procesar_codigo_barras', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    },
                                    body: JSON.stringify({ image_data: imageData }),
                                })
                                .then(function(response) {
                                    return response.json();
                                })
                                .then(function(data) {
                                    // Obtenemos el ISBN del resultado de la API
                                    const isbn = data.isbn;
                                    // Insertamos el ISBN en el campo del formulario
                                    document.getElementById('isbn').value = isbn.replace(/\s/g, '');
                                    // Eliminamos el elemento video y canvas creados
                                    document.body.removeChild(video);
                                    document.body.removeChild(canvas);
                                })
                                .catch(function(error) {
                                    console.error('Error al procesar el código de barras:', error);
                                    // Eliminamos el elemento video y canvas creados
                                    document.body.removeChild(video);
                                    document.body.removeChild(canvas);
                                });
                            }, false);
                        })
                        .catch(function(error) {
                            console.error('Error al acceder a la cámara:', error);
                        });
                } else {
                    alert('El escaneo de códigos de barras no está soportado en este dispositivo.');
                }
            } else {
                alert('El escaneo de códigos de barras solo está disponible en dispositivos móviles.');
            }
        }
    </script>

</body>
</html>




<!-- Agrega el script principal -->
<!-- Agrega el script de QuaggaJS desde una CDN -->
<script src="https://cdn.jsdelivr.net/npm/quagga@latest/dist/quagga.min.js"></script>


<!-- Script para configurar QuaggaJS -->
<script>
    // Función para manejar el resultado del escaneo
    function onScanSuccess(result) {
        const isbnInput = document.getElementById('isbn_buscar');
        if (isbnInput) {
            isbnInput.value = result.codeResult.code;
        }
    }

    // Función para manejar el error del escaneo
    function onScanError(error) {
        console.error('Error al escanear:', error);
    }

    // Configuración de QuaggaJS
    Quagga.init({
        inputStream: {
            name: 'Live',
            type: 'LiveStream',
            target: document.querySelector('#scan-barcode-btn'), // Elemento del botón para activar el escaneo
            constraints: {
                facingMode: 'environment', // Utiliza la cámara trasera del dispositivo (puedes cambiar a 'user' para la cámara frontal)
            },
        },
        decoder: {
            readers: ['ean_reader'], // Permite escanear códigos de barras EAN
        },
    }, function (err) {
        if (err) {
            console.error('Error al inicializar Quagga:', err);
            return;
        }
        console.log('Quagga inicializado correctamente.');
        Quagga.start(); // Inicia el escaneo de códigos de barras
    });

    // Evento para detener el escaneo cuando se cierre el modal
    $('#add-libro-modal').on('hidden.bs.modal', function () {
        Quagga.stop();
    });
</script>
