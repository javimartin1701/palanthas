// scripts.js

// Función para la vista previa de la portada
function mostrarVistaPreviaPortada() {
    // Obtiene el input de tipo "file" para la portada
    var portadaInput = document.getElementById('portada');

    // Verifica si se ha seleccionado un archivo
    if (portadaInput.files && portadaInput.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            // Obtiene la URL de la imagen cargada
            var imgURL = e.target.result;

            // Muestra la imagen en la vista previa
            var imgPreview = document.getElementById('portada-preview');
            imgPreview.src = imgURL;
            imgPreview.style.display = 'block';
        }

        reader.readAsDataURL(portadaInput.files[0]);
    }
}



 


var csrfToken = $('meta[name="csrf-token"]').attr('content');






$('#buscar-form').submit(function(event) {
    event.preventDefault(); // Evita el envío del formulario por defecto
    isbn = $('#isbn_buscar').val();
    conectarApiLibrosGoogle(isbn);
    conectarApiOpenLibrary(isbn);
  });


  function conectarApiLibrosGoogle(isbn){
    var miVariable = isbn;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
  
    $.ajax({
      url: '/buscargoogle',
      type: 'POST',
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      data: {
        miVariable: miVariable
      },
      success: function(response) {
        var datosLibro = response.variableRespuesta;
  
        console.log(datosLibro.items);

        $('.modal-crear #autor').val(datosLibro.items[0].volumeInfo.authors);
       
        
        $('.modal-crear #isbn').val(miVariable);
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });
  }


  function conectarApiOpenLibrary(isbn){
    var miVariable = isbn;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
  
    $.ajax({
      url: '/buscar',
      type: 'POST',
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      data: {
        miVariable: miVariable
      },
      success: function(response) {
        var datosLibro = response.variableRespuesta;
  
        console.log(datosLibro);

        $('.modal-crear #titulo').val(datosLibro.title);
        $('.modal-crear #autor').val(datosLibro.authors[0].name);
        $('.modal-crear #paginas').val(datosLibro.number_of_pages);
        $('.modal-crear #editorial').val(datosLibro.publishers[0]);
        
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });
  }
  


// Obtén los elementos del DOM
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('portada');
const previewImage = document.getElementById('preview-image');

// Previene el comportamiento por defecto del arrastrar y soltar
dropArea.addEventListener('dragover', function(event) {
  event.preventDefault();
  dropArea.classList.add('highlight');
});

// Controla el evento de soltar
dropArea.addEventListener('drop', function(event) {
  event.preventDefault();
  dropArea.classList.remove('highlight');
  const file = event.dataTransfer.files[0];
  previewFile(file);
});

// Controla el evento de clic en la zona de arrastre
dropArea.addEventListener('click', function() {
  fileInput.click();
});

// Controla el evento de cambio de archivo seleccionado
fileInput.addEventListener('change', function(event) {
  const file = event.target.files[0];
  previewFile(file);
});

// Función para mostrar la vista previa de la imagen
function previewFile(file) {
  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onloadend = function() {
    previewImage.src = reader.result;
    previewImage.style.display = 'block';
  };
}


$(document).ready(function() {
    $('#edit-libro-modal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var libroId = button.data('id');

        // Realiza una petición AJAX para obtener los datos del libro
        $.ajax({
            url: '/libros/' + libroId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Rellena los campos del formulario con los datos del libro
                $('#edit-libro-modal form').attr('action', '/libros/' + libroId);
                $('#edit-libro-modal [name="titulo"]').val(data.titulo);
                $('#edit-libro-modal [name="autor"]').val(data.autor);
                $('#edit-libro-modal [name="editorial"]').val(data.editorial);
                $('#edit-libro-modal [name="paginas"]').val(data.paginas);
                $('#edit-libro-modal [name="isbn"]').val(data.isbn);
            }
        });
    });
});


