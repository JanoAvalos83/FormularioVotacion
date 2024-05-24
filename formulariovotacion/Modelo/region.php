<?php

require_once 'conexionDB.php';

class Region {
    private $id_region;
    private $nombre_region;

    public function __construct($id_region,$nombre_region){
        $this->id_region = $id_region;
        $this->nombre_region = $nombre_region;
    }

    public function getIdRegion() {
        return $this->id_region;
    }
    public function setIdRegion($id_region) {
        $this->id_region = $id_region;
    }

    public function getNombreRegion(){
        return $this->nombre_region;
    }
    public function setNombreRegion($nombre_region){
        $this->nombre_region = $nombre_region;
    }

    public static function obtenerTodasLasRegiones(){
        $conn = conectarBaseDatos();

        $sql = "select * from region";
        $result = $conn ->query($sql);

        $opciones_regiones = array();

        if ($result) {
            if($result->num_rows > 0){
                while ($row = $result->fetch_assoc()) {
                    $region = new stdClass();
                    $region->id_region = $row['id_region'];
                    $region->nombre_region = $row['nombre_region'];
                    $opciones_regiones[] = $region;
                }
            } else {
                echo "No se encontraron regiones en la base de datos";
            }
        } else {
            echo "Error al ejecutar la consulta: " . $conn->error;
        }

        $conn->close();

        return $opciones_regiones;
    }

    

}

?>