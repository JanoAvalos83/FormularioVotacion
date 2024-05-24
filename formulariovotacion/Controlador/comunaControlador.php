<?php

    require_once '../Modelo/comuna.php';

    class ComunaController {
        public function obtenerComunasJSON(){
            $opciones_comuna = Comuna::obtenerTodasLasComunas();
            echo json_encode($opciones_comuna, JSON_UNESCAPED_UNICODE);
        }
        public function obtenerComunasPorIDRegionJSON($id_region){
            $opciones_comuna = Comuna::obtenerComunasPorIDregion($id_region);
            echo json_encode($opciones_comuna, JSON_UNESCAPED_UNICODE);
        }
    }

    if(isset($_GET['accion']) && $_GET['accion'] == 'obtenerComunasJSON'){
        $comunaController = new ComunaController();
        $comunaController->obtenerComunasJSON();
    }

    if(isset($_GET['accion']) && $_GET['accion'] == 'obtenerComunasPorIDRegionJSON' && isset($_GET['id_region'])) {
        $id_region = $_GET['id_region']; 
        $comunaController = new ComunaController();
        $comunaController->obtenerComunasPorIDRegionJSON($id_region);
    }

?>
