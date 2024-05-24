document.addEventListener('DOMContentLoaded', function() {

    var xhrDatosCandidatos = new XMLHttpRequest();
    xhrDatosCandidatos.open("GET","../Controlador/candidatoControlador.php?accion=obtenerCandidatosJSON", true);
    xhrDatosCandidatos.onreadystatechange = async function() {
        if (xhrDatosCandidatos.readyState === XMLHttpRequest.DONE) {
            if (xhrDatosCandidatos.status === 200) {
                    
                var candidatos = JSON.parse(xhrDatosCandidatos.responseText); // Convertir el JSON a un objeto JavaScript
                
                var divCandidatos = document.getElementById("candidatos");
                console.log(divCandidatos)

                divCandidatos.innerHTML = "";
                const tabla = await crearTablaCandidatos(divCandidatos)
                
                await llenarTablaCandidatos(tabla,candidatos)
                            
            } else {
                console.error("Error al cargar las personas");
            }
        }
    };
    xhrDatosCandidatos.send();
});

const crearTablaCandidatos = async (ubicacion) =>{
    const tabla = document.createElement('table');
    tabla.setAttribute('id','votacion-candidatos');

    const filaEncabezado = document.createElement('tr');
    const columnas = ['Nombre','Votos'];

    columnas.forEach(element =>{

        const encabezado = document.createElement('th');
        encabezado.textContent = element;
        filaEncabezado.appendChild(encabezado)
    })

    tabla.appendChild(filaEncabezado);

    ubicacion.appendChild(tabla);

    return tabla
}

const llenarTablaCandidatos = async (tabla,candidatos) =>{

    candidatos.forEach(element => {
        const fila = document.createElement('tr');
        const {nombre_candidato,votos} = element;
        const candidato = [nombre_candidato,votos];

        candidato.forEach(dato =>{
            const celda = document.createElement('td');
            celda.textContent = dato;
            fila.appendChild(celda)
        })

        tabla.appendChild(fila)

    });
    
}
