<?php

    require_once '../Modelo/candidato.php'; // Ruta ajustada para incluir el archivo desde una carpeta arriba

    class CandidatoController {
        public function obtenerCandidatosJSON() {
            $opciones_candidatos = Candidato::todosLosCandidatos();
            echo json_encode($opciones_candidatos, JSON_UNESCAPED_UNICODE);
        }

    }

    // Instanciar la clase y llamar al método si la solicitud es correcta
    if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerCandidatosJSON') {
        $candidatoController = new CandidatoController();
        $candidatoController->obtenerCandidatosJSON();
    }


?>