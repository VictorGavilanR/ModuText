<?php
include '../conexion.php';

if (isset($_GET['id'])) {
    $id_suc = $_GET['id'];
    
    // Consulta para obtener los datos de la sucursal a editar
    $stmt = $conexion->prepare("SELECT nom_suc FROM sucursales WHERE id_suc = ?");
    $stmt->bind_param("i", $id_suc);
    $stmt->execute();
    $result = $stmt->get_result();
    $sucursal = $result->fetch_assoc();

    if (!$sucursal) {
        echo "Sucursal no encontrada.";
        exit;
    }
} else {
    echo "ID de sucursal no especificado.";
    exit;
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
</head>
<body>
    
    <div class="container mt-5">
    <div class="container mt-5">
    <!-- Editar Sucursal -->
    <form id="ingSucursales" class="form-container hidden" method="POST" action="controlador/controlador_sucursales.php">
        <h2 class="mb-4">Editar Sucursal</h2>

        <!-- Campo oculto para el ID de la sucursal -->
        <input type="hidden" name="id_suc" value="<?php echo $id_suc; ?>"> <!-- Aquí se agrega el ID de la sucursal -->

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nom_suc" class="form-control" id="nombre" placeholder="Nombre de la sucursal" value="<?php echo htmlspecialchars($sucursal['nom_suc']); ?>" required> <!-- Llenar el campo con el nombre actual -->
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
                <input type="number" name="cod_post" class="form-control" id="cod_post" placeholder="Código postal" required>
            </div>
            <div class="mb-3 col-md-6">
                <label for="fono" class="form-label">Teléfono</label>
                <input type="number" name="fono" class="form-control" id="fono" placeholder="Teléfono de contacto" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Sucursal</button>
    </form>
</div>
