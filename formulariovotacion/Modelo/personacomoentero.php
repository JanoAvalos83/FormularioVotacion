<?php

    require_once 'conexionDB.php';

    class PersonaComoEntero {
        private $id_persona_como_entero;
        private $id_persona;
        private $id_como_entero;
        
        public function __construct($id_persona_como_entero, $id_persona, $id_como_entero) {
            $this->id_persona_como_entero = $this->controlarID($id_persona_como_entero);
            $this->id_persona = $this->validarNumeroInt($id_persona);
            $this->id_como_entero = $this->validarNumeroInt($id_como_entero);
        }

        // Métodos GET
        public function getIdPersonaComoEntero() {
            return $this->id_persona_como_entero;
        }

        public function getIdPersona() {
            return $this->id_persona;
        }

        public function getIdComoEntero() {
            return $this->id_como_entero;
        }

        // Métodos SET
        public function setIdPersonaComoEntero($id_persona_como_entero) {
            $this->id_persona_como_entero = $id_persona_como_entero;
        }

        public function setIdPersona($id_persona) {
            $this->id_persona = $id_persona;
        }

        public function setIdComoEntero($id_como_entero) {
            $this->id_como_entero = $id_como_entero;
        }

        //METODOS BASE DATOS
        public function insertarPersonaComoEnteroEnDB(){
            $conexion = conectarBaseDatos();
            
            $consulta_como_entero = $conexion->prepare("INSERT INTO personacomoentero (id_como_entero,id_persona) VALUES (?, ?)");
            $consulta_como_entero->bind_param("ii",$this->id_como_entero,$this->id_persona);
            $consulta_como_entero->execute();

            $conexion->close();
        }



        //METODOS VALIDACION
        private function campoObligatorios($atributo){
            if($atributo === "" || $atributo === null){
                throw new Exception('Todos los campos son obligatorios');
            }else{
                return $atributo;
            }
        }

        private function controlarID($id_persona_como_entero){
            
            if($id_persona_como_entero == "" || $id_persona_como_entero == null || $id_persona_como_entero == 0){
                return 0;
            }else{
                return $id_persona_como_entero;
            }
        }

        private function validarNumeroInt($numero){
            $this->campoObligatorios($numero);
            
            if(is_int($numero)){
                return $numero;
            }else{
                throw new Exception("valor no es un numero valido");
            }
        }

    }


?>