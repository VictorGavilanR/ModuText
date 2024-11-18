<?php
session_start();



// Verificar si el usuario está logueado
if (isset($_SESSION["rut_usuario"]) && isset($_SESSION["email_usuario"])) {
    $rutUsuario = $_SESSION["rut_usuario"];
    $usuarioEmail = $_SESSION["email_usuario"];
} else {
    echo "Error: No se han encontrado los datos del usuario en la sesión.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Retiro de Telas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="retiro.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>
    <a href="controlador/controlador_cerrar_session.php" class="logout-button btn-custom">Cerrar sesión</a>

    <div class="main-container">
        <div class="right-section">
            <img src="img/retiro.png">
        </div>
        <div class="left-section">
            <div class="d-flex justify-content-between align-items-start mt-4">
                <img class="logo" src="./img/Marca - Blanco.png" alt="">
            </div>
            <div class="container">
                <form id="retiroForm">
                    <h2 class="saludo">
                        <?php echo "Hola, " . $_SESSION["rut_usuario"]; ?>
                    </h2>
                    <h2 class="mb-4">Solicitud de Retiro de Telas</h2>
                    <div class="mb-3">
                        <label for="tipoTela" class="form-label">Tipo de Tela</label>
                        <select class="form-select" id="tipoTela" name="tipoTela" required>
                            <option selected disabled>Seleccione un tipo de tela</option>
                            <option value="algodon">Algodón</option>
                            <option value="poliester">Poliéster</option>
                            <option value="seda">Seda</option>
                            <option value="lana">Lana</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad (kg)</label>
                        <input type="range" id="sliderValue" class="form-range" min="1" max="300" value="25" oninput="syncManualInput(this.value)">
                        <div class="input-group">
                            <input type="number" class="form-control" id="cantidadManual" name="cantidad" min="1" max="300" value="150.5" step="0.01" oninput="syncSliderInput(this.value)">
                            <span class="input-group-text">kg</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="direccionRetiro" class="form-label">Dirección de Retiro</label>
                        <select class="form-select" id="direccionRetiro" name="direccionRetiro" required>
                            <option selected disabled>Seleccione una dirección</option>
                            <?php
                                include "conexion.php";

                                $id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
                                $id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

                                $stmt = $conexion->prepare("SELECT id_dir, nom_dir, calle_dir, num_calle_dir, comuna_dir FROM direccion_retiro WHERE id_per = ? OR id_emp = ?");
                                $stmt->bind_param("ii", $id_per, $id_emp);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($sucursal = $result->fetch_assoc()):
                            ?>
                                <option value="<?php echo $sucursal['id_dir']; ?>">
                                    <?php
                                        echo htmlspecialchars($sucursal['comuna_dir']) . " - " .
                                            htmlspecialchars($sucursal['nom_dir']) . " - " .
                                            htmlspecialchars($sucursal['calle_dir']) . " " . 
                                            htmlspecialchars($sucursal['num_calle_dir']);
                                    ?>
                                </option>
                            <?php endwhile; ?>
                            <?php $stmt->close(); ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                    <a href="sucursales.php" class="btn btn-primary">Administrar Direcciones</a>
                </form>
                <div id="successMessage" class="alert alert-success mt-4" style="display: none;">
                    Solicitud de retiro enviada con éxito.
                </div>
                <div id="errorMessage" class="alert alert-danger mt-4" style="display: none;">
    Por favor, completa todos los campos obligatorios antes de enviar la solicitud.
</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
</body>
</html>