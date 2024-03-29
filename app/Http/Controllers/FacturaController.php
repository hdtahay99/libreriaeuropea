<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Factura;
use App\Detalle;
use App\DetalleTemporal;
use App\User;
use App\Cliente;
use App\Notifications\NotifyAdmin;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        //if(!$request -> ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $facturas = Factura::join('empleados', 'facturas.empleadoid','=','empleados.empleadoid')
            ->join('clientes','facturas.clienteid','=', 'clientes.clienteid')
            ->select('facturas.facturaid','facturas.factura_fecha',
            'facturas.factura_total','facturas.factura_pago',
            'facturas.factura_estado','facturas.condicion','empleados.empleado_nombre','empleados.empleado_apellido',
            'clientes.cliente_nombre','clientes.cliente_apellido','clientes.cliente_nit','clientes.cliente_direccion')
            ->orderBy('facturas.facturaid','desc')->paginate(8);
        }
        else{
            if($criterio == 'cliente_nit'){
                $facturas = Factura::join('empleados', 'facturas.empleadoid','=','empleados.empleadoid')
                ->join('clientes','facturas.clienteid','=', 'clientes.clienteid')
                ->select('facturas.facturaid','facturas.factura_fecha',
                'facturas.factura_total','facturas.factura_pago',
                'facturas.factura_estado','facturas.condicion','empleados.empleado_nombre','empleados.empleado_apellido',
                'clientes.cliente_nombre','clientes.cliente_apellido','clientes.cliente_nit','clientes.cliente_direccion')
                ->where('clientes.cliente_nit','like','%'.$buscar.'%')
                ->orderBy('facturas.facturaid','desc')->paginate(8);
            }
            else{
                $facturas = Factura::join('empleados', 'facturas.empleadoid','=','empleados.empleadoid')
                ->join('clientes','facturas.clienteid','=', 'clientes.clienteid')
                ->select('facturas.facturaid','facturas.factura_fecha',
                'facturas.factura_total','facturas.factura_pago',
                'facturas.factura_estado','facturas.condicion','empleados.empleado_nombre','empleados.empleado_apellido',
                'clientes.cliente_nombre','clientes.cliente_apellido','clientes.cliente_nit','clientes.cliente_direccion')
                ->where('facturas.'.$criterio, 'like', '%'.$buscar.'%')
                ->orderBy('facturas.facturaid','desc')->paginate(8);
            }
        }

        return [
            'pagination' => [
                'total' => $facturas -> total(),
                'current_page' => $facturas -> currentPage(),
                'per_page' => $facturas -> perPage(),
                'last_page' => $facturas -> lastPage(),
                'from' => $facturas -> firstItem(),
                'to' => $facturas -> lastItem(),
            ],
            'facturas' =>  $facturas
        ];
    }

    public function obtenerCabecera(Request $request){
        if(!$request -> ajax()) return redirect('/');

        $facturaid = $request->facturaid;

        $factura = Factura::join('empleados', 'facturas.empleadoid','=','empleados.empleadoid')
        ->join('clientes','facturas.clienteid','=', 'clientes.clienteid')
        ->select('facturas.facturaid','facturas.factura_fecha',
        'facturas.factura_total','facturas.factura_pago',
        'facturas.factura_estado','facturas.condicion',DB::raw("CONCAT(empleados.empleado_nombre, ', ',empleados.empleado_apellido) AS enombre"),
         DB::raw("CONCAT(clientes.cliente_nombre, ', ',clientes.cliente_apellido) AS nombre"),'clientes.cliente_nit','clientes.cliente_direccion')
        ->where('facturas.facturaid','=',$facturaid)
        ->orderBy('facturas.facturaid','desc')->take(1)->get();

        return ['factura' => $factura];
    }

    public function pdf(Request $request, $id){
        $factura = Factura::join('empleados', 'facturas.empleadoid','=','empleados.empleadoid')
        ->join('clientes','facturas.clienteid','=','clientes.clienteid')
        ->select('facturas.facturaid','facturas.factura_fecha','facturas.factura_total','facturas.factura_pago','factura_estado',
         'clientes.cliente_nit','clientes.cliente_direccion', DB::raw("CONCAT(clientes.cliente_nombre, ', ',clientes.cliente_apellido) AS nombre"),
         DB::raw("CONCAT(empleados.empleado_nombre, ', ',empleados.empleado_apellido) AS enombre"))
        ->where('facturas.facturaid','=',$id)
        ->orderBy('facturas.facturaid','desc')->take(1)->get();

        $detalles = Detalle::join('productos', 'detalles.productoid','=','productos.productoid')
        ->select('detalles.detalleid','productos.producto_pventa','detalles.detalle_cantidad','detalles.detalle_monto','productos.producto_nombre')
        ->where('detalles.facturaid','=',$id)
        ->orderBy('detalles.detalleid','desc')->get();
        
        $numfactura = Factura::select('facturaid')->where('facturaid',$id)->get();

        $pdf = \PDF::loadView('pdf.factura',['factura' => $factura, 'detalles' => $detalles]);
        return $pdf->download('factura-'.$numfactura[0]->facturaid.'.pdf');
    }

    public function dailyReport(Request $request, $date)
    {
        $ventas = DB::table('facturas as f')
                        ->select(DB::raw('SUM(f.factura_total) as total'))
                        ->whereDate('f.factura_fecha',$date)
                        ->where('f.condicion', '=', '1')
                        ->get();

        $pdf = \PDF::loadView('pdf.dailysale',['ventas' => $ventas, 'fecha' => $date]);
        return $pdf->download('factura-'.$date.'.pdf');
    }

    public function obtenerDetalles(Request $request){
        if(!$request -> ajax()) return redirect('/');

        $facturaid = $request->facturaid;


        $detalles = Detalle::join('productos','detalles.productoid','=','productos.productoid')
        ->select('detalles.detalle_cantidad','productos.producto_pventa','detalles.detalle_monto','productos.producto_nombre')
        ->where('detalles.facturaid','=',$facturaid)
        ->orderBy('detalles.detalleid','desc')->get();

        $temporales = DetalleTemporal::where('facturaid','=',$facturaid)
        ->where('estado', '=', '1')
        ->orderBy('detempid','desc')->get();

        return ['detalles' => $detalles, 'temporales' => $temporales];

    }

    public function store2(Request $request){
        if(!$request -> ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $cliente = new Cliente();
            $cliente->cliente_nombre = $request->cliente_nombre;
            $cliente->cliente_apellido = $request->cliente_apellido;
            $cliente->cliente_direccion = $request->cliente_direccion;
            $cliente->cliente_nit = $request->cliente_nit;
            $cliente->cliente_estado = '1';
            $cliente->save();

            $mytime = Carbon::now('America/Guatemala');
            $factura = new Factura();
            $factura->empleadoid = \Auth::user()->userid;
            $factura->clienteid = $cliente->clienteid;
            $factura->factura_fecha = $mytime;
            $factura->factura_total = $request->factura_total;
            $factura->factura_pago = $request->factura_pago;
            $factura->factura_estado = 'Registrado';
            $factura->condicion = '1';
            $factura->save();

            $detalles   = $request->data;
            $temporales = $request->temporal;

            foreach($detalles as $ep => $det){
                $detalle = new Detalle();
                $detalle->facturaid = $factura->facturaid;
                $detalle->productoid = $det['productoid'];
                $detalle->detalle_cantidad = $det['detalle_cantidad'];
                $detalle->detalle_monto = $det['detalle_monto'];
                $detalle->save();
            }

            foreach($temporales as $temp)
            {
                $detemp                    = new DetalleTemporal();
                $detemp->facturaid         = $factura->facturaid;
                $detemp->temporal_producto = $temp['producto_nombre'];
                $detemp->temporal_monto    = $temp['detalle_monto'];
                $detemp->temporal_cantidad = $temp['detalle_cantidad'];
                $detemp->estado            = '1';
                $detemp->save();
            }

            DB::commit();

            return [
                'facturaid' => $factura->facturaid
            ];
        } catch(Exception $e){
            DB::rollBack();
        }
    }

    public function store(Request $request)
    {
        //Recibimos los datos del objeto request para insertarlo en la base de datos
        if(!$request -> ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $mytime = Carbon::now('America/Guatemala');
            $factura = new Factura();
            $factura->empleadoid = \Auth::user()->userid;
            $factura->clienteid = $request->clienteid;
            $factura->factura_fecha = $mytime;
            $factura->factura_total = $request->factura_total;
            $factura->factura_pago = $request->factura_pago;
            $factura->factura_estado = 'Registrado';
            $factura->condicion = '1';
            $factura->save();

            $detalles   = $request->data;
            $temporales = $request->temporal;

            foreach($detalles as $ep => $det){
                $detalle = new Detalle();
                $detalle->facturaid = $factura->facturaid;
                $detalle->productoid = $det['productoid'];
                $detalle->detalle_cantidad = $det['detalle_cantidad'];
                $detalle->detalle_monto = $det['detalle_monto'];
                $detalle->save();
            }

            foreach($temporales as $temp)
            {
                $detemp                    = new DetalleTemporal();
                $detemp->facturaid         = $factura->facturaid;
                $detemp->temporal_producto = $temp['producto_nombre'];
                $detemp->temporal_monto    = $temp['detalle_monto'];
                $detemp->temporal_cantidad = $temp['detalle_cantidad'];
                $detemp->estado            = '1';
                $detemp->save();
            }
            

            DB::commit();

            return [
                'facturaid' => $factura->facturaid
            ];
        } catch(Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $factura = Factura::findOrFail($request->facturaid);
        $factura->factura_estado = "Anulado";
        $factura->condicion = '0';
        $factura->save();
    }
}
