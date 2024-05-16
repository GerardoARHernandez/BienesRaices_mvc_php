document.addEventListener('DOMContentLoaded', function(){
 
    EventListeners();
    darkMode();
})

function darkMode() {
    const preferirDarkMode = window.matchMedia('(prefers-color-scheme: dark)');
    // console.log(preferirDarkMode.matches);

    if (preferirDarkMode.matches) {
        document.body.classList.add('dark-mode');
    }else{
        document.body.classList.remove('dark-mode');
    }

    preferirDarkMode.addEventListener('change', function () {
        if (preferirDarkMode.matches) {
            document.body.classList.add('dark-mode');
        }else{
            document.body.classList.remove('dark-mode');
        }
    })

    const botonDarkMode = document.querySelector('.dark-mode-boton');

    botonDarkMode.addEventListener('click', function () {
        document.body.classList.toggle('dark-mode')
    })
}

function EventListeners() {
    const mobileMenu = document.querySelector('.mobile-menu');

    mobileMenu.addEventListener('click', navegacionResponsive)

    //Muestra campos condicionales
    const metodoContacto = document.querySelectorAll('input[name="contacto[contacto]"]');
    metodoContacto.forEach(input =>  input.addEventListener('click', mostrarMetodosContacto));
}

function navegacionResponsive() {
    const navegacion = document.querySelector('.navegacion');

    navegacion.classList.toggle('mostrar');
//     if (navegacion.classList.contains('mostrar')) {
//         navegacion.classList.remove('mostrar');
//     }else{
//         navegacion.classList.add('mostrar');
//     }
}

function mostrarMetodosContacto(e) {
    const contactoDiv = document.querySelector('#contacto');

    if(e.target.value == 'telefono'){
        contactoDiv.innerHTML = `
            <label for="telefono">Teléfono:</label>
            <input type="tel" placeholder="Tu Teléfono" id="telefono" name="contacto[telefono]">

            <p>Elija la fecha y hora para llamada:</p>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="contacto[fecha]">

            <label for="hora">Hora:</label>
            <input type="time" id="hora" min="9:00" max="18:00" name="contacto[hora]">
        `;
    }else{
        contactoDiv.innerHTML = `
            <label for="email">Correo:</label>
            <input type="email" placeholder="Tu Correo" id="email" name="contacto[email]" required>
        `;
    }

}