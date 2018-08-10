<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Cerys;
use App\User;
use App\Oficios;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generaPDF($oficio, $tipo) {
        //Obtenemos el oficio
        $oficios = DB::table('oficios')->where('oficio', $oficio)->get();
        if (count($oficios)<1) {
            return view('errors.noficio');
        }
        if ($oficios[0]->firmado == 0) {
        	return view('errors.404');
        }
        //Obtenemos los lotes
        $array      = explode(",",$oficios[0]->lotes);
        $lotes      = DB::table('credenciales_empleados')->whereIn('Lote', $array)->get();
        //Obtemos la cantidad de tarjetas
        $totalLotes = count($lotes);
        //Datos generales para el llenado del documento        
        $nombre     = $oficios[0]->firmadoPor;
        $rfc        = $oficios[0]->RFC;
        $fecha      = date('Y-m-d', strtotime($oficios[0]->firmadoEl));
        $hora       = date('H:i:s', strtotime($oficios[0]->firmadoEl));
        $firmaD     = $oficios[0]->firmaDigital;
        $selloD     = $oficios[0]->selloDigital;        
        $date       = date('Y-m-d');
        //Nombre del Cerys
        $nameCerys  = DB::table('cerys')->where('numero',$oficios[0]->cerys)->get();
        $cerys      = $nameCerys[0]->nombre;
        //Dibujamos la vista con render
        $view       =  \View::make('pdf.invoice2', compact('oficios', 'totalLotes', 'date', 'nombre', 'rfc', 'fecha', 'hora', 'firmaD', 'selloD', 'oficio', 'cerys'))->render();
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
