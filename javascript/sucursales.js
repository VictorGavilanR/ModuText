function confirmarFormulario() {
    const form = document.getElementById("ingSucursales");
    const inputs = form.querySelectorAll("input"); // Selecciona todos los campos de entrada
    let errores = []; // Almacena los errores
    let camposVacios = false; // Variable para verificar si hay campos vacíos
    let telefonoValido = true; // Variable para verificar si el teléfono es válido

    // Recorremos todos los inputs y validamos
    inputs.forEach(input => {
        // Verifica si el campo está vacío
        if (input.value.trim() === "") {
            input.classList.add("is-invalid"); // Marca el campo como inválido
            camposVacios = true; // Si el campo está vacío, marcamos la bandera de camposVacios como true
        } else {
            input.classList.remove("is-invalid"); // Si es válido, quita la marca
        }
    });

    // Validación de teléfono (debe tener "+56" seguido de 9 dígitos)
    const telefonoInput = document.getElementById("fono");
    const telefonoValue = telefonoInput.value.trim().replace(/\s+/g, ''); // Elimina espacios

    // Si el teléfono no tiene el formato correcto, marcamos como inválido
    if (telefonoValue && !/^\+56\d{9}$/.test(telefonoValue)) {
        telefonoValido = false;
        telefonoInput.classList.add("is-invalid"); // Marca el campo como inválido
    } else {
        telefonoInput.classList.remove("is-invalid"); // Si es válido, quita la clase
    }

    // Si hay campos vacíos o teléfono inválido, mostramos un solo mensaje de error
    if (camposVacios) {
        let mensajeError = "Por favor, completa todos los campos.";

        Swal.fire({
            title: 'Error',
            text: mensajeError,
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    }else if(!telefonoValido){
        let mensajeError = "Teléfono inválido.";

        Swal.fire({
            title: 'Error',
            text: mensajeError,
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    } else {
        // Si no hay errores, proceder a la confirmación
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Quieres añadir esta dirección!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, añadir',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Añadido!',
                    'La dirección ha sido añadida correctamente.',
                    'success'
                ).then(() => {
                    form.submit(); // Envía el formulario
                });
            } else {
                Swal.fire(
                    'Cancelado',
                    'No se ha añadido la dirección.',
                    'error'
                );
            }
        });
    }
}


// Formateo teléfono
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById("fono");

    if (!phoneInput) {
        console.error("El campo con ID 'fono' no se encuentra.");
        return;
    }

    function formatPhoneNumber(event) {
        let value = phoneInput.value.replace(/\D/g, ''); // Elimina caracteres no numéricos
    
        // Asegurar el prefijo +56
        if (!value.startsWith("56")) {
            value = "56" + value;
        }
    
        const maxDigits = 9; // Máximo de 9 dígitos después del prefijo
        const phoneNumber = value.slice(2, 2 + maxDigits);
        phoneInput.value = `+56 ${phoneNumber}`;
    
        // Validar la longitud del teléfono (debe ser 9 dígitos después del prefijo)
        if (phoneNumber.length !== 9) {
            phoneInput.setCustomValidity("Teléfono inválido.");
            phoneInput.classList.add("is-invalid"); // Marca el campo como inválido
        } else {
            phoneInput.setCustomValidity(""); // Resetea la validez
            phoneInput.classList.remove("is-invalid"); // Quita la clase de invalidez
        }
    }

    // Inicializa el valor si está vacío o no tiene el formato correcto
    if (phoneInput.value === "" || !phoneInput.value.startsWith("+56")) {
        phoneInput.value = "+56 ";
        console.log("Campo inicializado:", phoneInput.value);
    }

    // Añade eventos para el formateo y bloqueo de edición del prefijo
    phoneInput.addEventListener('input', formatPhoneNumber);

    phoneInput.addEventListener('keydown', function (event) {
        const caretPosition = phoneInput.selectionStart;
        if (caretPosition <= 4 && (event.key === "Backspace" || event.key === "Delete")) {
            console.warn("Intento de borrar prefijo bloqueado");
            event.preventDefault(); // Evita eliminar el prefijo
        }
    });

    // Función para validar el teléfono y enviar el formulario si es válido
    window.validarYEnviarFormulario = function () {
        const form = document.getElementById('ingSucursales');
        // Validar el teléfono antes de enviar
        formatPhoneNumber(); // Realiza el formateo y validación del teléfono
    
        if (phoneInput.classList.contains("is-invalid")) {
            Swal.fire({
                title: 'Error',
                text: 'El número de teléfono es inválido. Por favor, ingresa un número válido.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return; // No envía el formulario si el teléfono es inválido
        }
    
        // Si el teléfono es válido, envía el formulario
        form.submit();
    };
});


// Función para eliminar formulario
function eliminarFormulario(id_dir) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, ¡bórralo!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Hacer una solicitud a eliminar_sucursal.php usando Fetch API
            fetch(`crud_sucursales/eliminar_sucursal.php?id_dir=${id_dir}`)
                .then(response => response.text())
                .then(data => {
                    Swal.fire({
                        title: "¡Eliminado!",
                        text: data,
                        icon: "success"
                    }).then(() => {
                        // Redirigir o actualizar la página después de eliminar
                        window.location.href = './sucursales.php';
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Hubo un error al eliminar la dirección.",
                        icon: "error"
                    });
                });
        }
    });
}

//Actualizar sucursales
document.addEventListener('DOMContentLoaded', (event) => {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('updated')) {
        const updateStatus = urlParams.get('updated'); // Guarda el valor de 'updated'
        if (updateStatus === 'success') {
            Swal.fire({
                icon: "success",
                title: "La sucursal ha sido actualizada correctamente.",
                showConfirmButton: false,
                timer: 1500
            });
        } else if (updateStatus === 'error') {
            Swal.fire({
                icon: "error",
                title: "Hubo un error al actualizar la sucursal.",
                showConfirmButton: false,
                timer: 1500
            });
        } else if (updateStatus === 'no-changes') {
            Swal.fire({
                icon: "info",
                title: "No se realizaron cambios.",
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
});
