<?php
session_start();

// Verificar si hay una sesión activa
if (empty($_SESSION["rut_usuario"])) {
    header("Location: login.php");
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
            <img src="img/lateral-retiro.jpg" class="img-fluid">
        </div>
        <div class="left-section">
            <div class="d-flex justify-content-between align-items-start mt-4">
                <h1 class="saludo">
                    <?php
                    // Mostrar el RUT del usuario desde la sesión
                    echo "Hola, " . $_SESSION["rut_usuario"];
                    ?>
                </h1>
                <img class="logo" src="./img/Marca - Blanco.png" alt="">
            </div>
            <div class="container mt-5">
                <form method="post" action="procesar_retiro.php">
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
                        <input type="range" id="sliderValue" class="form-range" min="1" max="50" value="25" oninput="syncManualInput(this.value)">
                        <div class="input-group">
                            <input type="number" class="form-control" id="cantidadManual" name="cantidad" min="1" max="50" value="25" oninput="syncSliderInput(this.value)">
                            <span class="input-group-text">kg</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                    <label for="direccionRetiro" class="form-label">Dirección de Retiro</label>
                    <select class="form-select" id="direccionRetiro" name="direccionRetiro" required>
                        <option selected disabled>Seleccione una dirección</option>
                            <?php
                                include "conexion.php";  // Asegúrate de incluir la conexión a la base de datos.

                                $id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
                                $id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

                                // Consultar direcciones del usuario
                                $stmt = $conexion->prepare("SELECT id_dir, nom_dir, calle_dir, num_calle_dir, comuna_dir FROM direccion_retiro WHERE id_per = ? OR id_emp = ?");
                                $stmt->bind_param("ii", $id_per, $id_emp);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Llenar las opciones del select con las direcciones
                                while ($sucursal = $result->fetch_assoc()):
                                 ?>
                                <option value="<?php echo $sucursal['id_dir']; ?>">
                                    <?php 
                                        // Concatenar comuna, nombre de dirección, calle y número
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

<!-- lo que va en value es el id de la direccion  -->
                    
                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                    <a href="sucursales.php" class="btn btn-primary">Administrar Direcciones</a>

                </form>
            </div>
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
