<?php
/**
 * Módulo de Consulta de Clientes - Taller "El Estaño"
 */
require_once 'Conexion.php';

try {
    $db = Conexion::getInstance()->getConnection();
    
    // Consulta utilizando JOIN para obtener clientes y sus vehículos asociados
    $sql = "SELECT c.id_cliente, c.nombre, c.telefono, c.correo, v.placas, v.marca, v.modelo, v.anio 
            FROM cliente c 
            LEFT JOIN vehiculo v ON c.id_cliente = v.id_cliente";
    $stmt = $db->query($sql);
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = "Error al consultar los datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Clientes - Taller El Estaño</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary mb-4">Listado General de Clientes y Vehículos</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <table class="table table-striped table-bordered shadow bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Cliente</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Placas</th>
                    <th>Vehículo (Marca/Modelo/Año)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($registros)): ?>
                    <?php foreach ($registros as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['correo']); ?></td>
                            <td><?php echo htmlspecialchars($row['placas'] ?? 'Sin vehículo'); ?></td>
                            <td><?php echo htmlspecialchars(($row['marca'] ?? '') . ' ' . ($row['modelo'] ?? '') . ' (' . ($row['anio'] ?? '') . ')'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No hay registros en el sistema.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="registrar_cliente.php" class="btn btn-secondary mt-3">Registrar Nuevo Cliente</a>
    </div>
</body>
</html>
