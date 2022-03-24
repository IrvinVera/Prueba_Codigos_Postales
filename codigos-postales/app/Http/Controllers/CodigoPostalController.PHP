<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Codigo;
use \stdClass;

class CodigoPostalController extends Controller
{

    public function obtenerInformacionCodigoPostal($codigo){

        $respuesta = "";
        $array_settlements = [];

        $codigos_encontrados =  Codigo::where('d_codigo',$codigo)->get();

            $informacion_codigo_postal = $codigos_encontrados[0];

            $objeto_federal_entity = new stdClass();
            $objeto_federal_entity->key = $informacion_codigo_postal->c_estado;
            $objeto_federal_entity->name = $informacion_codigo_postal->d_estado;
            $objeto_federal_entity->code = $informacion_codigo_postal->c_CP;

            $objeto_municipality = new stdClass();
            $objeto_municipality->key = $informacion_codigo_postal->c_mnpio;
            $objeto_municipality->name = $informacion_codigo_postal->D_mnpio;

            foreach ($codigos_encontrados as $codigo) {
                $objeto_settlement = new stdClass();
                $objeto_settlement->key = $codigo->id_asenta_cpcons;
                $objeto_settlement->name = $codigo->d_asenta;
                $objeto_settlement->zone_type = $codigo->d_zona;
                
                $objeto_settlement_type = new stdClass();
                $objeto_settlement_type->name = $codigo->d_tipo_asenta;

                $objeto_settlement->settlement_type = $objeto_settlement_type;

                array_push($array_settlements,$objeto_settlement);
            }

            $objeto_final = new stdClass();
            $objeto_final->zip_code = $informacion_codigo_postal->d_codigo;
            $objeto_final->locality = $informacion_codigo_postal->d_ciudad;
            $objeto_final->federal_entity = $objeto_federal_entity;
            $objeto_final->settlements = $array_settlements;
            $objeto_final->municipality = $objeto_municipality;

        return  json_encode($objeto_final);
    }

}
