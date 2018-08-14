<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Venta;
use App\DetalleVenta;

class VentaController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(Request $request)
    {
       if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.serie_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado','users.usuario','personas.nombre')
                ->orderBy('ventas.id','DESC')
                ->paginate(3);
        }else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.serie_comprobante',
                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado','users.usuario','personas.nombre')
                ->where('ventas.'.$criterio, 'LIKE', '%'.$buscar.'%')
                ->orderBy('ventas.id','DESC')
                ->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem()
            ],
            'ventas' => $ventas
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $mytime = Carbon::now('America/Mexico_City');

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $mytime->toDateString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->estado = 'Registrado';
            $venta->save();

            $detalles = $request->data; //Array de detalles
            //Recorro todos los detalles
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }

    public function obtenerCabecera(Request $request){
        if(!$request->ajax()) return redirect('/');
        $id= $request->id;
            $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.serie_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado','users.usuario','personas.nombre')
                ->where('ventas.id', '=', $id)
                ->take(1)
                ->get();
        return ['venta' => $venta];
    }

    public function obtenerDetalles(Request $request){
        if(!$request->ajax()) return redirect('/');
        $id= $request->id;
            $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','articulos.nombre as articulo')
                ->where('detalle_ventas.idingreso', '=', $id)
                ->get();
        return ['detalles' => $detalles];
    }
}
