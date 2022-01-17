<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio =date('Y');

        $ingresos = DB::table('productos as p')
        ->select(DB::raw('MONTH(p.updated_at) as mes'),
                DB::raw('YEAR(p.updated_at) as anio'),
                DB::raw('COUNT(p.productoid) as total'))
        ->whereYear('p.updated_at',$anio)
        ->groupBy(DB::raw('MONTH(p.updated_at)'),DB::raw('YEAR(p.updated_at)'))
        ->get();


        $ventas = DB::table('facturas as f')
        ->select(DB::raw('MONTH(f.factura_fecha) as mes'),
        DB::raw('YEAR(f.factura_fecha) as anio'),
        DB::raw('SUM(f.factura_total) as total'))
        ->whereYear('f.factura_fecha',$anio)
        ->groupBy(DB::raw('MONTH(f.factura_fecha)'),DB::raw('YEAR(f.factura_fecha)'))
        ->get();

        return['ingresos' => $ingresos, 'ventas' => $ventas, 'anio' =>$anio ];
    }
}
