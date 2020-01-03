<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Cliente;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        //Listar todos los registros
        if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $clientes = Cliente::orderBy('clienteid','desc')->paginate(3);
        }
        elseif($criterio=='cliente_nombre'){
            $clientes = Cliente::where('cliente_nombre', 'like', '%'.$buscar.'%')->orWhere('cliente_apellido', 'like', '%'.$buscar.'%')->orderBy('clienteid','desc')->paginate(3);
        }
        else{
            $clientes = Cliente::where($criterio, 'like', '%'.$buscar.'%')->orderBy('clienteid','desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $clientes -> total(),
                'current_page' => $clientes -> currentPage(),
                'per_page' => $clientes -> perPage(),
                'last_page' => $clientes -> lastPage(),
                'from' => $clientes -> firstItem(),
                'to' => $clientes -> lastItem(),
            ],
            'clientes' =>  $clientes
        ];
    }

    public function selectCliente(Request $request){
        //if(!$request -> ajax()) return redirect('/');

        $filtro = $request->filtro;
        $clientes = Cliente::where('cliente_nombre', 'like', '%'.$filtro.'%')
        ->orWhere('cliente_apellido', 'like', '%'.$filtro.'%')
        ->orWhere('cliente_nit', 'like', '%'.$filtro.'%')
        ->select(DB::raw("CONCAT(cliente_nombre, ', ',cliente_apellido) AS nombre"), 'clienteid', 'cliente_nit', 'cliente_direccion')
        ->orderBy('cliente_nombre','asc')->get();

        return ['clientes' => $clientes];
    }

    public function buscarCliente(Request $request)
    {

        $cliente_nit = $request->cliente_nit;
        $cliente = Cliente::where('cliente_nit', '=', $cliente_nit)->get();

        return ['cliente' => $cliente];
    }
    
    public function store(Request $request)
    {
        //Recibimos los datos del objeto request para insertarlo en la base de datos
        if(!$request -> ajax()) return redirect('/');
        $cliente = new Cliente();
        $cliente->cliente_nombre = $request->cliente_nombre;
        $cliente->cliente_apellido = $request->cliente_apellido;
        $cliente->cliente_direccion = $request->cliente_direccion;
        $cliente->cliente_nit = $request->cliente_nit;
        $cliente->cliente_estado = '1';
        $cliente->save();
    }
    
    public function update(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $cliente = Cliente::findOrfail($request->clienteid);
        $cliente->cliente_nombre = $request->cliente_nombre;
        $cliente->cliente_apellido = $request->cliente_apellido;
        $cliente->cliente_direccion = $request->cliente_direccion;
        $cliente->cliente_nit = $request->cliente_nit;
        $cliente->cliente_estado = '1';
        $cliente->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $cliente = Cliente::findOrfail($request->clienteid);
        $cliente->cliente_estado = '0';
        $cliente->save();
    }

    public function activar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $cliente = Cliente::findOrfail($request->clienteid);
        $cliente->cliente_estado = '1';
        $cliente->save();
    }
}
