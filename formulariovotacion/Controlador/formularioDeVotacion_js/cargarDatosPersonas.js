document.addEventListener('DOMContentLoaded', function() {

    var xhrDatosPersonas = new XMLHttpRequest();
    xhrDatosPersonas.open("GET","../Controlador/DatosPersonasControlador.php?accion=obtenerpersonasJSON", true);
    xhrDatosPersonas.onreadystatechange = async function() {
        if (xhrDatosPersonas.readyState === XMLHttpRequest.DONE) {
            if (xhrDatosPersonas.status === 200) {
                    
                var personas = JSON.parse(xhrDatosPersonas.responseText); // Convertir el JSON a un objeto JavaScript
                
                var divPersonas = document.getElementById("personas");
                console.log(divPersonas)

                divPersonas.innerHTML = "";
                const tabla = await crearTablaPersonasRegistradas(divPersonas)
                
                await llenarTablaPersonasRegistradas(tabla,personas)
                            
            } else {
                console.error("Error al cargar las personas");
            }
        }
    };
    xhrDatosPersonas.send();
});

const crearTablaPersonasRegistradas = async (ubicacion) =>{
    const tabla = document.createElement('table');
    tabla.setAttribute('id','personasRegistradas');

    const filaEncabezado = document.createElement('tr');
    const columnas = ['Nombre', 'RUT', 'Email', 'Comuna', 'Cómo se enteró'];

    columnas.forEach(element =>{

        const encabezado = document.createElement('th');
        encabezado.textContent = element;
        filaEncabezado.appendChild(encabezado)
    })

    tabla.appendChild(filaEncabezado);

    ubicacion.appendChild(tabla);

    return tabla
}

const llenarTablaPersonasRegistradas = async (tabla,personas) =>{

    personas.forEach(element => {
        const fila = document.createElement('tr');
        const {nombre_apellido, rut,email,nombre_comuna,nombre_entero} = element;
        const persona = [nombre_apellido, rut,email,nombre_comuna,nombre_entero];

        persona.forEach(dato =>{
            const celda = document.createElement('td');
            celda.textContent = dato;
            fila.appendChild(celda)
        })

        tabla.appendChild(fila)

    });
    
}
