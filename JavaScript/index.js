//Funcion para mostrar y ocultar la contraseña
document.addEventListener('DOMContentLoaded', function() {
    console.log('Js Cargado');

    // Obtener los elementos del DOM
    var showPassword = document.getElementById('show-password');
    var hidePassword = document.getElementById('hide-password');
    var passwordField = document.getElementById('contraseña');

    // Inicializar los botones de mostrar y ocultar contraseña
    showPassword.addEventListener('click', function (e) {
        e.preventDefault();
        console.log('mostrar password');
        passwordField.type = 'text';
        showPassword.style.display = 'none';
        hidePassword.style.display = 'block';
    });

    hidePassword.addEventListener('click', function (e) {
        e.preventDefault();
        console.log('no mostrar clicked');
        passwordField.type = 'password';
        hidePassword.style.display = 'none';
        showPassword.style.display = 'block';
    });
});

//Animaciones con ScrollReveal
//Configuración de ScrollReveal para animaciones al hacer scroll
ScrollReveal().reveal('.animation', {
    duration:2000,
    origin: 'bottom',
    distance: '-100px',
});