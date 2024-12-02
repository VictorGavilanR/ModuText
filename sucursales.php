<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION["rut_usuario"]) || !isset($_SESSION["email_usuario"])) {
    header("Location: login.php"); // Redirige al login si no está logueado
    exit();
}

// Variables de sesión
$rutUsuario = $_SESSION["rut_usuario"];
$usuarioEmail = $_SESSION["email_usuario"];
$id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
$id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Direcciones</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="sucursales.css">
</head>
<body>

<div>
    <h1 class="saludo">
        <?php
        echo "Hola, " . ($_SESSION["nombres_usuario"] ?? "Usuario"); // Muestra "Usuario" si no hay nombre
        ?>
    </h1>

        <div class="container main-container">
            <!-- Formulario para añadir sucursales -->
            <div class="form-container">
                <form id="ingSucursales" method="POST" action="controlador/controlador_sucursales.php">
                    <h2 class="mb-4">Añadir Dirección</h2>
                    <div id="mensaje" class="alert" role="alert" style="display: none;"></div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Dirección</label>
                        <input type="text" name="nombre_dir" class="form-control" id="nombre" placeholder="Almacén Central" >
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label for="calle" class="form-label">Calle</label>
                            <input type="text" name="calle_dir" class="form-control" id="calle" placeholder="Chacabuco" >
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="numcalle" class="form-label">Número</label>
                            <input type="number" name="num_calle_dir" class="form-control" id="numcalle" placeholder="#333" >
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fono" class="form-label">Teléfono</label>
                        <input type="text" name="fono_dir" class="form-control" id="fono" placeholder="Teléfono de contacto" > 
                    </div>
                    <div class="mb-3">
                        <label for="comuna" class="form-label">Comuna</label>
                        <input type="text" name="comuna_dir" class="form-control" id="comuna" placeholder="Concepción" >
                    </div>
                    <button type="button" class="btn btn-primary" onclick="confirmarFormulario()">Añadir Dirección</button>

                </form>
                <a href="retiro.php" class="back-button btn btn-custom">Volver</a>
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
                            <th>Comuna</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include "conexion.php";
                            $id_per = isset($_SESSION["id_per"]) ? $_SESSION["id_per"] : null;
                            $id_emp = isset($_SESSION["id_emp"]) ? $_SESSION["id_emp"] : null;

                            // Consultar sucursales del usuario actual
                            $stmt = $conexion->prepare("SELECT id_dir, nom_dir, calle_dir, num_calle_dir, fono_dir, comuna_dir FROM direccion_retiro WHERE id_per = ? OR id_emp = ?");
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
                            <td><?php echo htmlspecialchars($sucursal['comuna_dir']); ?></td>

                            <td>
                            <!-- Íconos de Bootstrap para Modificar y Eliminar -->
                            <a href="crud_sucursales/modificar_sucursal.php?id_dir=<?php echo $sucursal['id_dir']; ?>" class="icon-action" title="Modificar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="crud_sucursales/eliminar_sucursal.php?id_dir=<?php echo $sucursal['id_dir']; ?>" class="icon-action" title="Eliminar"  onclick="eliminarFormulario(<?php echo $sucursal['id_dir']; ?>); return false;">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php $stmt->close(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="./javascript/sucursales.js"></script>
    <style>


            /* CSS adicional para disposición en columnas */
            .main-container {
                display: flex;
                gap: 40px; /* Aumentar el espacio entre las columnas */
                margin-top: 20px;
            }
            /* Estilos de la tabla */
            .table-container h2 {
                color: #333333; /* Cambiar el color del título */
            }
            .table {
                border-collapse: separate;
                border-spacing: 0;
                width: 100%;
                background-color: #f8f9fa; /* Color de fondo de la tabla */
            }
            .table thead {
                background-color: #5F288F; /* Color de fondo de encabezado */
                color: #ffffff; /* Color del texto del encabezado */
            }
            .table th, .table td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #dddddd; /* Color del borde inferior */
            }
            .table tbody tr:hover {
                background-color: #f1f1f1; /* Efecto hover en las filas */
            }
            .table .icon-action {
                color: #5F288F; /* Color base de los iconos */
                font-size: 20px;
                margin: 0 5px;
                cursor: pointer;
            }
            .table .icon-action:hover {
                color: #88D317; /* Color de hover para los iconos */
            }

            @media (max-width: 1000px) {
                
            .main-container {
                display: flex;
                flex-direction:column;
                gap: 40px; /* Aumentar el espacio entre las columnas */
            }
        }

        </style>
    </body>
    </html>
