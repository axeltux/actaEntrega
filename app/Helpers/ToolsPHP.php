<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Employees;
use App\Cerys;

class ToolsPHP {
	/**
	 * Retorna el nombre de un empleado
	*/
    public static function NomEmpleados($id){
        $empleado = Employees::where('enroll_code',"$id")->first();
        if($empleado){
            return $empleado->name_1 . " " . $empleado->name_2 . " " . $empleado->name_3;
        }else{
            return "- - -";
        }
    }

    /**
     * [NombreMes convierte el numero de mes a texto]
     * @param [Int] $mes [Numero del mes a ser convertido en String]
     */
    public static function NombreMes($mes){
        setlocale(LC_TIME, 'spanish');
        $nombre = strftime("%B",mktime(0, 0, 0, $mes, 1, 2000));
        return $nombre;
    }

    /**
	 * Retorna el nombre de un empleado
	*/
    public static function NomCerys($id){
        $cerys = Cerys::where('Numero',$id)->first();
        if($cerys){
            return $cerys->Nombre;
        }else{
            return "- - -";
        }
    }
}