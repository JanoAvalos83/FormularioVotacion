document.addEventListener('DOMContentLoaded', () => {
    const formularioPersona = document.querySelector('#formularioPersona');

    formularioPersona.addEventListener('submit', async (evt)=>{
        evt.preventDefault()
        
        const datos = await datosFormularioPersona();
        
        // if(validarCamposvacios(datos) == false){

            // console.log('no hay campos vacios')
            // if(validarEmail(datos.email)){
            // console.log(' paso email')
                
                // if(validarRut(datos.rut)){
                // console.log(' paso rut')
                    
                    // const datosEnvio = await convertirCamposStringNumero(datos);
                    await enviarDatos(datos);
                    console.log('envio datos')
                    
        //         }else{
        //             evt.preventDefault()
        //             alert('Formato rut no valido')
        //         }
        //     }else{
        //         evt.preventDefault();
        //         alert('Formato correo no valido')
        //     }
                       
        // }else{
        //     evt.preventDefault()
        //     alert('Todos los campos son obligatorios')
        // }
        
    })

})


const datosFormularioPersona = async () =>{
    const nombreApellido = document.querySelector("#nombreApellido");
    const alias = document.querySelector("#alias");
    const rut = document.querySelector("#rut");
    const email = document.querySelector("#email");
    const region = document.querySelector("#region");
    const comuna = document.querySelector("#comuna");
    const id_candidato = document.querySelector("#candidato");
    const comoEntero = document.getElementsByName("comoEntero");

    let seleccionComoEntero = [];

    for(var i = 0; i < comoEntero.length;i++){
        if(comoEntero[i].checked){
            let valor = parseInt(comoEntero[i].value)
            seleccionComoEntero.push(valor)
        }
    }

    const aux = {
        nombreApellido: nombreApellido.value.trim(),
        alias: alias.value.trim(),
        rut: rut.value.trim(),
        email: email.value.trim(),
        region: parseInt(region.value),
        comuna: parseInt(comuna.value),
        id_candidato: parseInt(id_candidato.value),
        comoEntero: seleccionComoEntero
    }
    return aux;
}

const validarCamposvacios = (datos) =>{
    let camposNulos = [];
    for (let propiedad in datos) {
        if (!datos[propiedad] || datos[propiedad] === 0 || datos[propiedad] === "" || datos[propiedad].length === 0) {
            camposNulos.push(`${propiedad}`)
        }
    }
    if(camposNulos.length > 0){
        alert(`Error al ingresar datos campos nulos "${camposNulos}"`);
        return true;
    }
    return false;

}

const validarEmail = (email) => {
    const expresionRegular = /^[^\s@]+(\.[^\s@]+)*@[^\s@]+\.[^\s@]+$/;
    if(expresionRegular.test(email)){
        return expresionRegular.test(email);
    }else{    
        return false
    }
}


const enviarDatos = async (datos) => {
    try {
        fetch('../Controlador/procesarFormulario.php',{
        method: 'POST',
        headers: {
            'Content-Type': 'aplication/json'
        },
        body: JSON.stringify(datos)
        })
        .then(async  response =>{
            if(response.ok){
                const datos = await response.json()
                return datos;
            }
            throw new Error('Error en la solicitud',response.statusText);
        })
        .then( data =>{
            if(data.error){
                alert(data.message);
            }
        })
    }catch(error){
        console.log('error');
        alert('Error: '+error.message)
    }
}






const calcularDigitoVerificador = (rutNumerico) => {
    
    let suma = 0;
    let multiplicador = 2;

    const longitud = rutNumerico.toString().length;
    let aux = rutNumerico;
    const numeroinvetido = [];
    const numeroinvetidoXSec = []
    //invertir numero
    for (let i = 0; i < longitud; i++) {
        const element = aux%10;
        aux = parseInt(aux/10);
        numeroinvetido.push(element);        
    }

    //multiplicar desde 2 hasta el 7
    for (let i = 0; i < numeroinvetido.length; i++) {
        if(multiplicador > 7){
            multiplicador = 2;
            const resultado = numeroinvetido[i]*multiplicador
            numeroinvetidoXSec.push(resultado);
            multiplicador = multiplicador+1;

        }else{
            const resultado = numeroinvetido[i]*multiplicador;
            multiplicador = multiplicador+1;
            numeroinvetidoXSec.push(resultado)            
        }
        
    }
    
    numeroinvetidoXSec.forEach(element =>{
        suma = suma+element;
    })
    
    const resto11 = parseInt(suma/11);
    
    const resto11X11 = resto11*11
    const diferenciaSumaResto11X11 = parseInt(suma-resto11X11)

    const dvaux = 11- diferenciaSumaResto11X11

    let dv= dvaux;

    if(dvaux === 11){
        dv = 0;
    }
    if(dvaux === 10){
        dv = 10;
    }
    //controlar dv = 10 como dv = K cuando se compare dv ingresado con dv proporcionado
    return dv
};

const validarRut = (rut) => {
    
    if(rut.indexOf('-') === -1){
        return false;
    }
    // Separar el RUT y el dígito verificador

    let [rutNumerico, digitoVerificador] = rut.split('-');
    
    if(digitoVerificador.length > 1 || rutNumerico.length > 8 || rutNumerico.length < 7){
        return false;
    }

    rutNumerico = parseInt(rutNumerico);

    if(digitoVerificador != 'K'){
        digitoVerificador = parseInt(digitoVerificador)
    }

    if(digitoVerificador == 'k'){
        digitoVerificador = digitoVerificador.toUpperCase();
    }

    // Calcular el dígito verificador esperado
    let digitoVerificadorCalculado = calcularDigitoVerificador(rutNumerico);

    if(digitoVerificadorCalculado === 10){
        digitoVerificadorCalculado = 'K';
    }
    // Comparar el dígito verificador calculado con el proporcionado

    return digitoVerificadorCalculado === digitoVerificador;
    
}


