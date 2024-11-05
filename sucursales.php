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
        session_start();
        if (!isset($_SESSION["nombres_usuario"])) {
            $_SESSION["nombres_usuario"] = "Usuario"; // Dice "Usuario" si no se tiene un nombre
        }

        echo "Hola, " . $_SESSION["nombres_usuario"];
    ?>
    </h1>
    <div class="container mt-5">
        <!--Ingresar Sucursal-->
        <form id="ingSucursales" class="form-container" method="POST" action="controlador/controlador_sucursales.php">
            <h2 class="mb-4">Ingreso de Sucursales</h2>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Dirección</label>
                <input type="text" name="nombre_dir" class="form-control" id="nombre" placeholder="Ingrese Dirección de retiro" required>
            </div>
            <div class="row">
                <div class="mb-3 col-md-8">
                    <label for="calle" class="form-label">Calle</label>
                    <input type="text" name="calle_dir" class="form-control" id="calle" placeholder="Calle" required>
                </div>
                <div class="mb-3 col-md-4">
                    <label for="numcalle" class="form-label">Número</label>
                    <input type="number" name="num_calle_dir" class="form-control" id="numcalle" placeholder="Nº de calle" required>
                </div>
            </div>
                <div class="mb-3 col-md-6">
                    <label for="fono" class="form-label">Teléfono</label>
                    <input type="number" name="fono_dir" class="form-control" id="fono" placeholder="Teléfono de contacto" required> 
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Ingresar Sucursal</button>
        </form>
    </div>
</div>
</body>
</html>
