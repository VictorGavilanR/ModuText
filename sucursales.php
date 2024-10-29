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

 <!-- Listar Sucursales -->
<div id="sucursal-list">
    <h2 class="mb-4">Mis Sucursales</h2>
    <ul class="sucursal-list" style="list-style-type: none; margin: 0; padding: 0;">
    <?php
    include 'conexion.php';

    // Se obtiene el id del usuario actual
    $id_us = $_SESSION["id_usuario"];
    $stmt = $conexion->prepare("SELECT id_suc, nom_suc FROM sucursales WHERE rut_cli = (SELECT rut_cliente FROM clientes WHERE id_usuario = ?)");
    $stmt->bind_param("i", $id_us);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<li>
                <label>
                    ' . $row['nom_suc'] . '
                </label>
                
                <a href="editar_sucursal.php?id=' . $row['id_suc'] . '" class="btn btn-warning btn-sm">Editar</a>
                <form method="POST" action="crud_sucursales/eliminar_sucursal.php" style="display:inline;">
                    <input type="hidden" name="id_suc" value="' . $row['id_suc'] . '">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'¿Estás seguro de que deseas eliminar esta sucursal?\');">Eliminar</button>
                </form>
            </li>';
    }

    $stmt->close();
    $conexion->close();
    ?>
</ul>

    <!-- Botón de edición/borrado que se muestra al seleccionar una sucursal -->
    <div id="edit-delete-buttons" class="hidden">
        <p id="selected-sucursal"></p>
        <a href="update_sucursal.php?id_suc=' . $row['id_suc'] . '" class="btn btn-secondary btn-sm"><i class="fas fa-trash"></i> Editar</a>
        <a href="actions/delete_sucursal.php?id_suc=<?= $row['id_suc'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Borrar</a>
    </div>
</div>



            <!-- Botón para agregar una nueva sucursal -->
            <button class="btn btn-success mt-3 btn-agregar" onclick="toggleForms()">Agregar Sucursal</button>
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