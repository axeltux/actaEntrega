<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Cerys;
use App\User;
use App\Oficios;
use App\CredEmpleado;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generaPDF($oficio, $tipo) {
        //Obtenemos el oficio
        $oficios = Oficios::where('oficio', $oficio)->first();
        if (!$oficios) {
            return view('errors.noficio');
        }
        if ($oficios->firmado == 0) {
        	return view('errors.404');
        }
        //Obtenemos los lotes
        $array      = explode(",",$oficios->lotes);
        //Buscamos el o los lotes en cred_historico
        $historico  = DB::table('cred_historico')
                                ->select('NumeroEmpleado','UnidadAdmin','Lote','Cerys')
                                ->where('Firmado',1)
                                ->whereIn('Lote',$array);
        //Creamos la union con la tabla de cred_empleado
        $lotes      = DB::table('cred_empleado')
                                ->select('NumeroEmpleado','UnidadAdmin','Lote','Cerys')
                                ->where('Firmado',1)
                                ->whereIn('Lote',$array)
                                ->union($historico)
                                ->orderBy('Lote')
                                ->get();
        //Obtemos la cantidad de tarjetas
        $totalLotes = count($lotes);
        //Datos generales para el llenado del documento
        $fecha      = date('Y-m-d', strtotime($oficios->firmadoEl));
        $hora       = date('H:i:s', strtotime($oficios->firmadoEl));
        $date       = date('Y-m-d');
        //Nombre del Cerys
        $nameCerys  = Cerys::where('Numero',$oficios->cerys)->first();
        $cerys      = $nameCerys->Nombre;
        //Dibujamos la vista con render
        $view       = \View::make('pdf.invoice2', compact('oficios', 'totalLotes', 'date', 'fecha', 'hora', 'cerys'))->render();
        $pdf        = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        if ($tipo == 1) {
        	return $pdf->stream("acuse_$oficio.pdf");
        } elseif ($tipo == 2) {
        	return $pdf->download("acuse_$oficio.pdf");
        } else {
        	return view('errors.404');
        }
    }
}
