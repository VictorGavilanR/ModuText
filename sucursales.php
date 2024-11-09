<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Sucursales</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet"> <!-- Bootstrap Icons -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <button type="button" class="btn btn-primary" onclick="confirmarFormulario()">Añadir Dirección</button>

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
<script>
    // Función para confirmar la acción antes de enviar el formulario
    function confirmarFormulario() {
        // Mostrar el mensaje de confirmación
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Quieres añadir esta dirección!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, añadir',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, el formulario se envía
                Swal.fire(
                    'Añadido!',
                    'La dirección ha sido añadida correctamente.',
                    'success'
                ).then(() => {
                    // Enviar el formulario después de la confirmación
                    document.getElementById("ingSucursales").submit();
                });
            } else {
                // Si el usuario cancela, no se hace nada
                Swal.fire(
                    'Cancelado',
                    'No se ha añadido la dirección.',
                    'error'
                );
                return false; // Evita el envío del formulario
            }
        });

        // Evitar que el formulario se envíe inmediatamente
        return false;
    }
</script>

<script>
    // Función para eliminar formulario
    // Función para eliminar formulario
function eliminarFormulario(id_dir) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, ¡bórralo!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Hacer una solicitud a eliminar_sucursal.php usando Fetch API
            fetch(`crud_sucursales/eliminar_sucursal.php?id_dir=${id_dir}`)
                .then(response => response.text())
                .then(data => {
                    Swal.fire({
                        title: "¡Eliminado!",
                        text: data,
                        icon: "success"
                    }).then(() => {
                        // Redirigir o actualizar la página después de eliminar
                        window.location.href = './sucursales.php';
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Hubo un error al eliminar el archivo.",
                        icon: "error"
                    });
                });
        }
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('updated') && urlParams.get('updated') === 'success') {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "La sucursal ha sido actualizada correctamente.",
            showConfirmButton: false,
            timer: 1500
        });
    } else if (urlParams.has('updated') && urlParams.get('updated') === 'error') {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Hubo un error al actualizar la sucursal.",
            showConfirmButton: false,
            timer: 1500
        });
    }
});
</script>




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
    </style>

</body>
</html>
