<?php

    class ComoSeEntero {
        private $id_como_entero;
        private $nomb_entero;

        public function __construct($id_como_entero, $nomb_entero) {
            $this->id_como_entero = $id_como_entero;
            $this->nomb_entero = $nomb_entero;
        }

        public function getIdComoEntero() {
            return $this->id_como_entero;
        }

        public function setIdComoEntero($id_como_entero) {
            $this->id_como_entero = $id_como_entero;
        }

        public function getNombreEntero() {
            return $this->nomb_entero;
        }

        public function setNombreEntero($nomb_entero) {
            $this->nomb_entero = $nomb_entero;
        }   
    }
?>