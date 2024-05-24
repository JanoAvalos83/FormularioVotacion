// Ejecuta esta función cuando se cargue la página
document.addEventListener('DOMContentLoaded', function() {
    var xhrRegiones = new XMLHttpRequest();
    xhrRegiones.open("GET","../Controlador/regionesControlador.php?accion=obtenerRegionesJSON", true);
    xhrRegiones.onreadystatechange = function() {
        if (xhrRegiones.readyState === XMLHttpRequest.DONE) {
            if (xhrRegiones.status === 200) {
                    
                var regiones = JSON.parse(xhrRegiones.responseText); // Convertir el JSON a un objeto JavaScript
                
                var selectRegion = document.getElementById("region");

                // Limpiar cualquier opción existente en el select
                selectRegion.innerHTML = "";
                
                const opcion1 = document.createElement("option")
                opcion1.value = '0';
                opcion1.textContent = 'Seleccione Region';
                selectRegion.appendChild(opcion1);

                // Iterar sobre cada región y agregar una opción al select
                regiones.forEach(function(region) {
                    var option = document.createElement("option");
                    option.value = region.id_region;
                    option.textContent = region.nombre_region;
                    selectRegion.appendChild(option);
                });
            } else {
                console.error("Error al cargar las regiones");
            }
        }
    };
    xhrRegiones.send();
});