<?php
use App\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/oficios/{username}', function($username){
    //Buscamos al usuario en la tabla users
    if(!isset($username)){
        return view('errors.404');
    }
    $user = User::where('username', $username)->first();
    //Si es el usuario Admin buscar los oficios de todos los cerys.
    if($user->username === 'Admin'){
        //Obtenemos los oficios del Cerys pendientes o aceptados, 2 indica rechazado
        $oficios    = DB::select("SELECT oficio.id, oficio.oficio, cerys.Nombre AS cerys, oficio.lotes,
                                    CONVERT(varchar,oficio.creadoEl,9) AS creado,
                                    estado= CASE
                                                WHEN oficio.status=0 THEN 'Pendiente'
                                                WHEN oficio.status=1 THEN 'Aceptado'
                                                WHEN oficio.status=2 THEN 'Rechazado'
                                                ELSE 'N/A'
                                            END,
                                    firmado=CASE
                                                WHEN oficio.firmado=0 THEN 'No'
                                                WHEN oficio.firmado=1 THEN 'Si'
                                                ELSE 'N/A'
                                            END,
                                    usuario = '$username', estadoOficio=oficio.status, firmaOficio=oficio.firmado,
                                    oficio.cerys AS numCerys
                                    FROM oficio
                                    JOIN cerys ON cerys.Numero=oficio.cerys
                                    ORDER BY oficio.id DESC
                                ");
    }else{
        //Obtenemos los oficios del Cerys del usuario logueado pendientes o aceptados, 2 indica rechazado
        $oficios    = DB::select("SELECT oficio.id, oficio.oficio, cerys.Nombre AS cerys, oficio.lotes,
                                    CONVERT(varchar,oficio.creadoEl,9) AS creado,
                                    estado= CASE
                                                WHEN oficio.status=0 THEN 'Pendiente'
                                                WHEN oficio.status=1 THEN 'Aceptado'
                                                WHEN oficio.status=2 THEN 'Rechazado'
                                                ELSE 'N/A'
                                            END,
                                    firmado=CASE
                                                WHEN oficio.firmado=0 THEN 'No'
                                                WHEN oficio.firmado=1 THEN 'Si'
                                                ELSE 'N/A'
                                            END,
                                    usuario = '$username', estadoOficio=oficio.status, firmaOficio=oficio.firmado,
                                    oficio.cerys AS numCerys
                                    FROM oficio
                                    JOIN cerys ON cerys.Numero=oficio.cerys
                                    WHERE oficio.cerys=?
                                    ORDER BY oficio.id DESC
                                ", [$user->cerys]);
    }
    //Retornamos los valores en Json con Query Builder via Factory
    return DataTables::of($oficios)
                    ->addColumn('botones','oficios.botones')
                    ->rawColumns(['botones'])
                    ->toJson();
});

Route::get('/users', function(){
    //Buscamos los usuarios dados de alta en el sistema
    $usuarios       = DB::select("SELECT a.id, a.username AS rfc,a.name AS nombre,
                                    a.email AS correo, b.Nombre AS cerys
                                FROM users a
                                JOIN Cerys b ON b.Numero=a.cerys
                                WHERE a.username<>'Admin'
                            ");
    //Retornamos los valores en Json con Query Builder via Factory
    return DataTables::of($usuarios)
                    ->addColumn('btn','users.botones')
                    ->rawColumns(['btn'])
                    ->toJson();
});

Route::get('/lotes/{lote}', function($lote){
    $caracter   = substr($lote, -1);
    if($caracter == ','){
        $lote = trim($lote, ',');
    }
    //Buscamos los usuarios dados de alta en el sistema
    $lotes      = DB::select("SELECT a.id, a.NumeroEmpleado, CONCAT(a.nombre, ' ', a.APaterno, ' ', a.AMaterno) AS nombre,
                                        a.UnidadAdmin, CONCAT('Lote-',a.Lote) AS lote
                             FROM cred_empleado a
                             WHERE a.lote IN(1,2)
                            ");
    //Retornamos los valores en Json con Query Builder via Factory
    return DataTables::of($lotes)
        ->addColumn('btn','firma.botones')
        ->rawColumns(['btn'])
        ->toJson();
});