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

/*SLIDE*/

// Variables de los elementos
const slides = document.querySelectorAll('.contenedor__slide');
const puntos = document.querySelectorAll('.contenedor__punto');

let indiceActual = 0;
const intervaloTiempo = 3000; // Tiempo en milisegundos (3 segundos)

// Función para cambiar de slide
function cambiarSlide(indice) {
  // Ocultar el slide actual
  slides[indiceActual].classList.remove('contenedor__slide--activo');
  puntos[indiceActual].classList.remove('contenedor__punto--activo');

  // Mostrar el nuevo slide
  slides[indice].classList.add('contenedor__slide--activo');
  puntos[indice].classList.add('contenedor__punto--activo');

  // Actualizar el índice actual
  indiceActual = indice;
}

// Cambiar el slide automáticamente cada 3 segundos
function iniciarCarrusel() {
  setInterval(() => {
    let nuevoIndice = (indiceActual + 1) % slides.length; // Mover al siguiente slide
    cambiarSlide(nuevoIndice);
  }, intervaloTiempo);
}

// Agregar eventos a los puntos
puntos.forEach((punto, index) => {
  punto.addEventListener('click', () => cambiarSlide(index));
});

// Iniciar el carrusel automáticamente
iniciarCarrusel();
