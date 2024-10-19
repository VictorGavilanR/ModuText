<?php
session_start();
include "conexion.php"; // Conexión a la base de datos

// Verificar si el usuario es administrador
if ($_SESSION["tipo_usuario"] != "admin") {
    header("Location: login.php");
    exit;
}

// Consulta para obtener todas las solicitudes de donaciones
$sql = "SELECT d.id, u.nombre, d.tipo_tela, d.kilos, d.sucursal, d.estado 
        FROM donaciones d 
        JOIN usuarios u ON d.usuario_id = u.id 
        WHERE d.estado = 'Pendiente'";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Solicitudes de Retiro Pendientes</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Solicitud</th>
                    <th>Nombre Usuario</th>
                    <th>Tipo de Tela</th>
                    <th>Kilos</th>
                    <th>Sucursal</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['tipo_tela']; ?></td>
                    <td><?php echo $row['kilos']; ?></td>
                    <td><?php echo $row['sucursal']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td>
                        <form method="post" action="procesar_estado.php">
                            <input type="hidden" name="id_solicitud" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-success">Marcar como Retirada</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>