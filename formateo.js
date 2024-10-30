document.addEventListener('DOMContentLoaded', function () {
    const rutInputs = document.querySelectorAll('input[id^="rut"]'); // Selecciona todos los campos de RUT
    const rutErrors = document.querySelectorAll('#rutError'); // Selecciona el elemento de error

    // Para cada campo de RUT encontrado en los formularios
    rutInputs.forEach((rutInput, index) => {
        let rutError = rutErrors[index];

        rutInput.addEventListener('input', function () {
            let rut = rutInput.value.replace(/\./g, '').replace(/-/g, '').replace(/[^\dkK]/g, '');

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