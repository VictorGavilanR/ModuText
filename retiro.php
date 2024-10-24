<?php
session_start();

if (empty($_SESSION["id_usuario"])){
  header("location: login.php");
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="retiro.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>
  <a href="controlador/controlador_cerrar_session.php" class="logout-button btn-custom">Cerrar sesión</a>
  
  <div class="main-container">
    <div class="left-section">
      <div class="d-flex justify-content-between align-items-start mt-4">
        <h1 class="saludo">
        <?php
        // Asignar un valor a la variable de sesión si no está definida
            if (!isset($_SESSION["nombres_usuario"])) {
              $_SESSION["nombres_usuario"] = "Usuarios"; // Asigna un valor predeterminado si no está definido
            }

            // Mostrar el mensaje
            echo "Hola, " . $_SESSION["nombres_usuario"];
        ?>

        </h1>
        <img class="logo" src="./img/Marca - Blanco.png" alt="">
      </div>
      <div class="container mt-5">
        <form method="post" action="procesar_retiro.php">
          <h2 class="mb-4">Solicitud de Retiro de Telas</h2>
          <div class="mb-3">
            <label for="tipoTela" class="form-label">Tipo de Tela</label>
            <select class="form-select" id="tipoTela" required>
              <option selected disabled>Seleccione un tipo de tela</option>
              <option value="algodon">Algodón</option>
              <option value="poliester">Poliéster</option>
              <option value="seda">Seda</option>
              <option value="lana">Lana</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <!-- Sección de cantidad con slider y campo de texto -->
          <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (kg)</label>
            <input type="range" id="sliderValue" class="form-range" min="1" max="50" value="25" oninput="syncManualInput(this.value)">
            <div class="input-group">
              <input type="float" class="form-control" id="cantidadManual" min="1" max="50" value="25" oninput="syncSliderInput(this.value)">
              <span class="input-group-text">kg</span>
            </div>
          </div>
          <!-- Sucursales -->
          <div class="mb-3">
            <label for="direccionRetiro" class="form-label">Dirección de Retiro</label>
            <select class="form-select" id="direccionRetiro" required>
              <option selected disabled>Seleccione una dirección</option>
              <option value="almacen1">Almacén Central</option>
              <option value="almacen2">Sucursal Norte</option>
              <option value="almacen3">Sucursal Sur</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
        </form>
      </div>
    </div>
    <div class="right-section">
      <img src="img/lateral-retiro.jpg" class="img-fluid">
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function syncManualInput(value) {
      document.getElementById('cantidadManual').value = value; // Sincroniza el valor del slider con el input manual
    }

    function syncSliderInput(value) {
      document.getElementById('sliderValue').value = value; // Sincroniza el valor del input manual con el slider
    }
  </script>
</body>
</html>
