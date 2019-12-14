<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empleado;

class EmpleadoController extends Controller
{
    //

    public function index(Request $request)
    {
        //Listar todos los registros
        if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $empleados = Empleado::orderBy('empleadoid','desc')->paginate(3);
        }
        else{
            $empleados = Empleado::where('empleado_nombre', 'like', '%'.$buscar.'%')->orWhere('empleado_apellido', 'like', '%'.$buscar.'%')->orderBy('empleadoid','desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $empleados -> total(),
                'current_page' => $empleados -> currentPage(),
                'per_page' => $empleados -> perPage(),
                'last_page' => $empleados -> lastPage(),
                'from' => $empleados -> firstItem(),
                'to' => $empleados -> lastItem(),
            ],
            'empleados' =>  $empleados
        ];
    }

    public function store(Request $request)
    {
        //Recibimos los datos del objeto request para insertarlo en la base de datos
        if(!$request -> ajax()) return redirect('/');
        $empleado = new Empleado();
        $empleado->empleado_nombre = $request->empleado_nombre;
        $empleado->empleado_apellido = $request->empleado_apellido;
        $empleado->empleado_telefono = $request->empleado_telefono;
        $empleado->empleado_direccion = $request->empleado_direccion;
        $empleado->empleado_estado = '1';
        $empleado->save();
    }

    public function update(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $empleado = Empleado::findOrfail($request->empleadoid);
        $empleado->empleado_nombre = $request->empleado_nombre;
        $empleado->empleado_apellido = $request->empleado_apellido;
        $empleado->empleado_telefono = $request->empleado_telefono;
        $empleado->empleado_direccion = $request->empleado_direccion;
        $empleado->empleado_estado = '1';
        $empleado->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $empleado = Empleado::findOrfail($request->empleadoid);
        $empleado->empleado_estado = '0';
        $empleado->save();
    }

    public function activar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $empleado = Empleado::findOrfail($request->empleadoid);
        $empleado->empleado_estado = '1';
        $empleado->save();
    }
}
