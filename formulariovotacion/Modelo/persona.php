<?php

    require_once 'conexionDB.php';

    class Persona {
        private $id_persona;
        private $nombre_apellido;
        private $alias;
        private $rut;
        private $email;
        private $id_comuna;
        private $id_candidato;

        public function __construct($id_persona, $nombre_apellido, $alias, $rut, $email, $id_comuna, $id_candidato) {
            $this->id_persona = $this->validarId_persona($id_persona);
            $this->nombre_apellido = $this->campoObligatorios($nombre_apellido);
            $this->alias = $this->campoObligatorios($alias);
            $this->rut = $this->validarRut($rut);
            $this->email = $this->validarEmail($email);
            $this->id_comuna = $this->validarNumeroInt($id_comuna);
            $this->id_candidato = $this->validarNumeroInt($id_candidato);
        }

        public function getIdPersona() {
            return $this->id_persona;
        }

        public function setIdPersona($id_persona) {
            $this->id_persona = $id_persona;
        }

        public function getNombreApellido() {
            return $this->nombre_apellido;
        }

        public function setNombreApellido($nombre_apellido) {
            $this->nombre_apellido = $nombre_apellido;
        }

        public function getAlias() {
            return $this->alias;
        }

        public function setAlias($alias) {
            $this->alias = $alias;
        }

        public function getRut() {
            return $this->rut;
        }

        public function setRut($rut) {
            $this->rut = $this->validarRut($rut);
        }

        public function getEmail() {
            return $this->email;
        }

        public function setEmail($email) {
            $this->email = $this->validarEmail($email);
        }

        public function getIdComuna() {
            return $this->id_comuna;
        }

        public function setIdComuna($id_comuna) {
            $this->id_comuna = $id_comuna;
        }

        public function getIdCandidato() {
            return $this->id_candidato;
        }

        public function setIdCandidato($id_candidato) {
            $this->id_candidato = $id_candidato;
        }
        
        //Para validar email en modelo
        private function validarEmail($email){
            $this->campoObligatorios($email);
            if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
                return $email;
            } else {
                throw new Exception("El correo electronico no es valido");
            }
        }

        private function campoObligatorios($atributo){
            if($atributo === "" || $atributo === null){
                throw new Exception('Todos los campos son obligatorios');
            }else{
                return $atributo;
            }
        }
        //Validar rut
        private function calcularDigitoVerificador($rutNumerico) {
            $suma = 0;
            $multiplicador = 2;
            $longitud = strlen((string)$rutNumerico);
            $aux = $rutNumerico;
            $numeroinvertido = [];
            $numeroinvertidoXSec = [];
        
            // Invertir número
            for ($i = 0; $i < $longitud; $i++) {
                $element = $aux % 10;
                $aux = intval($aux / 10);
                $numeroinvertido[] = $element;
            }
        
            // Multiplicar desde 2 hasta el 7
            for ($i = 0; $i < count($numeroinvertido); $i++) {
                if ($multiplicador > 7) {
                    $multiplicador = 2;
                }
                $resultado = $numeroinvertido[$i] * $multiplicador;
                $numeroinvertidoXSec[] = $resultado;
                $multiplicador++;
            }
        
            // Calcular la suma de los resultados
            foreach ($numeroinvertidoXSec as $element) {
                $suma += $element;
            }
        
            // Calcular el resto de dividir la suma por 11
            $resto11 = intval($suma / 11);
            $resto11X11 = $resto11 * 11;
            $diferenciaSumaResto11X11 = intval($suma - $resto11X11);
        
            // Calcular el dígito verificador
            $dvaux = 11 - $diferenciaSumaResto11X11;
            $dv = $dvaux === 11 ? 0 : $dvaux;
            if ($dvaux === 10) {
                $dv = 10;
            }
        
            return $dv;
        }
        
        private function validarRut($rut) {
            $this->campoObligatorios($rut);

            if (strpos($rut, '-') === false) {
                throw new Exception("Rut debe tener guion");
            }
        
            // Separar el RUT y el dígito verificador
            list($rutNumerico, $digitoVerificador) = explode('-', $rut);
            
            if (strlen($digitoVerificador) > 1 || strlen($rutNumerico) > 8 || strlen($rutNumerico) < 7) {
                throw new Exception("Rut no valido");
            }
        
            $rutNumerico = intval($rutNumerico);
            if ($digitoVerificador !== 'K') {
                $digitoVerificador = intval($digitoVerificador);
            }
        
            if ($digitoVerificador == 'k') {
                $digitoVerificador = strtoupper($digitoVerificador);
            }
        
            // Calcular el dígito verificador esperado
            $digitoVerificadorCalculado = $this->calcularDigitoVerificador($rutNumerico);
            if ($digitoVerificadorCalculado === 10) {
                $digitoVerificadorCalculado = 'K';
            }
        
            // Comparar el dígito verificador calculado con el proporcionado
            if ($digitoVerificadorCalculado === $digitoVerificador) {
                return $rutNumerico . '-' . $digitoVerificador;
            } else {
                throw new Exception("Digito verificador no corresponde al rut");

            }
        }

        private function validarId_persona($id_persona){
            if($id_persona == "" || $id_persona == null){
                return 0;
            }else{
                return $id_persona;
            }
        }

        private function validarNumeroInt($numero){
            if(is_int($numero) && $numero > 0){
                return $numero;
            }else{
                throw new Exception("valor de Campo Region, Comuna o Candidato no es valor valido");
            }

        }
        

        //Interaccion base de datos
        public function insertarPersonaDB(){
            $conexion = conectarBaseDatos();
            if($this->existePersonaEnDB() === false){

                // Preparar la consulta SQL para insertar en la tabla persona
                $consulta = $conexion->prepare("INSERT INTO persona (nombre_apellido, alias, rut, email, id_comuna, id_candidato) VALUES (?, ?, ?, ?, ?, ?)");
                $consulta->bind_param("ssssii", $this->nombre_apellido, $this->alias, $this->rut, $this->email, $this->id_comuna, $this->id_candidato);
    
                // Ejecutar la consulta
                if ($consulta->execute()) {
                    $conexion->close();
                    return true;
                    
                } else {
                    $conexion->close();
                    throw new Exception("Error al insertar persona en base datos: " . $conexion->error);
                }
            }else{
                $conexion->close();
                throw new Exception("Persona ya existe en base de datos");
                
            }
    
        }



        public static function obtenerTodosLosDatosPersonas(){
            $conn = conectarBaseDatos();
            $sql = "SELECT nombre_apellido,rut,email,nombre_comuna,GROUP_CONCAT(nombre_entero) AS nombre_entero FROM `persona`
                        JOIN comuna on persona.id_comuna = comuna.id_comuna
                        JOIN personacomoentero on persona.id_persona = personacomoentero.id_persona
                        JOIN comoseentero on personacomoentero.id_como_entero = comoseentero.id_como_entero
                    GROUP BY 
                        nombre_apellido, 
                        rut, 
                        email, 
                        nombre_comuna;";
        
            $result = $conn->query($sql);
            $opciones_personas = array();
        
            if($result){
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $persona = new stdClass();
                        $persona->nombre_apellido = $row['nombre_apellido']; 
                        $persona->rut = $row['rut'];
                        $persona->email = $row['email'];
                        $persona->nombre_comuna = $row['nombre_comuna']; 
                        $persona->nombre_entero = $row['nombre_entero'];
                        $opciones_personas[] = $persona;
                    }
                }else{
                    //controlar vacio
                    return $opciones_personas;
                    // throw new Exception("No se encontraron personas en la base de datos");
                }
            }else {
                throw new Exception('Error al ejecutar la consulta: ' . $conn->error);
            }
        
            $conn->close();
        
            return $opciones_personas;
        }

        public function existePersonaEnDB(){
            $conn = conectarBaseDatos();
            $sql = "SELECT * FROM persona WHERE rut = ?";

            $consulta = $conn->prepare($sql);
            $consulta->bind_param("s",$this->rut);
            $consulta->execute();
            $resultado = $consulta->get_result();
            $conn->close();

            if($resultado->num_rows > 0){
                return true;
            }else{
                return false;
            }
            
        }

        public function traerIDPersona(){
            $conexion = conectarBaseDatos();
            $consulta_id_persona = $conexion->prepare("SELECT id_persona FROM persona WHERE rut = ?");
            $consulta_id_persona->bind_param("s", $this->rut);
            $consulta_id_persona->execute();
            $resultado_id_persona = $consulta_id_persona->get_result();
            
            if ($resultado_id_persona->num_rows > 0) {
                // Obtener el ID de la persona
                $fila_persona = $resultado_id_persona->fetch_assoc();
                $id_persona = $fila_persona['id_persona'];
                
                $conexion->close();
                return $id_persona;
            
            } else {
                $conexion->close();
                throw new Exception('Persona no esta registrada en base de datos');
            }
        }

        public function eliminarPersona(){
            $conexion = conectarBaseDatos();
            $consulta_id_persona = $conexion->prepare("DELETE FROM persona WHERE id_persona = ?");
            $consulta_id_persona->bind_param("i", $this->id_persona);
            $consulta_id_persona->execute();
            $resultado_id_persona = $consulta_id_persona->get_result();
        }
    }

?>

