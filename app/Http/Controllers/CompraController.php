<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Proveedor;
use App\Compra;
use App\CompraDetalle;
use App\MongoUtil;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $compras = Compra::join('empleados', 'compras.empleadoid','=','empleados.empleadoid')
            ->join('proveedores','compras.proveedorid','=', 'proveedores.proveedorid')
            ->select('compras.compraid', 'compras.codigo_compra','compras.compra_fecha',
            'compras.compra_total','compras.compra_estado',
            'compras.fecha_credito_limite','compras.estado','empleados.empleado_nombre','empleados.empleado_apellido',
            'proveedores.proveedor_nombre','clientes.cliente_apellido','proveedores.proveedor_nit','proveedores.proveedor_direccion')
            ->orderBy('compras.comprasid','desc')->paginate(8);
        }
        else{
            if($criterio == 'proveedor_nit'){
                $compras = Compra::join('empleados', 'compras.empleadoid','=','empleados.empleadoid')
                ->join('proveedores','compras.proveedorid','=', 'proveedores.proveedorid')
                ->select('compras.compraid', 'compras.codigo_compra','compras.compra_fecha',
                'compras.compra_total','compras.compra_estado',
                'compras.fecha_credito_limite','compras.estado','empleados.empleado_nombre','empleados.empleado_apellido',
                'proveedores.proveedor_nombre','clientes.cliente_apellido','proveedores.proveedor_nit','proveedores.proveedor_direccion')
                ->where('proveedores.proveedor_nit','like','%'.$buscar.'%')
                ->orderBy('compras.comprasid','desc')->paginate(8);
            }
            else{
                $compras = Compra::join('empleados', 'compras.empleadoid','=','empleados.empleadoid')
                ->join('proveedores','compras.proveedorid','=', 'proveedores.proveedorid')
                ->select('compras.compraid', 'compras.codigo_compra','compras.compra_fecha',
                'compras.compra_total','compras.compra_estado',
                'compras.fecha_credito_limite','compras.estado','empleados.empleado_nombre','empleados.empleado_apellido',
                'proveedores.proveedor_nombre','clientes.cliente_apellido','proveedores.proveedor_nit','proveedores.proveedor_direccion')
                ->where('compras.'.$criterio, 'like', '%'.$buscar.'%')
                ->orderBy('compras.compraid','desc')->paginate(8);
            }
        }

        return [
            'pagination' => [
                'total' => $compras -> total(),
                'current_page' => $compras -> currentPage(),
                'per_page' => $compras -> perPage(),
                'last_page' => $compras -> lastPage(),
                'from' => $compras -> firstItem(),
                'to' => $compras -> lastItem(),
            ],
            'compras' =>  $compras
        ];
    }

    public function store(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');

        try {

            DB::beginTransaction();

            $mytime = Carbon::now('America/Guatemala');

            $compra                       = new Compra();
            $comra->codigo_compra         = MongoUtil::mongoIdFromTimestamp($mytime);
            $compra->empleadoid           = \Auth::user()->userid;
            $compra->proveedorid          = $request->proveedorid;
            $compra->compra_fecha         = $mytime;
            $compra->compra_total         = $request->compra_total;
            $compra->compra_estado        = $request->compra_estado;
            $compra->fecha_credito_limite = $request->fecha_credito_limite;
            $compra->easter_date          = '1';
            $compra->save();

            $detalles = $request->detalles;

            foreach($detalles as $detalle)
            {
                $mytime = Carbon::now('America/Guatemala');

                $detalle->codigo_detalle_compra = MongoUtil::mongoIdFromTimestamp($mytime);
                $detalle->compraid              = $compra->compraid;
                $detalle->productoid            = $detalle['productoid'];
                $detalle->detalle_cantidad      = $detalle['detalle_cantidad'];
                $detalle->detalle_monto         = $detalle['detalle_monto'];
                $detalle->detalle_estado        = $detalle['detalle_estado'];
                $detalle->estado                = '1';

                $detalle->save();
            }

            DB::commit();

            return ['compraid' => $compra->compraid];
            
        } catch (Exception $e) {            
            DB::rollback();
        }

    }
}
