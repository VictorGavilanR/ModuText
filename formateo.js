document.addEventListener('DOMContentLoaded', function () {
    const rutInput = document.getElementById('rut');
    const rutError = document.getElementById('rutError');

    // Formatear el RUT al escribir
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

    // Función para validar RUT chileno
    function validarRUT(rut) {
        // Quitar puntos y guiones para la validación
        rut = rut.replace(/\./g, '').replace(/-/g, '');

        if (rut.length < 8 || rut.length > 9) {
            return false;
        }

        let cuerpo = rut.slice(0, -1);
        let dv = rut.slice(-1).toUpperCase(); // Convertir a mayúscula el DV

        let suma = 0;
        let multiplo = 2;

        // Sumar los dígitos del cuerpo del RUT
        for (let i = cuerpo.length - 1; i >= 0; i--) {
            suma += multiplo * parseInt(cuerpo.charAt(i), 10);
            multiplo = multiplo === 7 ? 2 : multiplo + 1;
        }

        let dvEsperado = 11 - (suma % 11);
        if (dvEsperado === 11) dvEsperado = '0';
        else if (dvEsperado === 10) dvEsperado = 'k'; // Asegurar que K sea considerado válido
        else dvEsperado = dvEsperado.toString();

        return dv === dvEsperado;
    }
});