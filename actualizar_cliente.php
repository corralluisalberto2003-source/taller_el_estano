<?php
/**
 * Módulo de Actualización de Datos de Cliente - Taller "El Estaño"
 */
require_once 'Conexion.php';

$mensaje = "";
$cliente = null;
$db = Conexion::getInstance()->getConnection();

// 1. Buscar cliente por ID al enviar formulario de selección o POST
if (isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
    $stmt->execute([':id' => $_GET['id']]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 2. Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cliente'])) {
    try {
        $sql = "UPDATE cliente SET nombre = :nombre, telefono = :telefono, correo = :correo WHERE id_cliente = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nombre'   => htmlspecialchars($_POST['nombre']),
            ':telefono' => htmlspecialchars($_POST['telefono']),
            ':correo'   => htmlspecialchars($_POST['correo']),
            ':id'       => $_POST['id_cliente']
        ]);
        $mensaje = "¡Datos del cliente actualizados correctamente!";
        
        // Recargar datos actualizados
        $stmtRef = $db->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
        $stmtRef->execute([':id' => $_POST['id_cliente']]);
        $cliente = $stmtRef->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        $mensaje = "Error al actualizar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Cliente - Taller El Estaño</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Actualizar Información de Cliente</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($mensaje)): ?>
                            <div class="alert alert-info"><?php echo $mensaje; ?></div>
                        <?php endif; ?>

                        <?php if ($cliente): ?>
                            <form action="actualizar_cliente.php?id=<?php echo $cliente['id_cliente']; ?>" method="POST">
                                <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
                                <div class="mb-3">
                                    <label class="form-label">Nombre Completo:</label>
                                    <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Teléfono:</label>
                                    <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Correo Electrónico:</label>
                                    <input type="email" class="form-control" name="correo" value="<?php echo htmlspecialchars($cliente['correo']); ?>">
                                </div>
                                <button type="submit" class="btn btn-warning w-100">Guardar Cambios</button>
                            </form>
                        <?php else: ?>
                            <p class="text-danger">Selecciona un cliente válido para editar (ej. añade `?id=1` en la URL o conéctalo desde tu listado).</p>
                            <a href="consultar_clientes.php" class="btn btn-primary">Ir al Listado de Clientes</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
