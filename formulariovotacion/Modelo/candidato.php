<?php

    require_once 'conexionDB.php';

    class Candidato {
        private $id_candidato;
        private $nombre_candidato;
        private $email;
        private $votos;
    
        public function __construct($id_candidato, $nombre_candidato, $email,$votos){
            $this->id_candidato = $id_candidato;
            $this->nombre_candidato = $nombre_candidato;
            $this->email = $email;
            $this->votos = $votos;
        }
    
        public function getIdCandidato() {
            return $this->id_candidato;
        }
        public function setIdCandidato($id_candidato) {
            $this->id_candidato = $id_candidato;
        }
    
        public function getNombreCandidato() {
            return $this->nombre_candidato;
        }
        public function setNombreCandidato($nombre_candidato) {
            $this->nombre_candidato = $nombre_candidato;
        }
    
        public function getEmail() {
            return $this->email;
        }
        public function setEmail($email) {
            $this->email = $email;
        }

        public function getVoto() {
            return $this->votos;
        }
        public function setVoto($votos) {
            $this->votos = $votos;
        }

        public static function todosLosCandidatos(){
            $conn = conectarBaseDatos();

            $sql = "select * from candidato";
            $result = $conn ->query($sql);

            $opciones_candidatos = array();

            if ($result) {
                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        $candidato = new stdClass();
                        $candidato->id_candidato = $row['id_candidato'];
                        $candidato->nombre_candidato = $row['nombre_candidato'];
                        $candidato->email = $row['email'];
                        $candidato->votos = $row['votos'];
                        $opciones_candidatos[] = $candidato;
                    }
                } else {
                    echo "No se encontraron candidatos en la base de datos";
                }
            } else {
                echo "Error al ejecutar la consulta: " . $conn->error;
            }

            $conn->close();

            return $opciones_candidatos;
        }

        public static function buscarCandidatoPorID($id_candidato){
            $conn = conectarBaseDatos();

            $sql = "SELECT * FROM candidato WHERE id_candidato = ".$id_candidato;

            $cantidadVotos = $conn -> query($sql);
            if($cantidadVotos){
                if($cantidadVotos->num_rows > 0){
                    while($row = $cantidadVotos->fetch_assoc()){
                        $candidato = new Candidato($row['id_candidato'],$row['nombre_candidato'],$row['email'],$row['votos']);
                    }
                    $conn->close();
                    return $candidato;

                }
            }
   
        }

        public static function sumarVoto($id_candidato){
            $conn = conectarBaseDatos();

            $actualizacion = $conn->prepare('UPDATE candidato
                    SET votos = votos + 1 
                    WHERE id_candidato = ?');
            
            $actualizacion->bind_param('i',$id_candidato);
            $actualizacion->execute();
            $resultadoActualizacion = $actualizacion->get_result();

            $candidato = Candidato::buscarCandidatoPorID($id_candidato);

            echo $candidato->votos;
        }


    }

?>