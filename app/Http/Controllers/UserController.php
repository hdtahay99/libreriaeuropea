<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Empleado;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $empleados = User::join('empleados', 'users.userid','=','empleados.empleadoid')
            ->join('roles','users.rolid','=', 'roles.rolid')
            ->select('empleados.empleadoid','empleados.empleado_nombre','empleados.empleado_apellido',
            'empleados.empleado_telefono','empleados.empleado_direccion','empleados.empleado_estado','users.usuario','users.password','users.estado',
            'users.rolid','roles.rol_nombre')
            ->orderBy('empleados.empleadoid','desc')->paginate(3);
        }
        else{
            $empleados = User::join('empleados', 'users.userid','=','empleados.empleadoid')
            ->join('roles','users.rolid','= ', 'roles.rolid')
            ->select('empleados.empleadoid','empleados.empleado_nombre','empleados.empleado_apellido',
            'empleados.empleado_telefono','empleados.empleado_direccion','empleados.empleado_estado','users.usuario','users.password','users.estado',
            'users.rolid','roles.rol_nombre')
            ->where('empleados.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('empleados.empleadoid','desc')->paginate(3);
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
        try{
            DB::beginTransaction();
            $empleado = new Empleado();
            $empleado->empleado_nombre = $request->empleado_nombre;
            $empleado->empleado_apellido = $request->empleado_apellido;
            $empleado->empleado_telefono = $request->empleado_telefono;
            $empleado->empleado_direccion = $request->empleado_direccion;
            $empleado->empleado_estado = '1';
            $empleado->save();

            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->estado = '1';
            $user->rolid = $request->rolid;
            $user->userid = $empleado->empleadoid;
            $user->save();
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $user = User::findOrFail($request->userid);
            $empleado = Empleado::findOrFail($user->userid);
            $empleado->empleado_nombre = $request->empleado_nombre;
            $empleado->empleado_apellido = $request->empleado_apellido;
            $empleado->empleado_telefono = $request->empleado_telefono;
            $empleado->empleado_direccion = $request->empleado_direccion;
            $empleado->empleado_estado = '1';
            $empleado->save();

            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->estado = '1';
            $user->rolid = $request->rolid;
            $user->userid = $empleado->empleadoid;
            $user->save();
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $user = User::findOrFail($request->userid);
        $user->estado = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $user = User::findOrFail($request->userid);
        $user->estado = '1';
        $user->save();
    }

}
