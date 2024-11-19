let menu = document.querySelector('#menu-icon');
let navbar = document.querySelector('.navbar');
let user = document.querySelector('.user'); // Seleccionamos el usuario también

menu.onclick = () => {
    menu.classList.toggle('bx-x');
    navbar.classList.toggle('open');
    user.classList.toggle('open'); // Añadimos la clase .open a user
}


/*Contador*/
document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".counter");
  
  counters.forEach(counter => {
      const target = parseInt(counter.getAttribute('data-target')); // Convertir a entero
      const unitSpan = counter.querySelector(".unit"); // Encontrar el span de la unidad
      const originalUnit = unitSpan.innerText; // Guardar el texto de la unidad
      unitSpan.innerText = ""; // Temporalmente vaciar el contenido de la unidad

      let current = 0;

      const updateCounter = () => {
          const increment = Math.ceil(target / 200); // Controla la velocidad
          if (current < target) {
              current += increment;
              counter.innerHTML = `${current}${originalUnit}`;
              setTimeout(updateCounter, 10); // Velocidad de animación
          } else {
              counter.innerHTML = `${target}${originalUnit}`;
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
const intervaloTiempo = 8000;//time

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


// Animaciones de entrada 
// Selecciona todos los elementos que necesitan animaciones de entrada
const contenedor = document.querySelector('.contenedor');
const cards = document.querySelectorAll('.card');
const counterSection = document.querySelector('.counter-section');
const proposito = document.querySelector('.pagina-proposito-texto');
const objetivos = document.querySelectorAll('.item');
const contacto = document.querySelector('.cont2');

// Configura el observer para detectar cuando los elementos son visibles
const observer = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      // Agrega la clase de visibilidad según el tipo de elemento
      if (entry.target.classList.contains('contenedor')) {
        entry.target.classList.add('contenedor--visible');
      } else if (entry.target.classList.contains('card')) {
        entry.target.classList.add('card--visible');
      } else if (entry.target.classList.contains('counter-section')) {
        entry.target.classList.add('counter-section--visible');
      } else if (entry.target.classList.contains('pagina-proposito-texto')) {
        entry.target.classList.add('pagina-proposito-texto--visible');
      } else if (entry.target.classList.contains('item')) {
        entry.target.classList.add('item--visible');
      } else if (entry.target.classList.contains('cont2')) {
        entry.target.classList.add('cont2--visible');
      }
      observer.unobserve(entry.target); // Deja de observar una vez que se activa la animación
    }
  });
}, { threshold: 0.01 }); // Se activará cuando el 1% del elemento sea visible

// Asocia el observer a cada elemento
observer.observe(contenedor);
cards.forEach(card => observer.observe(card));
observer.observe(counterSection);
observer.observe(proposito);
objetivos.forEach(objetivo => observer.observe(objetivo));
observer.observe(contacto);


// Mensaje de contacto
