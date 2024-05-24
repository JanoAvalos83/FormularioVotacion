document.addEventListener('DOMContentLoaded', function() {
    var xhrCandidatos = new XMLHttpRequest();
    xhrCandidatos.open("GET","../Controlador/candidatoControlador.php?accion=obtenerCandidatosJSON", true);
    xhrCandidatos.onreadystatechange = function() {
        if (xhrCandidatos.readyState === XMLHttpRequest.DONE) {
            if (xhrCandidatos.status === 200) {
                    
                var candidatos = JSON.parse(xhrCandidatos.responseText); // Convertir el JSON a un objeto JavaScript
                
                var selectCandidato = document.getElementById("candidato");

                // Limpiar cualquier opción existente en el select
                selectCandidato.innerHTML = "";
                
                const opcion1 = document.createElement("option")
                opcion1.value = '0';
                opcion1.textContent = 'Seleccione Candidato';
                selectCandidato.appendChild(opcion1);

                // Iterar sobre cada región y agregar una opción al select
                candidatos.forEach(function(candidato) {
                    var option = document.createElement("option");
                    option.value = candidato.id_candidato;
                    option.textContent = candidato.nombre_candidato;
                    selectCandidato.appendChild(option);
                });
            } else {
                console.error("Error al cargar las candidatos");
            }
        }
    };
    xhrCandidatos.send();
});