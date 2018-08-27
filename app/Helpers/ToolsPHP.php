<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ToolsPHP {
	/**
	 * Retorna el nombre de un empleado
	*/
    public static function NomEmpleados($id){
    	$dato = DB::table('vs_empleados_p_DR')->select('*')->where('NumeroEmpleado','=',$id)->get();
            if(count($dato)>=1){
                    return $dato[0]->Nombre . " " . $dato[0]->APaterno . " " . $dato[0]->AMaterno;
            }else{
                    return "- - -";
            }
    }

    public static function NombreMes($mes){
            setlocale(LC_TIME, 'spanish');
            $nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000));
            return $nombre;
    }
}