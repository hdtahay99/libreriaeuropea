<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $roles = Rol::orderBy('rolid','asc')->paginate(3);
        }
        else{
            $roles = Rol::where('rol_nombre', 'like', '%'.$buscar.'%')->orderBy('rolid','asc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
            ],
            'roles' => $roles
        ];
    }

    public function selectRol(Request $request)
    {
        //if(!$request -> ajax()) return redirect('/');
        $roles = Rol::where('rol_estado', '=', '1')
        ->select('rolid','rol_nombre')
        ->orderBy('rol_nombre', 'asc')->get();
        return ['roles' => $roles];
    } 
}
