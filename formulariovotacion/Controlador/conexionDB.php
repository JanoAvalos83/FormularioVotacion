<?php
// Conexión a la base de datos

function conectarBaseDatos(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "formulariovotacion";

    $conexion= new mysqli($servername, $username, $password, $dbname);

    if ($conexion->connect_error) {
        die("Error de conexión a base de datos: " . $conexion->connect_error);
    }

    return $conexion;
}

?>



