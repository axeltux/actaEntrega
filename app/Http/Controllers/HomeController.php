<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Cerys;
use App\User;
use App\Estado;
use App\Motivo;
use App\Oficios;
use App\CredEmpleado;
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
     * [oficios Obtiene el listado de oficios pendientes por ser aceptados]
     * @param  Request $request [Recibe el numero del cerys]
     * @return [array]          [Retorna arreglo con los numeros de oficios del cerys]
     */
    public function oficios() {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        //Si no se recibe el cerys redirigir a error
        if (Auth::user()->cerys === null || Auth::user()->cerys === '') {
            return view('errors.nocerys');
        }
        if(Auth::user()->username === 'Admin'){
            //Obtenemos los oficios del Cerys pendientes o aceptados, 2 indica rechazado
            $oficios    = Oficios::all()->sortBy('id');
            $cerys      = "Todos";
        }else{
            //Obtenemos los oficios del Cerys pendientes o aceptados, 2 indica rechazado
            $oficios    = Oficios::where('cerys', '=', Auth::user()->cerys)
                                ->orderBy('id', 'DESC')
                                ->get();
            $nameCerys  = Cerys::where('Numero', Auth::user()->cerys)->first();
            $cerys      = $nameCerys->Nombre;
        }
        return view('oficios.index', compact('oficios', 'cerys'));
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
        return view('users.index');
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function borraUsuario(Request $request) {
        //Si la sesion expiro mandarlo al login
        if(!Auth::check()){
            return redirect('/login');
        }
        if ($request->ajax()) {
            $error = null;
            DB::beginTransaction();
            try {
                $usuario = User::where('id', $request->id)->first();
                //Guardamos la firma electronica en oficios
                if ($usuario) {
                    $usuario->delete();
                } else {
                    return response()->json([
                        'valor' => "ER",
                        'msg'   => "No se encontro el usuario indicado.",
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
                    'msg'   => "Usuario eliminado."
                ]);
            } else {
                return response()->json([
                    'valor' => "ER",
                    'msg'   => "Error: $error",
                ]);
            }
        }
    }
    
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editUser($id){
        $user = User::findOrFail($id);
        $cerys = Cerys::all();
        return view('users.editar', compact('user','cerys'));
    }
    
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateUser(Request $request, $id){
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cerys' => 'required|integer',
        ]);
        $input = $request->all();
        $user->fill($input)->save();
        return view('users.index');
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
        //Si el usuario admin intenta firmar un documento
        if(Auth::user()->username == 'Admin'){
            return view('errors.404');
        }else{
            //Si el cerys del usuario logueado es diferente del cerys del oficio
            if($oficios->cerys != Auth::user()->cerys){
                return view('errors.404');
            }
        }
        //Si intentan firmar un oficio rechazado
        if($oficios->status == 2){
            return view('errors.404');
        }
        //Si el documento ya ha sido firmado y quieren volver a firmarlo
        if($oficios->firmado == 1){
            return view('errors.yafirmado');
        }
        //En produccion cambiar a la vista efirma
        return view('firma.efirma2', compact('oficio', 'firmado', 'cerys'));
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
        $array      = explode(",", $lotes);
        //Buscamos el o los lotes en cred_historico
        $historico  = DB::table('cred_historico')
                                ->select('id','NumeroEmpleado','UnidadAdmin','Lote','Acepta','Cerys','Firmado')
                                ->whereIn('Lote',$array);
        //Creamos la union con la tabla de cred_empleado
        $lotes      = DB::table('cred_empleado')
                                ->select('id','NumeroEmpleado','UnidadAdmin','Lote','Acepta','Cerys','Firmado')
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
        return view('lotes.index', compact('lotes', 'contador', 'oficio', 'nom', 'firmado', 'aceptado'));
    }
    
    /**
     * @param $lotes
     * @param $oficio
     * @param $cerys
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function firmaLotes($lotes, $oficio, $cerys) {
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
        $listaLotes = $lotes;
        $lotes      = CredEmpleado::whereIn('lote',$array)->get();
        $nameCerys  = Cerys::where('Numero',$cerys)->first();
        $nom        = $nameCerys->Nombre;
        $oficios    = Oficios::where('oficio',$oficio)->first();
        $firmado    = $oficios->firmado;
        $aceptado   = $oficios->status;
        $estado     = Estado::all();
        $motivo     = Motivo::where('Estado', '<>', 'No aplica')->get();
        return view('firma.firmaLotes', compact('lotes', 'listaLotes',
            'contador', 'oficio', 'nom', 'firmado', 'aceptado', 'cerys',
            'estado', 'motivo'));
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerDatosEmpleado(Request $request){
        if ($request->ajax()) {
            $datos = CredEmpleado::where('Id',$request->id)->first();
            
            if ($datos) {
                return response()->json([
                    'valor' => "OK",
                    'numEmp' => $datos->NumeroEmpleado,
                    'nombre' => $datos->Nombre . " ". $datos->APaterno . " ".  $datos->AMaterno,
                    'estado' => $datos->Acepta,
                    'motivo' => $datos->MotivoRechazo
                    ]);
            } else {
                return response()->json([
                    'valor' => "ER",
                    'msg' => "No se encontro el empleado indicado.",
                    'oficio' => "-"
                ]);
            }
        }
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardaEstado(Request $request){
        if ($request->ajax()) {
            $error = null;
            DB::beginTransaction();
            try {
                $credenciales = CredEmpleado::where('Id', $request->id)->first();
                //Guardamos la firma electronica en oficios
                if ($credenciales) {
                    DB::table('cred_empleado')
                        ->where('Id', $request->id)
                        ->update(array('Acepta' => $request->estado,
                                       'MotivoRechazo' => $request->motivo));
                } else {
                    return response()->json([
                        'valor' => "ER",
                        'msg'   => "No se encontro la credencial.",
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
                    'msg'   => "Cambio efectuado.",
                ]);
            } else {
                return response()->json([
                    'valor' => "ER",
                    'msg'   => "Error: $error",
                ]);
            }
        }
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

                if($oficios->firmado == 1){
                    return response()->json([
                        'valor' => "ER",
                        'msg'   => "El documento ya fue firmado.",
                        'oficio'=> "-"
                    ]);
                }

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
                        //Actualizamos las credenciales al firmase
                        DB::table('cred_empleado')
                            ->whereIn('Lote', $array)
                            ->where('Acepta', 1)
                            ->update(array('Firmado' => 1,
                                           'Usuario' => Auth::user()->username));
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
        return view('firma.documento', compact('oficio', 'oficios', 'cerys'));
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
                        'oficio'=> "-",
                        'cerys' => Auth::user()->cerys
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
                    'oficio'=> $request->oficio,
                    'cerys' => Auth::user()->cerys
                ]);
            } else {
                return response()->json([
                    'valor' => "ER",
                    'msg'   => "Error: $error",
                    'oficio'=> "-",
                    'cerys' => Auth::user()->cerys
                ]);
            }
        }
    }
}