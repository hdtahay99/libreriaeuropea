<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Categoria; //Importar el modelo

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Listar todos los registros
        if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $categorias = Categoria::orderBy('categoriaid','desc')->paginate(3);
        }
        else{
            $categorias = Categoria::where('categoria_nombre', 'like', '%'.$buscar.'%')->orderBy('categoriaid','desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $categorias -> total(),
                'current_page' => $categorias -> currentPage(),
                'per_page' => $categorias -> perPage(),
                'last_page' => $categorias -> lastPage(),
                'from' => $categorias -> firstItem(),
                'to' => $categorias -> lastItem(),
            ],
            'categorias' =>  $categorias
        ];
    }

    public function selectCategoria(Request $request){
        if(!$request -> ajax()) return redirect('/');
        $categorias = Categoria::where('categoria_estado','=','1')
        ->select('categoriaid','categoria_nombre')->orderBy('categoria_nombre','asc')->get();
        return ['categorias' => $categorias];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Recibimos los datos del objeto request para insertarlo en la base de datos
        if(!$request -> ajax()) return redirect('/');
        $categoria = new Categoria();
        $categoria->categoria_nombre = $request->categoria_nombre;
        $categoria->categoria_estado = '1';
        $categoria->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $categoria = Categoria::findOrfail($request->categoriaid);
        $categoria->categoria_nombre = $request->categoria_nombre;
        $categoria->categoria_estado = '1';
        $categoria->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $categoria = Categoria::findOrfail($request->categoriaid);
        $categoria->categoria_estado = '0';
        $categoria->save();
    }

    public function activar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $categoria = Categoria::findOrfail($request->categoriaid);
        $categoria->categoria_estado = '1';
        $categoria->save();
    }

}
