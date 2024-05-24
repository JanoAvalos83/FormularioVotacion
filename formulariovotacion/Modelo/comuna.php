<?php
    require_once 'conexionDB.php';

class Comuna {
    private $id_comuna;
    private $nombre_comuna;
    private $id_region;

    public function __construct($id_comuna, $nombre_comuna, $id_region) {
        $this->id_comuna = $id_comuna;
        $this->nombre_comuna = $nombre_comuna;
        $this->id_region = $id_region;
    }

    public function getIdComuna() {
        return $this->id_comuna;
    }

    public function setIdComuna($id_comuna) {
        $this->id_comuna = $id_comuna;
    }

    public function getNombreComuna() {
        return $this->nombre_comuna;
    }

    public function setNombreComuna($nombre_comuna) {
        $this->nombre_comuna = $nombre_comuna;
    }

    public function getIdRegion() {
        return $this->id_region;
    }

    public function setIdRegion($id_region) {
        $this->id_region = $id_region;
    }

    public static function obtenerTodasLasComunas(){
        $conn = conectarBaseDatos();

        $sql = "select * from comuna";
        $result = $conn ->query($sql);

        $opciones_comunas = array();

        if ($result) {
            if($result->num_rows > 0){
                while ($row = $result->fetch_assoc()) {
                    $comuna = new stdClass();
                    $comuna->id_comuna = $row['id_comuna'];
                    $comuna->nombre_comuna = $row['nombre_comuna'];
                    $comuna->id_region = $row['id_region'];
                    $opciones_comunas[] = $comuna;
                }
            } else {
                echo "No se encontraron comunas en la base de datos";
            }
        } else {
            echo "Error al ejecutar la consulta: " . $conn->error;
        }

        $conn->close();

        return $opciones_comunas;
    }

    public static function obtenerComunasPorIDRegion($id_region){
        $conn = conectarBaseDatos();

        $sql = "SELECT * from comuna WHERE id_region=".$id_region;
        $result = $conn ->query($sql);

        $opciones_comunas = array();

        if($result){
            if($result->num_rows > 0){
                while ($row = $result->fetch_assoc()){
                    $comuna = new stdClass();
                    $comuna->id_comuna = $row['id_comuna'];
                    $comuna->nombre_comuna = $row['nombre_comuna'];
                    $comuna->id_region = $row['id_region'];
                    $opciones_comunas[] = $comuna;
                }
            } else {
                $comuna = new stdClass();
                $comuna->nombre_comuna = 'No se encontraron comunas';
                $opciones_comunas[] = $comuna  ;

            }
        } else {
            echo "Error al ejecutar la consulta: " . $conn->error;
        }

        $conn->close();

        return $opciones_comunas;
    }

    
}

?>