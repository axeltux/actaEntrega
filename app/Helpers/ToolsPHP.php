<?php

namespace App\Helpers;

use App\Models\Motivo;
use App\Models\Employees;
use App\Models\Cerys;

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

    /**
     * [FechaHumanos convierte el numero de mes a texto]
     * @param [string] $fecha [Retorna la fecha en formato dd MM YY hh:mm PM/AM]
     */
    public static function FechaHumanos($fecha){
        setlocale(LC_TIME, 'spanish');
        $mes    = date("m", strtotime($fecha));
        $dia    = date("d", strtotime($fecha));
        $annio  = date("Y", strtotime($fecha));
        $hora   = date("g:i a", strtotime($fecha));
        $mesEsp = ucwords(strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)));
        $feEsp  = "$dia de $mesEsp del $annio, $hora";
        return $feEsp;
    }
    
    public static function nombreMotivo($id){
        $motivo = Motivo::where('Id',"$id")->first();
        if($motivo){
            return $motivo->Nombre;
        }else{
            return '';
        }
    }
}