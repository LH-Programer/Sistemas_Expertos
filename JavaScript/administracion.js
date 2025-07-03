
// Funcion para preguntar antes de eliminar Bodega
function eliminar() {
    var respuesta = confirm("¿Esta seguro que quiere eliminar la bodega? este cambio sera irreversible");
    return respuesta;
}

//Funcion para preguntar antes de modificar los datos de la Bodega
function modificar(){
    var respuesta = confirm("¿Esta seguro de modificar los datos de la bodega?")
    return respuesta;
}

//Popup agregar bodegas
overlay = document.getElementById('overlay'),
popup = document.getElementById('popup'),
btnCerrarPopup = document.getElementById('btn-cerrar-popup');

function btnAbrirPopup(){
    overlay.classList.add('active')
    popup.classList.add('active')
};

// Cerrar popup cuando se clicke la x
btnCerrarPopup.addEventListener('click', function(){
    overlay.classList.remove('active')
    popup.classList.remove('active')
});

// Cerrar el popup cuando se haga clic fuera del formulario
overlay.onclick = function(event) {
    if (event.target == overlay) {
        overlay.classList.remove('active')
        popup.classList.remove('active')
    }
}

//Popup editar bodegas
overlay1 = document.getElementById('overlay1'),
popup1 = document.getElementById('popup1'),
btnCerrarPopup1 = document.getElementById('btn-cerrar-popup1');

function btnAbrirPopup1(){
    overlay1.classList.add('active')
    popup1.classList.add('active')
};

// Cerrar popup cuando se clicke la x
btnCerrarPopup1.addEventListener('click', function(){
    overlay1.classList.remove('active')
    popup1.classList.remove('active')
});

// Cerrar el popup cuando se haga clic fuera del formulario
overlay1.onclick = function(event) {
    if (event.target == overlay1) {
        overlay1.classList.remove('active')
        popup1.classList.remove('active')
    }
}

//Popup agregar encargado
overlay2 = document.getElementById('overlay2'),
popup2 = document.getElementById('popup2'),
btnCerrarPopup2 = document.getElementById('btn-cerrar-popup2');

function btnAbrirPopup2(){
    overlay2.classList.add('active')
    popup2.classList.add('active')
};

// Cerrar popup cuando se clicke la x
btnCerrarPopup2.addEventListener('click', function(){
    overlay2.classList.remove('active')
    popup2.classList.remove('active')
});

// Cerrar el popup cuando se haga clic fuera del formulario
overlay2.onclick = function(event) {
    if (event.target == overlay2) {
        overlay2.classList.remove('active')
        popup2.classList.remove('active')
    }
}

let inputBox = document.getElementsByClassName('inputBox')

function cambiarEstiloReadonly() {
    // Selecciona todos los inputs con el atributo readonly
    const inputs = document.querySelectorAll('input[readonly]');
    inputs.forEach(input => {
        input.style.color = '#8f8f8f';           // Color de texto
    });
}

// Automatizar el RUN
document.getElementById('run').addEventListener('input', function(e) {
    let valor = e.target.value.replace(/\./g, '').replace(/-/g, ''); // Quita puntos y guiones
    if (valor.length > 1) {
        // Inserta el guion antes del último dígito
        valor = valor.slice(0, -1) + '-' + valor.slice(-1);
    }
    e.target.value = valor;
});

// Llama a la función al cargar la página
window.onload = cambiarEstiloReadonly;


//animaciones
ScrollReveal().reveal('h1', {
    duration: 1000,
    origin: 'bottom',
    distance: '-100px'
});

ScrollReveal().reveal('p', {
    duration: 1000,
    origin: 'left',
    distance: '-100px'
});
ScrollReveal().reveal('h2', {
    duration: 1000,
    origin: 'right',
    distance: '-100px'
});

ScrollReveal().reveal('h3', {
    duration: 1000,
    origin: 'left',
    distance: '-100px'
});