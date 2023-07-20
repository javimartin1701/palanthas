<?php

namespace App\Http\Controllers;


use App\Models\Libro;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;



class LibroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
{
    // Obtener el usuario autenticado
    $user = auth()->user();

  // Obtener el tipo de orden seleccionado (por defecto, ordenar por título)
  $ordenarPor = $request->input('ordenarPor', 'titulo');

  // Obtener los libros asociados al usuario actual y ordenarlos según el tipo de ordenamiento
  $libros = $user->libros->sortBy($ordenarPor);
    
    $cantidadLibros = $libros->count();

    if ($libros->isEmpty()) {
        // No hay libros en el listado
        // Realiza alguna acción o muestra un mensaje indicando que no hay libros
        return view('libros.index', ['libros' => [], 'cantidadLibros' => $cantidadLibros]);
    }

    $isbn = $request->input('isbn');

    // Verifica si se proporcionó un ISBN
    if ($isbn) {
        $data = $this->getDataByIsbn($isbn);
    } else {
        $data = null;
    }

    return view('libros.index', compact('libros', 'data', 'cantidadLibros'));
}


    public function create()
    {
        return view('libros.create');
    }

    public function getDataByIsbn($isbn)
    {
        // $client = new \GuzzleHttp\Client();
        // $response = $client->get('https://openlibrary.org/api/books?bibkeys=ISBN:'.$isbn.'&jscmd=details&format=json');
        // $body = (string) $response->getBody();
        // $data = json_decode($body);
        // $data = $data->{'ISBN:' . $isbn}->details;

        // return $data;
    }


    public function buscarPorISBN(Request $request)
    {
        $isbn = $request->input('isbn');
        $client = new Client();
        $response = $client->get('https://openlibrary.org/api/books?bibkeys=ISBN:'.$isbn.'&jscmd=details&format=json');
        $body = (string) $response->getBody();
        $data = json_decode($body);
    
        // Retorna la vista 'libros.respuesta' con los datos del libro
        return view('libros.respuesta', compact('data'));
    }
    



    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required',
            'autor' => 'required',
            'editorial' => 'required',
            'paginas' => 'required|integer',
            'isbn' => 'required',
            'portada' => 'required|image|max:2048', // Asegura que se cargue una imagen válida y que no supere 2MB
        ]);
    
        $user = auth()->user();
    
        $libro = new Libro([
            'titulo' => $data['titulo'],
            'autor' => $data['autor'],
            'editorial' => $data['editorial'],
            'paginas' => $data['paginas'],
            'isbn' => $data['isbn'],
        ]);
    
        // Asociar el libro al usuario actual
        $user->libros()->save($libro);
    
        // Procesar la imagen antes de guardarla
        if ($request->hasFile('portada')) {
            $portada = $request->file('portada');
            $nombreArchivo = time() . '.' . $portada->getClientOriginalExtension();
    
            // Redimensionar y ajustar el formato de la imagen antes de guardarla
            $rutaPortada = public_path('portadas/' . $nombreArchivo);
            Image::make($portada)->resize(300, 400)->save($rutaPortada);
    
            $libro->portada = $nombreArchivo;
            $libro->save();
        }
    
        return redirect()->route('libros.index');
    }




    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();

        return redirect()->route('libros.index');
    }

    public function edit($id)
    {
        $libro = Libro::findOrFail($id);
        return view('libros.edit', compact('libro'));
    }

    public function update(Request $request, $id)
    {
        $libro = Libro::findOrFail($id);

        $data = $request->validate([
            'titulo' => 'required',
            'autor' => 'required',
            'editorial' => 'required',
            'paginas' => 'required|integer',
        ]);

        $libro->update($data);

        return redirect()->route('libros.index');
    }


    public function show($id)
    {
        $libro = Libro::findOrFail($id);
        return response()->json($libro);
    }


    public function buscar(Request $request)
{
   
    $isbn = $request->input('miVariable');
        $client = new Client();
        $response = $client->get('https://openlibrary.org/api/books?bibkeys=ISBN:'.$isbn.'&jscmd=details&format=json');
        $body = (string) $response->getBody();
        $data = json_decode($body);

       
        $data = $data->{'ISBN:' . $isbn}->details;
    
        // Retorna la vista 'libros.respuesta' con los datos del libro
        return response()->json(['variableRespuesta' => $data]);
}


public function buscargoogle(Request $request){

        $isbn = $request->input('miVariable');
        $client = new Client();
        $response = $client->get('https://www.googleapis.com/books/v1/volumes?q=isbn:'.$isbn);
        $body = (string) $response->getBody();
        $data = json_decode($body);

       
    
        // Retorna la vista 'libros.respuesta' con los datos del libro
        return response()->json(['variableRespuesta' => $data]);

}


public function exportar()
{
    // Obtener los libros asociados al usuario actual
    $user = auth()->user();
    $libros = $user->libros;

    // Crear el contenido del archivo CSV
    $csvData = [];
    $csvData[] = ['Título', 'Autor', 'Editorial', 'Páginas', 'ISBN'];
    foreach ($libros as $libro) {
        $csvData[] = [$libro->titulo, $libro->autor, $libro->editorial, $libro->paginas, $libro->isbn];
    }

    // Crear el nombre del archivo CSV
    $fileName = 'libros_' . date('Y-m-d_H-i-s') . '.csv';

    // Generar el archivo CSV
    $file = fopen('php://temp', 'w');
    foreach ($csvData as $row) {
        fputcsv($file, $row);
    }
    rewind($file);
    $csvContent = stream_get_contents($file);
    fclose($file);

    // Descargar el archivo CSV
    return response($csvContent)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
}




}

