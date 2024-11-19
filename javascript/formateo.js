document.addEventListener('DOMContentLoaded', function () {
    const rutInputs = document.querySelectorAll('input[id^="rut"]'); // Selecciona todos los campos de RUT

    // Para cada campo de RUT encontrado en los formularios
    rutInputs.forEach((rutInput) => {
      const rutError = rutInput.nextElementSibling;

        rutInput.addEventListener('input', function () {
            let rut = rutInput.value.replace(/\./g, '').replace(/-/g, '').replace(/[^\dkK]/g, '');

            rut = rut.slice(0, 9);

            if (rut.length > 1) {
                // Formatear automáticamente el RUT con puntos y guion
                rut = rut.slice(0, -1).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + '-' + rut.slice(-1).toUpperCase();
            }

            rutInput.value = rut;

            // Validar y mostrar error si el RUT es inválido
            if (validarRUT(rutInput.value)) {
                rutError.style.display = 'none';
                rutInput.classList.remove('is-invalid');
                rutInput.classList.add('is-valid');
            } else {
                rutError.style.display = 'block';
                rutInput.classList.remove('is-valid');
                rutInput.classList.add('is-invalid');
            }
        });
    });

    // Función para validar RUT chileno
    function validarRUT(rut) {
        rut = rut.replace(/\./g, '').replace(/-/g, '');

        if (rut.length < 8 || rut.length > 9) {
            return false;
        }

        let cuerpo = rut.slice(0, -1);
        let dv = rut.slice(-1).toUpperCase();

        let suma = 0;
        let multiplo = 2;

        // Sumar los dígitos del cuerpo del RUT
        for (let i = cuerpo.length - 1; i >= 0; i--) {
            suma += multiplo * parseInt(cuerpo.charAt(i), 10);
            multiplo = multiplo === 7 ? 2 : multiplo + 1;
        }

        let dvEsperado = 11 - (suma % 11);
        if (dvEsperado === 11) dvEsperado = '0';
        else if (dvEsperado === 10) dvEsperado = 'K';
        else dvEsperado = dvEsperado.toString();

        return dv === dvEsperado;
    }
});

// Datos de usuario
document.addEventListener('DOMContentLoaded', function() {
    const tipoUsuarioSelect = document.getElementById('tipo_usuario');
    const particularContainer = document.getElementById('particularContainer');
    const empresaContainer = document.getElementById('empresaContainer');
  
    tipoUsuarioSelect.addEventListener('change', function() {
      if (tipoUsuarioSelect.value === 'PARTICULAR') {
        particularContainer.style.display = 'block';
        empresaContainer.style.display = 'none';
      } else if (tipoUsuarioSelect.value === 'EMPRESA') {
        particularContainer.style.display = 'none';
        empresaContainer.style.display = 'block';
      }
    });
  });

  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const showRegister = document.getElementById('showRegister');
  const showLogin = document.getElementById('showLogin');
  const tipoUsuario = document.getElementById('tipo_usuario');
  const empresaContainer = document.getElementById('empresaContainer');

  showRegister.addEventListener('click', (e) => {
    e.preventDefault();
    loginForm.classList.add('hidden');
    registerForm.classList.remove('hidden');
  });

  showLogin.addEventListener('click', (e) => {
    e.preventDefault();
    registerForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
  });

  tipoUsuario.addEventListener('change', function() {
    if (this.value === 'EMPRESA') {
        empresaContainer.classList.add('show'); // Muestra el contenedor
    } else {
        empresaContainer.classList.remove('show'); // Oculta el contenedor
    }
  });

  document.addEventListener('DOMContentLoaded', function() {
    const tipoUsuarioSelect = document.getElementById('tipo_usuario');
    const empresaContainer = document.getElementById('empresaContainer');
    const particularContainer = document.getElementById('particularContainer');
    const registerButton = document.querySelector('button[type="submit"]');

    tipoUsuarioSelect.addEventListener('change', function() {
      const selectedValue = this.value;

      if (selectedValue === 'EMPRESA') {
        empresaContainer.style.display = 'block';
        particularContainer.style.display = 'none';
        document.querySelectorAll('#empresaContainer input').forEach(input => {
            input.setAttribute('required', 'true'); // Agregar required
        });
        document.querySelectorAll('#particularContainer input').forEach(input => {
            input.removeAttribute('required'); // Quitar required
        });
      } else if (selectedValue === 'PARTICULAR') {
        empresaContainer.style.display = 'none';
        particularContainer.style.display = 'block';
        document.querySelectorAll('#particularContainer input').forEach(input => {
            input.setAttribute('required', 'true'); // Agregar required
        });
        document.querySelectorAll('#empresaContainer input').forEach(input => {
            input.removeAttribute('required'); // Quitar required
        });
      } else {
        empresaContainer.style.display = 'none';
        particularContainer.style.display = 'none';
      }

      // Mostrar el botón de registro cuando se selecciona un tipo de usuario válido
      if (selectedValue) {
        registerButton.style.display = 'block';
      } else {
        registerButton.style.display = 'none';
      }
      });
    });

//Formateo telefono
document.addEventListener('DOMContentLoaded', function() {  
  const phoneInputs = document.querySelectorAll('#fono_emp, #fonoUser');

  function formatPhoneNumber(event) {
      let phoneInput = event.target;
      let value = phoneInput.value.replace(/\D/g, '');

      if (!value.startsWith("56")) {
          value = "56" + value;
      }

      const maxDigits = 9;
      const phoneNumber = value.slice(2, 2 + maxDigits); 
      phoneInput.value = `+56 ${phoneNumber}`;
  }

  phoneInputs.forEach((phoneInput) => {
      if (phoneInput.value === "" || !phoneInput.value.startsWith("+56")) {
          phoneInput.value = "+56 ";
      }

      phoneInput.addEventListener('input', formatPhoneNumber);
      phoneInput.addEventListener('keydown', function(event) {
          const caretPosition = phoneInput.selectionStart;
          if (caretPosition <= 4 && (event.key === "Backspace" || event.key === "Delete")) {
              event.preventDefault();
          }
      });
  });
});

//Ojo password
document.querySelectorAll('.toggle-password').forEach(function(button) {
  button.addEventListener('mousedown', function() {
    const targetId = button.getAttribute('data-target');
    const passwordField = document.getElementById(targetId);
    const eyeIcon = button.querySelector('i');

    //visible
    passwordField.type = 'text';
    eyeIcon.classList.remove('bi-eye-slash');
    eyeIcon.classList.add('bi-eye');
  });

  button.addEventListener('mouseup', function() {
    const targetId = button.getAttribute('data-target');
    const passwordField = document.getElementById(targetId);
    const eyeIcon = button.querySelector('i');

    // oculta
    passwordField.type = 'password';
    eyeIcon.classList.remove('bi-eye');
    eyeIcon.classList.add('bi-eye-slash');
  });

  // para dispositivos touch
  button.addEventListener('touchstart', function() {
    const targetId = button.getAttribute('data-target');
    const passwordField = document.getElementById(targetId);
    const eyeIcon = button.querySelector('i');

    //visible
    passwordField.type = 'text';
    eyeIcon.classList.remove('bi-eye-slash');
    eyeIcon.classList.add('bi-eye');
  });

  button.addEventListener('touchend', function() {
    const targetId = button.getAttribute('data-target');
    const passwordField = document.getElementById(targetId);
    const eyeIcon = button.querySelector('i');

    //oculta
    passwordField.type = 'password';
    eyeIcon.classList.remove('bi-eye');
    eyeIcon.classList.add('bi-eye-slash');
  });
});

// Mostrar mensaje de éxito si el parámetro "registro=exitoso" está en la URL
document.addEventListener('DOMContentLoaded', function() {
  const urlParams = new URLSearchParams(window.location.search);

  // Mostrar mensaje de éxito si el parámetro "registro=exitoso" está presente
  if (urlParams.has('registro') && urlParams.get('registro') === 'exitoso') {
      Swal.fire({
          icon: 'success',
          title: 'Registro exitoso',
          text: '¡Tu cuenta ha sido creada con éxito!',
          confirmButtonText: 'Aceptar'
      }).then(() => {
          // Eliminar el parámetro "registro" de la URL después de mostrar el mensaje
          const newUrl = window.location.origin + window.location.pathname;
          window.history.replaceState({}, document.title, newUrl);
      });
  }

  // Procesar el formulario de registro
  const registerForm = document.getElementById('registerForm');
  registerForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      const formData = new FormData(registerForm);

      try {
          const response = await fetch('controlador/controlador_registro.php', {
              method: 'POST',
              body: formData
          });

          const htmlResponse = await response.text();
          console.log(htmlResponse); // Para verificar la respuesta en consola

          // Insertar el HTML de respuesta en un contenedor de errores o éxito
          let messageContainer = document.getElementById('messageContainer');
          if (!messageContainer) {
              messageContainer = document.createElement('div');
              messageContainer.id = 'messageContainer';
              registerForm.insertBefore(messageContainer, registerForm.firstChild);
          }
          messageContainer.innerHTML = htmlResponse;

          // Redirigir en caso de éxito
          if (htmlResponse.includes('Registro exitoso')) {
              window.location.href = 'login.php?registro=exitoso';
          }

      } catch (error) {
          console.error('Error:', error);
      }
  });
});

//Bloquear boton registro
document.addEventListener("DOMContentLoaded", function () {
  const tipoUsuarioSelect = document.getElementById("tipo_usuario");
  const registerButton = document.getElementById("registerButton");

  // Agregar atributo disabled al botón al cargar la página
  registerButton.disabled = true;

  // Habilitar o deshabilitar el botón según la selección
  tipoUsuarioSelect.addEventListener("change", function () {
      if (tipoUsuarioSelect.value !== "") {
          registerButton.disabled = false;
      } else {
          registerButton.disabled = true;
      }
  });
});