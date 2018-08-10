<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Cerys;
use App\User;
use App\Oficios;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //Obtenemos los datos del cerys
        $cerys = Cerys::all();
        return view('home', compact('cerys'));
    }
    
    public function oficios(Request $request) {
        if (!$request->cerys) {
            return view('errors.nocerys');
        }

        $sql        = "SELECT * FROM oficios WHERE cerys=?";
        $oficios    = DB::select($sql,array($request->cerys));
        return view('oficios', compact('oficios'));
    }

    public function listaUsuarios() {
        if (Auth::user()->username != 'Admin') {
            return view('errors.noaccess');
        }
        //Obtenemos los usuarios registrados
        $sql        = "SELECT * FROM users WHERE username<>?";
        $usuarios   = DB::select($sql,array(Auth::user()->username));
        return view('listusers', compact('usuarios'));
    }

    public function destroy($id) {
        User::findOrFail($id)->delete();
        //Redireccionar a la vista index
        return redirect()->route('listusers');
    }

    public function efirma(Request $request) {
        $oficio     = $request->oficio;
        $oficios    = DB::table('oficios')->where('oficio', $oficio)->get();
        $firmado    = $oficios[0]->firmado;
        $nameCerys  = DB::table('cerys')->where('numero', $oficios[0]->cerys)->get();
        $cerys      = $nameCerys[0]->nombre;
        return view('efirma2', compact('oficio', 'firmado', 'cerys'));
    }

    public function listalotes($lotes, $oficio, $cerys) {
        $array      = explode(",",$lotes);
        $lotes      = DB::table('credenciales_empleados')->whereIn('Lote',$array)->get();
        $nameCerys  = DB::table('cerys')->where('numero',$cerys)->get();
        $nom        = $nameCerys[0]->nombre;
        $oficios    = DB::table('oficios')->where('oficio',$oficio)->get();
        $firmado    = $oficios[0]->firmado;
        $contador   = 0;
        return view('lotes', compact('lotes', 'contador', 'oficio', 'nom', 'firmado'));
    }

    /**
     * [sello Guarda firma digital en base de datos]
     * @param  Request $request [Recibe la solicitud por post]
     * @return [json]           [retorna respuesta JSON]
     */
    public function sello(Request $request) {
        if ($request->ajax()) {
            $error = null;
            DB::beginTransaction();
            try {
                //$fechaString    = strtotime($request->fecha . " " . $request->hora);
                //$firmadoEl      = date('d-m-Y H:i:s', $fechaString);
                $fechaString    = $request->fecha . " " . $request->hora;
                $firmadoEl      = Carbon::createFromTimeString($fechaString);                
                $oficios        = Oficios::where('oficio', $request->oficio)->first();

                if ($oficios) {
                    $oficios->firmado       = 1;
                    $oficios->firmadoPor    = $request->nombre;
                    $oficios->rfc           = $request->rfc;
                    $oficios->firmaDigital  = $request->firma;
                    $oficios->selloDigital  = $request->sello;
                    $oficios->firmadoEl     = $firmadoEl;
                    if ($oficios->save()) {
                        //Actualizamos a enviado el lote al firmar el oficio
                        $array  = explode(',', $oficios->lotes);
                        DB::table('lotes')->whereIn('idLote', $array)->update(array('estatus' => 'ENVIADO'));
                    }
                } else {
                    return response()->json([
                        'valor' => "ER",
                        'msg'   => "No se encontro el oficio indicado.",
                        'oficio'=> "-"
                    ]);
                }

                DB::commit();
                $success = true;
            } catch (\Exception $e) {
                $success = false;
                $error = $e->getMessage();
                DB::rollback();
            }
            
            if ($success) {
                return response()->json([
                    'valor' => "OK",
                    'msg'   => "Documento firmado.",
                    'oficio'=> $request->oficio
                ]);
            } else {
                return response()->json([
                    'valor' => "ER",
                    'msg'   => "Error: $error",
                    'oficio'=> "-"
                ]);
            }
        }
    }

    public function docFirmado(Request $request) {
        if (!$request->oficio) {
            return view('errors.noficio');
        }
        //Obtenemos los datos del oficio
        $oficio     = $request->oficio;
        $oficios    = DB::table('oficios')->where('oficio',$oficio)->get();
        if (count($oficios)<1) {
            return view('errors.noficio');
        }
        if ($oficios[0]->firmado == 0) {
            return view('errors.404');
        }
        $nameCerys  = DB::table('cerys')->where('numero',$oficios[0]->cerys)->get();
        $cerys      = $nameCerys[0]->nombre;
        return view('documento', compact('oficio', 'oficios', 'cerys'));
    }
}