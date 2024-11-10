<?php
session_start();
include "../conexion.php"; // Conexión a la base de datos

if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

$id_dir = isset($_GET["id_dir"]) ? (int) $_GET["id_dir"] : 0;

// Verificar permisos y obtener la sucursal
$id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
$id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

$stmt = $conexion->prepare("SELECT nom_dir, calle_dir, num_calle_dir, fono_dir, comuna_dir FROM direccion_retiro WHERE id_dir = ? AND (id_per = ? OR id_emp = ?)");
$stmt->bind_param("iii", $id_dir, $id_per, $id_emp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sucursal = $result->fetch_assoc();
} else {
    echo "No se encontró la sucursal.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Dirección</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../sucursales.css">
</head>
<body>

<div class="e">
    <a href="retiro.php" class="back-button btn btn-custom">Volver</a>
    
    <div class="container main-container">
        <!-- Formulario para actualizar sucursales -->
        <div class="form-container">
            <form id="ingSucursales" method="POST" action="actualizar_sucursal.php">
                <h2 class="mb-4">Actualizar Dirección</h2>
                
                <!-- Campo oculto para enviar el ID de la dirección -->
                <input type="hidden" name="id_dir" value="<?php echo htmlspecialchars($id_dir); ?>">
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Dirección</label>
                    <input type="text" name="nombre_dir" class="form-control" id="nombre" placeholder="Ingrese Dirección de retiro" value="<?php echo htmlspecialchars($sucursal['nom_dir']); ?>" required>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-8">
                        <label for="calle" class="form-label">Calle</label>
                        <input type="text" name="calle_dir" class="form-control" id="calle" placeholder="Calle" value="<?php echo htmlspecialchars($sucursal['calle_dir']); ?>" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="numcalle" class="form-label">Número</label>
                        <input type="number" name="num_calle_dir" class="form-control" id="numcalle" placeholder="Nº de calle" value="<?php echo htmlspecialchars($sucursal['num_calle_dir']); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="fono" class="form-label">Teléfono</label>
                    <input type="number" name="fono_dir" class="form-control" id="fono" placeholder="Teléfono de contacto" value="<?php echo htmlspecialchars($sucursal['fono_dir']); ?>" required> 
                </div>
                <!-- Nuevo campo para comuna -->
                <div class="mb-3">
                    <label for="comuna" class="form-label">Comuna</label>
                    <input type="text" name="comuna_dir" class="form-control" id="comuna" placeholder="Ingrese Comuna" value="<?php echo htmlspecialchars($sucursal['comuna_dir']); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Actualizar Dirección</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
