<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Cerys;
use App\User;
use App\Oficios;
use App\Lotes;
use App\CredEmpleado;
use App\CredHistorico;
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
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        //Obtenemos los datos del cerys
        if (Auth::user()->username === 'Admin') {
            $cerys = Cerys::all();
        }else{
            $cerys  = Cerys::where('Numero', Auth::user()->cerys)->get();
        }
        return view('home', compact('cerys'));
    }

    /**
     * [oficios Obtiene el listado de oficios pendientes por ser aceptados]
     * @param  Request $request [Recibe el numero del cerys]
     * @return [array]          [Retorna arreglo con los numeros de oficios del cerys]
     */
    public function oficios(Request $request) {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        //Si no se recibe el cerys redirigir a error
        if (!$request->cerys) {
            return view('errors.nocerys');
        }
        //Obtenemos los oficios del Cerys pendientes o aceptados, 2 indica rechazado
        $oficios    = Oficios::where('cerys', '=', $request->cerys)
                            ->where('status','<>',2)
                            ->get();
        $nameCerys  = Cerys::where('Numero', $request->cerys)->first();
        $cerys      = $nameCerys->Nombre;
        return view('oficios', compact('oficios', 'cerys'));
    }

    /**
     * [listaUsuarios Muestra la lista de los usuarios del sistema]
     * @return [none] [Retorna la vista del listado de usuarios registrados]
     */
    public function listaUsuarios() {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        //Si el usuario no es Admin mandar error
        if (Auth::user()->username != 'Admin') {
            return view('errors.noaccess');
        }
        //Obtenemos los usuarios registrados
        $usuario   = User::all()->sortBy("id");;
        $usuarios  = [];
        //Recorremos el arreglo obtenido para quitar Admin
        foreach ($usuario as $data) {
            if($data->username != 'Admin'){
                $usuarios[] = $data;
            }
        }
        return view('listusers', compact('usuarios'));
    }

    /**
     * [destroy Metodo para borra usuarios del sistema]
     * @param  [int] $id  [Recibe el id del usuario]
     * @return [none]     [Retorna vista de lista de usuarios]
     */
    public function destroy($id) {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        //Si no se encuentra el id mandar error 400
        User::findOrFail($id)->delete();
        //Redireccionar a la vista index
        return redirect()->route('listusers');
    }

    /**
     * [efirma Envia datos a vista de efirma para la firma digital]
     * @param  Request $request [Recibe el oficio que sera firmado]
     * @return [varios]         [Retorna valores para la firma]
     */
    public function efirma(Request $request) {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        $oficios    = Oficios::where('oficio', $request->oficio)->first();
        $oficio     = $oficios->oficio;
        $firmado    = $oficios->firmado;
        $nameCerys  = Cerys::where('Numero', $oficios->cerys)->first();
        $cerys      = $nameCerys->Nombre;
        if(Auth::user()->username == 'Admin'){
            return view('errors.404');
        }else{
            if($oficios->cerys != Auth::user()->cerys){
                return view('errors.404');
            }
        }
        //En produccion cambiar a la vista efirma
        return view('efirma2', compact('oficio', 'firmado', 'cerys'));
    }

    /**
     * [listalotes Lista los empleatos del o los lotes del oficio]
     * @param  [string] $lotes  [Lista de los lotes del oficio]
     * @param  [string] $oficio [Oficio que se esta validando]
     * @param  [int] $cerys     [El numero del cerys que esta en el oficio]
     * @return [varios]         [Retorna arreglos y variables]
     */
    public function listalotes($lotes, $oficio, $cerys) {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        if(Auth::user()->username != 'Admin'){
            if($cerys != Auth::user()->cerys){
                return view('errors.404');
            }
        }
        $array      = explode(",",$lotes);
        //Buscamos el o los lotes en cred_historico
        $historico  = DB::table('cred_historico')
                                ->select('NumeroEmpleado','UnidadAdmin','Lote','Cerys')
                                ->whereIn('Lote',$array);
        //Creamos la union con la tabla de cred_empleado
        $lotes      = DB::table('cred_empleado')
                                ->select('NumeroEmpleado','UnidadAdmin','Lote','Cerys')
                                ->whereIn('Lote',$array)
                                ->union($historico)
                                ->orderBy('Lote')
                                ->get();
        $nameCerys  = Cerys::where('Numero',$cerys)->first();
        $nom        = $nameCerys->Nombre;
        $oficios    = Oficios::where('oficio',$oficio)->first();
        $firmado    = $oficios->firmado;
        $aceptado   = $oficios->status;
        $contador   = 0;
        return view('lotes', compact('lotes', 'contador', 'oficio', 'nom', 'firmado', 'aceptado'));
    }

    /**
     * [sello Guarda firma digital en base de datos]
     * @param  Request $request [Recibe la solicitud por post]
     * @return [json]           [retorna respuesta JSON]
     */
    public function sello(Request $request) {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        if ($request->ajax()) {
            $error = null;
            DB::beginTransaction();
            try {
                $fechaString    = strtotime($request->fecha . " " . $request->hora);
                $firmadoEl      = date('d-m-Y H:i:s', $fechaString);
                $oficios        = Oficios::where('oficio', $request->oficio)->first();

                //Guardamos la firma electronica en oficios
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
                        DB::table('cred_lote')
                                    ->whereIn('NoLote', $array)
                                    ->update(array('EstatusFirma' => 'Firmado'));
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

    /**
     * [docFirmado Muestra el resumen del documento al ser firmado]
     * @param  Request $request [Recibe los datos del oficio firmado]
     * @return [varios]         [retorna arreglos y variables]
     */
    public function docFirmado(Request $request) {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        //Si no se especifica oficio retornar error
        if (!$request->oficio) {
            return view('errors.noficio');
        }
        //Obtenemos los datos del oficio
        $oficios    = Oficios::where('oficio', $request->oficio)->first();
        $oficio     = $oficios->oficio;
        //Si no existe oficio envia error
        if (!$oficios) {
            return view('errors.noficio');
        }
        //Si el oficio no esta firmado envia a pagina de error
        if ($oficios->firmado == 0) {
            return view('errors.404');
        }
        //si el Cerys del usuario logeado no es el mismo del documento
        if($oficios->cerys != Auth::user()->cerys){
            return view('errors.404');
        }
        $nameCerys  = Cerys::where('Numero', $oficios->cerys)->first();
        $cerys      = $nameCerys->Nombre;
        return view('documento', compact('oficio', 'oficios', 'cerys'));
    }

    /**
     * [statusOficio Cambia el estatus del oficio a Aceptado o Rechazado]
     * @param  Request $request [Recibe la solicitud por post]
     * @return [json]           [retorna respuesta JSON]
     */
    public function statusOficio(Request $request){
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        if ($request->ajax()) {
            $error = null;
            $estado = 'Aceptado';
            $estadoFirma = 'No firmado';
            DB::beginTransaction();
            try {
                $oficios        = Oficios::where('id', $request->id)->first();
                //Se confirma la recepción del oficio en el Cerys
                if ($oficios) {
                    $oficios->status = $request->tipo;
                    if ($oficios->save()) {
                        //Actualizamos a enviado el lote al firmar el oficio
                        $array  = explode(',', $oficios->lotes);
                        if ($request->tipo == 1){
                            $estado = 'Aceptado';
                        }else if($request->tipo == 2){
                            $estado = 'Rechazado';
                        }else{
                            $estado = 'Aceptado';
                        }
                        DB::table('cred_lote')
                                    ->whereIn('NoLote', $array)
                                    ->update(array('EstatusCerys' => $estado,
                                                   'EstatusFirma' => $estadoFirma,
                                                   'Comentarios'=> $request->comment
                                            ));
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
                    'msg'   => "Oficio $estado",
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
}