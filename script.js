let menu = document.querySelector('#menu-icon');
let navbar = document.querySelector('.navbar');

menu.onclick = () => {
    menu.classList.toggle('bx-x');
    navbar.classList.toggle('open');
}

/*Contador*/

document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".counter");
    
    counters.forEach(counter => {
        counter.innerText = '0';
        
        const updateCounter = () => {
            const target = +counter.getAttribute('data-target'); // Convertir a número
            const current = +counter.innerText;
            const increment = target / 200; // Ajusta este valor para controlar la velocidad
            
            if(current < target) {
                counter.innerText = Math.ceil(current + increment);
                setTimeout(updateCounter, 10); // Controla la velocidad de actualización
            } else {
                counter.innerText = target;
            }
        };
        
        updateCounter();
    });
});