<?php

    require_once 'conexionDB.php';
    require_once '../Modelo/persona.php';
    require_once '../Modelo/candidato.php';
    require_once '../Modelo/personacomoentero.php';

    try {
        $conexion = conectarBaseDatos();
        // Verificar si se recibieron los datos en formato JSON
        $json_data = file_get_contents('php://input');

        if ($json_data) {
            // Decodificar los datos JSON
            $datos = json_decode($json_data, true);
            // Verificar si se decodificaron correctamente
            if ($datos !== null) {
                // Extraer los datos necesarios
                $nombreApellido = $datos['nombreApellido'];
                $alias = $datos['alias'];
                $rut = $datos['rut'];
                $email = $datos['email'];
                $id_region = intval($datos['region']);
                $id_comuna = intval($datos['comuna']);
                $id_candidato = intval($datos['id_candidato']);
                $comoEntero = isset($datos['comoEntero']) ? $datos['comoEntero'] : [];
                $persona = new Persona("",$nombreApellido,$alias,$rut,$email,$id_comuna,$id_candidato);
                
                if(count($comoEntero) > 0){
                    if(!$persona->existePersonaEnDB()){

                        // Ejecutar la consulta
                        if ($persona->insertarPersonaDB()) {
                            //Recuperar id_persona desde DB
                            $id_persona = $persona->traerIDPersona();

                            // Insertar datos en la tabla personacomoentero
                            // comoEntero es un array con id_como_Entero;

                            foreach ($comoEntero as $id_como_entero) {
                                $id_como_entero = intval($id_como_entero);

                                $personaComoEntero = new PersonaComoEntero("",$id_persona,$id_como_entero);
                                $personaComoEntero->insertarPersonaComoEnteroEnDB();

                            }        

                        } else {
                            throw new Exception("Error al insertar los datos: " . $conexion->error);
                        }
                    }else{
                        throw new Exception('Error al Usuario ya existe en basede datos');
                    }
                }else{
                    throw new Exception('No se selecciono como se entero');
                }

                // Cerrar la conexión
                $conexion->close();
            } else {
                throw new Exception("Error al decodificar los datos JSON.");
            }
        } else {
            throw new Exception("No se recibieron datos en formato JSON.");
        }
    
    }catch(Exception $error){

        $response = [
            'error' => true,
            'message' =>  'Error al procesar formulario: '.$error->getMessage()
        ];

        header('Content-Type: application/json');
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
        
    }

?>