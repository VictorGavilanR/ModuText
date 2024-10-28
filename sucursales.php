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
  <title>Mis Sucursales</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="sucursales.css">
</head>
<body>
    
    <div class="e">
    <a href="login.php" class="back-button btn btn-custom">Volver</a>
    <h1 class="saludo">
    <?php
        // Saludo con nombre de usuario
            if (!isset($_SESSION["nombres_usuario"])) {
              $_SESSION["nombres_usuario"] = "Usuario"; // Dice "Usuario" si no se tiene un nombre
            }

            echo "Hola, " . $_SESSION["nombres_usuario"];
        ?>
    </h1>
    <div class="container mt-5">
        <!--Ingresar Sucursal-->
        <form id="ingSucursales" class="form-container hidden" method="POST" action="controlador/controlador_sucursales.php">
            <h2 class="mb-4">Ingreso de Sucursales</h2>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre de la sucursal" required>
            </div>
            <div class="row">
                <div class="mb-3 col-md-8">
                    <label for="calle" class="form-label">Calle</label>
                    <input type="text" name="calle" class="form-control" id="calle" placeholder="Nombre calle" required>
                </div>
                <div class="mb-3 col-md-4">
                    <label for="numcalle" class="form-label">Número</label>
                    <input type="number" name="num_calle" class="form-control" id="numcalle" placeholder="Nº de calle" required>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="cod_post" class="form-label">Código Postal</label>
                    <input type="number" name="cod_post" class="form-control" id="numcalle" placeholder="Código postal" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="fono" class="form-label">Teléfono</label>
                    <input type="number" name="fono" class="form-control" id="numcalle" placeholder="Teléfono de contacto" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Ingresar Sucursal</button>
            <button class="btn btn-primary" onclick="toggleForms()">Mis Sucursales</button>
        </form>

        <!--Modificar Sucursal-->
        <div id="sucursal-list" >
      <h2 class="mb-4">Mis Sucursales</h2>
      <div class="d-flex justify-content-between mb-3">
        <ul class="sucursal-list" style="list-style-type: none; margin: 0; padding: 0;">
            <li>
                <label>
                    <input type="radio" name="selected_sucursal" onclick="selectSucursal(this, 'Sucursal Centro')">
                    Sucursal Centro
                </label>
            </li>
            <li>
                <label>
                    <input type="radio" name="selected_sucursal" onclick="selectSucursal(this, 'Sucursal Norte')">
                    Sucursal Norte
                </label>
            </li>
            <!-- Agrega más sucursales aquí-->
        </ul>

        <!-- Botón de edición/borrado que se muestra al seleccionar una sucursal -->
        <button class="btn btn-success ms-3 btn-agregar" onclick="toggleForms()">Agregar Sucursal</button>
        </div>
        <div id="edit-delete-buttons" class="hidden">
            <p id="selected-sucursal"></p>
            <button class="btn btn-secondary btn-sm me-2"><i class="fas fa-edit"></i> Editar</button>
            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Borrar</button>
        </div>
        </div>
        </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script>
    function toggleForms() {
        const sucursalList = document.getElementById("sucursal-list");
        const ingSucursales = document.getElementById("ingSucursales");
        const agregarButton = document.querySelector('.btn-success'); // Selecciona el botón "Agregar Sucursal"

        // Log para verificar que se está ejecutando la función
        console.log("Toggling forms...");

        // Alternar visibilidad de la lista de sucursales y el formulario
        sucursalList.classList.toggle("hidden");
        ingSucursales.classList.toggle("hidden");

        // Ocultar el botón "Agregar Sucursal" cuando se muestra el formulario
        if (!ingSucursales.classList.contains("hidden")) {
            agregarButton.classList.add("hidden"); // Oculta el botón
        } else {
            agregarButton.classList.remove("hidden"); // Muestra el botón si el formulario está oculto
        }
    }
    function selectSucursal(radioButton, sucursalName) {
      const buttonsContainer = document.getElementById("edit-delete-buttons");
      buttonsContainer.classList.remove("hidden");

      const selectedSucursal = document.getElementById("selected-sucursal");
      selectedSucursal.textContent = "Sucursal seleccionada: " + sucursalName;
    }
  </script>
</body>
</html>