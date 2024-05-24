<?php

    require_once '../Modelo/persona.php';

    class personasController {
        public function obtenerpersonasJSON(){
            $opciones_personas = Persona::obtenerTodosLosDatosPersonas();
            echo json_encode($opciones_personas, JSON_UNESCAPED_UNICODE);
        }
    }

    if(isset($_GET['accion']) && $_GET['accion'] == 'obtenerpersonasJSON'){
        $personasController = new personasController();
        $personasController->obtenerpersonasJSON();
    }

?>
