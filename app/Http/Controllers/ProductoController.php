<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use App\Producto;

class ProductoController extends Controller
{
    //
    public function index(Request $request)
    {
        //Listar todos los registros
        if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $productos = Producto::join('categorias', 'productos.categoriaid','=','categorias.categoriaid')
            ->select('productos.productoid','productos.categoriaid','productos.producto_barra','productos.producto_nombre','categorias.categoria_nombre','productos.producto_pcompra','productos.producto_pventa','productos.producto_existencia','productos.producto_imagen','productos.producto_estado')
            ->orderBy('productos.productoid','desc')->paginate(10);
        }
        else{

            $productos = Producto::join('categorias', 'productos.categoriaid','=','categorias.categoriaid')
            ->select('productos.productoid','productos.categoriaid','productos.producto_barra','productos.producto_nombre','categorias.categoria_nombre','productos.producto_pcompra','productos.producto_pventa','productos.producto_existencia','productos.producto_imagen','productos.producto_estado')
            ->where('productos.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('productos.productoid','desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $productos -> total(),
                'current_page' => $productos -> currentPage(),
                'per_page' => $productos -> perPage(),
                'last_page' => $productos -> lastPage(),
                'from' => $productos -> firstItem(),
                'to' => $productos -> lastItem(),
            ],
            'productos' =>  $productos
        ];
    }

    public function buscarProductoCategoria(Request $request){
        //if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $categoriaid = $request->categoriaid;

        if($buscar==''){
            $productos = Producto::join('categorias', 'productos.categoriaid','=', 'categorias.categoriaid')
            ->select('productos.productoid','productos.categoriaid','productos.producto_barra','productos.producto_nombre','categorias.categoria_nombre','productos.producto_pcompra','productos.producto_pventa','productos.producto_existencia','productos.producto_imagen','productos.producto_estado')
            ->where('productos.categoriaid', '=', $categoriaid)
            ->orderBy('productos.productoid','desc')->paginate(10);
        }
        else{

            $productos = Producto::join('categorias', 'productos.categoriaid','=','categorias.categoriaid')
            ->select('productos.productoid','productos.categoriaid','productos.producto_barra','productos.producto_nombre','categorias.categoria_nombre','productos.producto_pcompra','productos.producto_pventa','productos.producto_existencia','productos.producto_imagen','productos.producto_estado')
            ->where('productos.categoriaid', '=', $categoriaid)
            ->where('productos.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('productos.productoid','desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $productos -> total(),
                'current_page' => $productos -> currentPage(),
                'per_page' => $productos -> perPage(),
                'last_page' => $productos -> lastPage(),
                'from' => $productos -> firstItem(),
                'to' => $productos -> lastItem(),
            ],
            'productos' =>  $productos
        ];
        
    }

    public function buscarProductoVenta(Request $request){
        if(!$request -> ajax()) return redirect('/');

        $filtro = $request->filtro;

        $productos = Producto::where('producto_barra','=',$filtro)
        ->select('productoid','producto_nombre','producto_pcompra','producto_pventa','producto_existencia','producto_imagen','producto_estado')
        ->where('producto_estado','=','1')
        ->where('producto_existencia','>','0')
        ->orderBy('producto_nombre','asc')
        ->take(1)->get();

        return ['productos' => $productos];
    }

    public function listarProductoVenta(Request $request){
        if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $productos = Producto::join('categorias', 'productos.categoriaid','=','categorias.categoriaid')
            ->select('productos.productoid','productos.categoriaid','productos.producto_barra','productos.producto_nombre','categorias.categoria_nombre','productos.producto_pcompra','productos.producto_pventa','productos.producto_existencia','productos.producto_imagen','productos.producto_estado')
            ->where('productos.producto_existencia','>','0')
            ->where('productos.producto_estado','=','1')
            ->orderBy('productos.productoid','desc')->paginate(3);
        }
        else{

            $productos = Producto::join('categorias', 'productos.categoriaid','=','categorias.categoriaid')
            ->select('productos.productoid','productos.categoriaid','productos.producto_barra','productos.producto_nombre','categorias.categoria_nombre','productos.producto_pcompra','productos.producto_pventa','productos.producto_existencia','productos.producto_imagen','productos.producto_estado')
            ->where('productos.'.$criterio, 'like', '%'.$buscar.'%')
            ->where('productos.producto_existencia','>','0')
            ->where('productos.producto_estado','=','1')
            ->orderBy('productos.productoid','desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $productos -> total(),
                'current_page' => $productos -> currentPage(),
                'per_page' => $productos -> perPage(),
                'last_page' => $productos -> lastPage(),
                'from' => $productos -> firstItem(),
                'to' => $productos -> lastItem(),
            ],
            'productos' =>  $productos
        ];
    }

    public function listarPdf(){
        $productos = Producto::join('categorias', 'productos.categoriaid','=','categorias.categoriaid')
        ->select('productos.productoid','productos.categoriaid','productos.producto_barra','productos.producto_nombre','categorias.categoria_nombre','productos.producto_pcompra','productos.producto_pventa','productos.producto_existencia','productos.producto_imagen','productos.producto_estado')
        ->orderBy('productos.producto_nombre','desc')->get();

        $cont=Producto::count();

        $pdf = \PDF::loadView('pdf.productospdf', ['productos' => $productos, 'cont' => $cont]);
        return $pdf->download('productos.pdf');
    }

    public function store(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $ruta = public_path().'/uploads/';
        if($request->hasFile('producto_imagen'))
        {
            $imagenOriginal = $request->file('producto_imagen');
            $imagen = Image::make($imagenOriginal);
            $temp_name = $this->random_string() . '.' . $imagenOriginal->getClientOriginalExtension();
            $imagen->save($ruta . $temp_name, 100);

            $producto = new Producto();
            $producto->categoriaid = $request->categoriaid;
            $producto->producto_nombre = $request->producto_nombre;
            $producto->producto_existencia = $request->producto_existencia;
            $producto->producto_barra = $request->producto_barra;
            $producto->producto_pcompra = $request->producto_pcompra;
            $producto->producto_pventa = $request->producto_pventa;
            $producto->producto_imagen = $temp_name;
            $producto->producto_estado = '1';
            $producto->save();
        }        
        else
        {
            $producto = new Producto();
            $producto->categoriaid = $request->categoriaid;
            $producto->producto_nombre = $request->producto_nombre;
            $producto->producto_existencia = $request->producto_existencia;
            $producto->producto_barra = $request->producto_barra;
            $producto->producto_pcompra = $request->producto_pcompra;
            $producto->producto_pventa = $request->producto_pventa;
            $producto->producto_imagen = '';
            $producto->producto_estado = '1';
            $producto->save();
        }
    }


    public function update(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $ruta = public_path().'/uploads/';
        $producto = Producto::findOrfail($request->productoid);
        $producto->categoriaid = $request->categoriaid;
        $producto->producto_nombre = $request->producto_nombre;
        $producto->producto_existencia = $request->producto_existencia;
        $producto->producto_barra = $request->producto_barra;
        $producto->producto_pcompra = $request->producto_pcompra;
        $producto->producto_pventa = $request->producto_pventa;
        $producto->producto_estado = '1';

        if ($request->hasFile('producto_imagen')) {
            $imagenOriginal = $request->file('producto_imagen');
            $imagen = Image::make($imagenOriginal);
            $temp_name = $this->random_string() . '.' . $imagenOriginal->getClientOriginalExtension();
            $imagen->save($ruta . $temp_name, 100);
            $producto->producto_imagen = $temp_name;
        }
        $producto->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $producto = Producto::findOrfail($request->productoid);
        $producto->producto_estado = '0';
        $producto->save();
    }

    public function activar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $producto = Producto::findOrfail($request->productoid);
        $producto->producto_estado = '1';
        $producto->save();
    }

    protected function random_string()
    {
        $key = '';
        $keys = array_merge( range('a','z'), range(0,9) );
    
        for($i=0; $i<10; $i++)
        {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }
}
