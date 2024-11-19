
        function syncManualInput(value) {
            document.getElementById('cantidadManual').value = value;
        }

        function syncSliderInput(value) {
            document.getElementById('sliderValue').value = value;
        }
            //codigo 
            document.getElementById("retiroForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevenir el envío del formulario

    // Obtener los valores de los campos del formulario
    const tipoTela = document.getElementById("tipoTela").value;
    const direccionRetiro = document.getElementById("direccionRetiro").value;
    const cantidadManual = document.getElementById("cantidadManual").value;

    // Validación de campos obligatorios
    if (!tipoTela || !direccionRetiro || cantidadManual <= 0) {
        const errorMessage = document.getElementById("errorMessage");
        errorMessage.style.display = "block"; // Mostrar el mensaje de error
        return;
    }

    // Si todos los campos son válidos, ocultar el mensaje de error
    const errorMessage = document.getElementById("errorMessage");
    errorMessage.style.display = "none";

    const submitButton = document.querySelector("button[type='submit']");
    submitButton.disabled = true; // Deshabilitar el botón para evitar múltiples envíos
    submitButton.textContent = "Enviando solicitud..."; // Cambiar el texto del botón

    const formData = new FormData(this); // Crear un objeto FormData con los datos del formulario

    fetch("procesar_retiro.php", {
        method: "POST", // Enviar la solicitud por POST
        body: formData // Incluir los datos del formulario
    })
    .then(response => response.json()) // Esperar la respuesta en formato JSON
    .then(data => {
        const successMessage = document.getElementById("successMessage");
        successMessage.style.display = "block"; // Mostrar el mensaje de éxito
        successMessage.textContent = data.message; // Establecer el mensaje de éxito

        if (data.status === "success") {
            successMessage.classList.add("alert-success"); // Establecer clase de éxito
            successMessage.classList.remove("alert-danger"); // Eliminar clase de error

            // Restablecer los campos del formulario manualmente si es necesario
            document.getElementById("retiroForm").reset(); // Restablecer los valores del formulario
            document.getElementById("sliderValue").value = 25; // Restablecer el valor del slider (por ejemplo, 25)
            document.getElementById("cantidadManual").value = 150.5; // Restablecer el valor manual del input

        } else {
            successMessage.classList.add("alert-danger"); // Establecer clase de error
            successMessage.classList.remove("alert-success"); // Eliminar clase de éxito
        }

        submitButton.disabled = false; // Volver a habilitar el botón
        submitButton.textContent = "Enviar Solicitud"; // Restaurar el texto original del botón
    })
    .catch(error => {
        console.error("Error al enviar la solicitud:", error); // Mostrar errores en consola
        submitButton.disabled = false; // Volver a habilitar el botón en caso de error
        submitButton.textContent = "Enviar Solicitud"; // Restaurar el texto original del botón
    });
});