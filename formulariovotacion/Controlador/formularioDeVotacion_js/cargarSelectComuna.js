document.addEventListener('DOMContentLoaded', () => {
    const selectRegion = document.querySelector("#region");

    selectRegion.addEventListener('change', async (evt) =>{
        const id_region = evt.target.value;
        await obtenerComunasPorRegion(id_region)
    })


});



const obtenerComunasPorRegion = async (id_region) => {

    var xhrComunas = new XMLHttpRequest();
    xhrComunas.open("GET","../Controlador/comunaControlador.php?accion=obtenerComunasPorIDRegionJSON&id_region="+id_region, true);
    xhrComunas.onreadystatechange = function() {
        if (xhrComunas.readyState === XMLHttpRequest.DONE) {
            if (xhrComunas.status === 200) {
                    
                var comunas = JSON.parse(xhrComunas.responseText); // Convertir el JSON a un objeto JavaScript
                
                var selectcomuna = document.getElementById("comuna");
                
                selectcomuna.innerHTML = "";
                
                const opcion1 = document.createElement("option")
                    opcion1.value = '0';
                    opcion1.textContent = 'Seleccione comuna';
                    selectcomuna.appendChild(opcion1);
                    
                comunas.forEach(function(comuna) {
                    var option = document.createElement("option");
                    option.value = comuna.id_comuna;
                    option.textContent = comuna.nombre_comuna;
                    selectcomuna.appendChild(option);
                });
            } else {
                console.error("Error al cargar las comunas");
            }
        }
    };
    xhrComunas.send();
}