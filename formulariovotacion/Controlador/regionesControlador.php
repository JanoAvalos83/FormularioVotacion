<?php

require_once '../Modelo/region.php'; // Ruta ajustada para incluir el archivo desde una carpeta arriba

class RegionController {
    public function obtenerRegionesJSON() {
        $opciones_regiones = Region::obtenerTodasLasRegiones();
        echo json_encode($opciones_regiones, JSON_UNESCAPED_UNICODE);
    }
}

// Instanciar la clase y llamar al método si la solicitud es correcta
if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerRegionesJSON') {
    $regionController = new RegionController();
    $regionController->obtenerRegionesJSON();
}

?>