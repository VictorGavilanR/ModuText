<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Sucursales</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="sucursales.css">

</head>
<body>

<div class="e">
    <a href="retiro.php" class="back-button btn btn-custom">Volver</a>
    <h1 class="saludo">
        <?php
            session_start();
            if (!isset($_SESSION["nombres_usuario"])) {
                $_SESSION["nombres_usuario"] = "Usuario"; // Muestra "Usuario" si no se tiene un nombre
            }
            echo "Hola, " . $_SESSION["nombres_usuario"];
        ?>
    </h1>

    <div class="container main-container">
        <!-- Formulario para añadir sucursales -->
        <div class="form-container">
            <form id="ingSucursales" method="POST" action="controlador/controlador_sucursales.php">
                <h2 class="mb-4">Añadir Dirección</h2>
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
                <div class="mb-3">
                    <label for="fono" class="form-label">Teléfono</label>
                    <input type="number" name="fono_dir" class="form-control" id="fono" placeholder="Teléfono de contacto" required> 
                </div>
                <button type="submit" class="btn btn-primary">Añadir Dirección</button>
            </form>
        </div>

        <!-- Tabla para listar sucursales -->
        <div class="table-container">
            <h2 class="mb-4">Direcciones Registradas</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Dirección</th>
                        <th>Calle</th>
                        <th>Número</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include "conexion.php";
                        $id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
                        $id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

                        // Consultar sucursales del usuario actual
                        $stmt = $conexion->prepare("SELECT id_dir, nom_dir, calle_dir, num_calle_dir, fono_dir FROM direccion_retiro WHERE id_per = ? OR id_emp = ?");
                        $stmt->bind_param("ii", $id_per, $id_emp);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($sucursal = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sucursal['nom_dir']); ?></td>
                        <td><?php echo htmlspecialchars($sucursal['calle_dir']); ?></td>
                        <td><?php echo htmlspecialchars($sucursal['num_calle_dir']); ?></td>
                        <td><?php echo htmlspecialchars($sucursal['fono_dir']); ?></td>
                        <td>
                            <a href="crud_sucursales/modificar_sucursal.php?id_dir=<?php echo $sucursal['id_dir']; ?>" class="btn btn-warning btn-sm">Modificar</a>
                            <a href="crud_sucursales/eliminar_sucursal.php?id_dir=<?php echo $sucursal['id_dir']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta sucursal?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php $stmt->close(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
        /* CSS adicional para disposición en columnas */
        .main-container {
            display: flex;
            gap: 40px; /* Aumentar el espacio entre las columnas */
            margin-top: 20px;
        }
        .form-container, .table-container {
            flex: 1;
        }
        .table-container {
            overflow-x: auto;
        }
    </style>

</body>
</html>
