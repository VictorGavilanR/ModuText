<?php
// Incluir el controlador que calcula los kilos
include 'controlador/controlador_kilos.php'; 

// Acceder al total de kilos desde la sesión
//$totalKilos = isset($_SESSION['total_kilos']) ? $_SESSION['total_kilos'] : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Radio+Canada:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>ModuTex</title>
</head>
<body>
    <div class="background-container">
        <header class="header" id="inicio">
            <a href="#" class="logo">
                <img src="img/Logo - Color.png" alt="logo-web">
            </a>
      
            <nav class="navbar">
                <li><a href="index.html" style="--i:1;">Inicio</a></li>
                <li><a href="#nosotros" style="--i:3;">Nosotros</a></li>
                <li><a href="#objetivo" style="--i:4;">Objetivos</a></li>
                <li><a href="#contacto" style="--i:5;">Contacto</a></li>
                <a href="login.php" class="user ocultar"><i class='bx bxs-user' ></i>Iniciar Sesión</a>
            </nav>
      
            <div class="main">
                <a href="login.php" class="user pc"><i class='bx bxs-user' ></i>Iniciar Sesión</a>
                <div class="bx bx-menu" id="menu-icon"></div>
            </div>   
        </header>
      
        <div class="container">
            <div class="text-content">
                <h1>¡Bienvenido a ModuTex!</h1>
                <p>Aquí transformamos residuos textiles en soluciones innovadoras para la construcción y diseño.</p>
                <a href="#nosotros" class="card-button-home">
                    Saber Más
                    <svg class="arrow-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </a>
            </div>
            <div class="img-content">
                
                <img src="img/imagen-inicio-morado.png" alt="Descripción de la imagen" >
            </div>
        </div>
    </div>
    

    <div class="contenedor" id="nosotros">
        <!-- Slide 1 -->
        <div class="contenedor__slide contenedor__slide--activo">
          <div class="contenedor__imagen">
            <img src="img/corporate-business-people.jpg" alt="Imagen 1">
          </div>
          <div class="contenedor__texto">
            <h2 class="contenedor__titulo">¿Quiénes somos?</h2>
            <p class="contenedor__descripcion">   <span class="morado">En Modutex</span>, somos una empresa comprometida con la <span class="morado">innovación</span>. Nacimos para enfrentar la <span class="morado">problemática de los residuos textiles</span>, buscando soluciones que contribuyan a la <span class="morado">economía circular</span>. A través de un <span class="morado">proceso avanzado de reciclaje</span> y un <span class="morado">ecosistema digital</span> para la gestión eficiente de residuos, transformamos desechos en productos útiles y responsables con el medio ambiente. Nuestro <span class="morado">material termoacústico</span>, elaborado a partir de <span class="morado">textiles reciclados</span>, es una muestra de nuestro compromiso con la sostenibilidad, ofreciendo soluciones para la <span class="morado">construcción y decoración de interiores</span>, alineadas con nuestros valores de <span class="morado">diseño y responsabilidad ambiental</span>.

          </div>
        </div>
    
        <!-- Slide 2 -->
        <div class="contenedor__slide">
          <div class="contenedor__imagen">
            <img src="img/historia.jpg" alt="Imagen 2">
          </div>
          <div class="contenedor__texto">
            <h2 class="contenedor__titulo">Historia</h2>
            <p class="contenedor__descripcion">   <span class="morado">Modutex</span> nació para enfrentar la creciente problemática de los <span class="morado">residuos textiles</span> generados por la industria y el comercio, agravada por el <span class="morado">'fast fashion'</span>, como se observa en el <span class="morado">Desierto de Atacama</span>. Creamos un <span class="morado">ecosistema digital</span> para gestionar residuos y un <span class="morado">proceso mecánico-químico</span> que transforma los desechos en <span class="morado">productos sostenibles</span>. Nuestro <span class="morado">material innovador</span>, un <span class="morado">revestimiento termoacústico</span> hecho de <span class="morado">textiles reciclados</span>, es ideal para la <span class="morado">construcción y decoración interior</span>, promoviendo proyectos <span class="morado">sostenibles</span> y <span class="morado">responsables con el medio ambiente</span>.

          </div>
        </div>
    
        <!-- Navegación con puntitos -->
        <div class="contenedor__navegacion">
          <span class="contenedor__punto contenedor__punto--activo" data-slide="0"></span>
          <span class="contenedor__punto" data-slide="1"></span>
        </div>
      </div>

    <!--Vision - Mision -->
    <div class="mision-vision ">
        <div class="card card--izquierda">
            <div class="mision-vision-contenedor">
                <a href="#">
                    <img class="card-img" src="img/mision.png" alt="" />
                </a>
            </div>

            <div class="card-content ">
                <a href="#">
                    <h5 class="card-title">Misión</h5>
                </a>
                <p class="card-description">Nuestra misión es reducir el impacto ambiental de la industria textil a través de la revalorización de residuos textiles. Transformamos desechos en revestimientos sólidos y duraderos, contribuyendo a una economía circular y ayudando a las empresas a cumplir con las normativas ambientales."</p>
            </div>
        </div>
    
        <div class="card card--derecha  ">
            <div class="mision-vision-contenedor">
                    <img class="card-img" src="img/vision.png" alt="" />
            </div>
            <div class="card-content">
                <a href="#">
                    <h5 class="card-title">Visión</h5>
                </a>
                <p class="card-description">Queremos crear un futuro donde todos los residuos textiles sean recuperados y reutilizados, promoviendo una cultura de responsabilidad y sostenibilidad, a nivel Socio-espacial y económico.</p>
            </div>
        </div>
    </div>

    <!--PROPOSITO-->

    <div class="pagina-proposito-texto" id="objetivo">
        <h4><span class="color-proposito">NUESTRO PROPÓSITO</span></h4>
        <hr class="pagina-proposito-texto-hr">
        <p>    El propósito de <span class="morado">Modutex</span> es <span class="morado">cerrar el ciclo de los residuos textiles</span>, dándoles una <span class="morado">segunda vida útil</span>. Queremos <span class="morado">transformar el problema de los residuos en una oportunidad</span> para <span class="morado">mejorar el medio ambiente</span>, <span class="morado">reducir costos operativos</span> y <span class="morado">fomentar prácticas empresariales responsables</span>.
        </p>

    </div>
    
    <!-- Time Line -->
    <ol class="items-center" id="objetivos">
        <li class="item">
            <div class="flex-container">
                <div class="icon-container">
                    <svg class="icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                    </svg>
                </div>
                <div class="line"></div>
            </div>
            <div class="content">
                <h3 class="title">Objetivo 1</h3>
                <time class="time">Implementación del Ecosistema Digital</time>
                <p class="description">
                    •    Diagnóstico de reducción de huella de carbono
                        <br>
                    •	Desarrollo de la plataforma digital
                        <br>
                    •	Integración de tecnologías de trazabilidad
                        <br>
                    •	Optimización de la logística inversa
                    </p>
            </div>
        </li>
        <li class="item">
            <div class="flex-container">
                <div class="icon-container">
                    <svg class="icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                    </svg>
                </div>
                <div class="line"></div>
            </div>
            <div class="content">
                <h3 class="title">Objetivo 2</h3>
                <time class="time">Optimización y Validación del Prototipo</time>
                <p class="description">
                    •	Mejora del prototipo
                    <br>
                    •	Pruebas de laboratorio avanzadas                        
                    <br>
                    •	Validación en condiciones reales
                </p>
            </div>
        </li>

        

        <li class="item">
            <div class="flex-container">
                <div class="icon-container">
                    <svg class="icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                    </svg>
                </div>
                <div class="line"></div>
            </div>
            <div class="content">
                <h3 class="title">Objetivo 3</h3>
                <time class="time">Capacitación y Sensibilización</time>
                <p class="description">
                    •	Programa de capacitación a municipalidades
                        <br>
                    •	Campañas de sensibilización
                        <br>
                    </p>
            </div>
        </li>
    </ol>

    
    <!--Contador-->

    <div class="counter-section">
    <h2 class="section-title">Nuestras Cifras</h2>
    <hr class="separador">

    <div class="counter-container">
        <div class="counter-item">
            <h3>Telas Recogidas</h3>
            <div class="counter" data-target="<?php echo $_SESSION['total_kilos']; ?>">0<span class="unit"> kg</span></div>
        </div>
        <div class="counter-item">
            <h3>Huella de Carbono</h3>
            <div class="counter" data-target="<?php echo $_SESSION['huella_carbono']; ?>">0<span class="unit"> CO₂</span></div>
        </div>
    </div>
</div>

    
<!-- Contacto -->
<div class="cont2 mt-5" id="contacto">
    <h2 class="text-center mb-4">Contáctanos</h2>
    <p class="text-center description mb-5">
        Si tienes alguna pregunta o deseas saber más sobre nuestros servicios, no dudes en comunicarte con nosotros.
        Estaremos encantados de ayudarte.
    </p>

    <div class="contact-form">
        <!-- Removemos el action y agregamos un id al formulario -->
        <form id="contactForm" method="POST">
          <label for="name">Nombre:</label>
          <input type="text" id="name" name="name" required>
          
          <label for="email">Correo:</label>
          <input type="email" id="email" name="email" required>
          
          <label for="phone">Teléfono:</label>
          <input type="tel" id="phone" name="phone" required>
          
          <label for="message">Mensaje:</label>
          <textarea id="message" name="message" required></textarea>
          
          <button type="submit">Enviar Correo</button>
        </form>
        
        <!-- Agregamos un div para mostrar mensajes -->
        <div id="formMessage" class="mt-3" style="display: none;"></div>
    </div>

    <!-- El resto del código permanece igual -->
    <div class="contact-info mt-5 text-center">
    <ul class="contact-list">
    <li>
        <a href="https://www.google.com/maps?q=Bartolomé+del+Pozo+259+N°22,+Concepción" target="_blank">
            <i class='bx bxs-map' style='color:#5f288f'></i> Bartolomé del Pozo 259 N°22, Concepción
        </a>
    </li>
    <li>
        <a href="tel:+56935541069">
            <i class='bx bxs-phone' style='color:#5f288f'></i> +56935541069
        </a>
    </li>
    <li>
        <a href="mailto:modulartex@gmail.com">
            <i class='bx bx-envelope' style='color:#5f288f'></i> modulartex@gmail.com
        </a>
    </li>
</ul>


        <div class="social-media mt-4">
            
          <!--  <a href="#" class="social-icon"><i class='bx bxl-facebook' style='color:#5f288f'></i></a> -->
            
            <a href="https://www.instagram.com/modutex_biobio/" target="_blank" class="social-icon"><i class='bx bxl-instagram-alt' style='color:#5f288f'></i></a>
            <a href="https://www.linkedin.com/in/modular-tex-37533a319/" target="_blank" class="social-icon"><i class='bx bxl-linkedin' style='color:#5f288f'></i></a>
        </div>
    </div>
</div>





<!--FOOTER-->
     <footer class="footer-area">

        <div class="main-footer">
            <div class="footer">
                <div class="single-footer">
                    <img src="img/Marca - Blanco.png" alt="logo-blanco-footer">

                    <p>Soluciones y diseños sostenibles.</p>
                    <div class="footer-social">
                        <a href="https://www.instagram.com/modutex_biobio/" target="_blank"><i class='bx bxl-instagram' ></i></a>
                        <a href="https://www.linkedin.com/in/modular-tex-37533a319/" target="_blank"><i class='bx bxl-linkedin' ></i></a>
                    </div>
                </div>
                <div class="single-footer single-footer-menu">
                    <h4>Menú</h4>
                    <ul>
                        <li><a href="#inicio"><i class='bx bx-chevron-right' > </i>Inicio</a></li>
                        <li><a href="#nosotros"><i class='bx bx-chevron-right' ></i>Nosotros</a></li>
                        <li><a href="#objetivo"><i class='bx bx-chevron-right' ></i>Objetivos</a></li>
                        <li><a href="#contacto"><i class='bx bx-chevron-right' ></i>Contacto</a></li>
                    </ul>
                </div>
                <div class="single-footer single-footer-contacto">
                    <h4>Contacto</h4>
                    <ul>
                        <li>
                            <i class='bx bx-map'></i>
                            <a href="https://www.google.com/maps?q=Bartolomé+del+Pozo+259+N°22,+Concepción" target="_blank">
                                Bartolomé del Pozo 259 N°22, Concepción
                            </a>
                        </li>
                        <li>
                            <i class='bx bx-mobile'></i>
                            <a href="tel:+56935541069">
                                +56935541069
                            </a>
                        </li>
                        <li>
                            <i class='bx bx-envelope'></i>
                            <a href="mailto:modulartex@gmail.com">
                                modulartex@gmail.com
                            </a>
                        </li>
                        <li>
                            <i class='bx bx-globe'></i>
                            <a href="https://www.modutex.com" target="_blank">
                                www.modutex.com
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="copy">
            <p>&copy; 2024 todos los derechos reservados</p>
        </div>
    </div>
</footer>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <!-- scritp contacto -->
   
    <script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Mostrar un SweetAlert2 con una imagen personalizada de carga con rotación
    Swal.fire({
        title: 'Enviando...',
        html: `
            <div style="display: flex; justify-content: center; align-items: center; overflow: hidden;">
                <img src="img/Marca - Color.png" alt="Cargando" 
                    style="width: 50px; height: 50px; animation: spin 2s linear infinite;">
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false,
        customClass: {
            popup: 'no-scroll-popup'
        }
    });

    fetch('enviar_correo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Cerrar el SweetAlert2
        Swal.close();

        // Mostrar mensaje basado en la respuesta del servidor
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                confirmButtonText: 'OK',
                timer: 5000
            });
            document.getElementById('contactForm').reset(); // Limpiar el formulario
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'OK',
                timer: 5000
            });
        }
    })
    .catch(error => {
        // Cerrar el SweetAlert2
        Swal.close();

        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error en el envío. Por favor, intenta nuevamente.',
            confirmButtonText: 'OK',
            timer: 5000
        });
    });
});
</script>

<style>
@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Evitar desbordamiento y barra de scroll */
.no-scroll-popup {
    overflow: hidden;
    max-width: 100%;
    max-height: 100%;
    padding: 0;
}

.swal2-popup {
    margin: 0 auto; /* Centrado en el viewport */
}
</style>

</body>
</html>