<?php
require_once 'Conexion.php';
require_once 'Cliente.php';
require_once 'Vehiculo.php';
require_once 'Refaccion.php';

$mensaje_exito = "";
$registros_consulta = [];

try {


    $db = Conexion::getInstance()->getConnection();

    // 1. INSERCIÓN DE PRUEBA: Cliente
    $stmtCliente = $db->prepare("INSERT INTO clientes (nombre, telefono, correo) VALUES (:nombre, :telefono, :correo)");
    $stmtCliente->execute([
        ':nombre' => 'Jesus Hernandez Martinez',
        ':telefono' => '6391234567',
        ':correo' => 'Jesus.Hernandez@ejemplo.com'
    ]);
    $idClienteInsertado = $db->lastInsertId();

    // 2. INSERCIÓN DE PRUEBA: Vehículo (Asociado al cliente mediante FK)
    $stmtVehiculo = $db->prepare("INSERT INTO vehiculos (id_cliente, placas, marca, modelo, anio) VALUES (:id_cliente, :placas, :marca, :modelo, :anio)");
    $stmtVehiculo->execute([
        ':id_cliente' => $idClienteInsertado,
        ':placas' => 'ENC-' . rand(1000, 9999),
        ':marca' => 'Ford',
        ':modelo' => 'F-150',
        ':anio' => 1989
    ]);

    // 3. INSERCIÓN DE PRUEBA: Refacción
    $stmtRef = $db->prepare("INSERT INTO refacciones (codigo, descripcion, precio, stock) VALUES (:codigo, :descripcion, :precio, :stock)");
    $stmtRef->execute([
        ':codigo' => 'REF-' . rand(100, 999),
        ':descripcion' => 'Filtro de Aceite Motor 3.8L V6',
        ':precio' => 280.50,
        ':stock' => 15
    ]);

    $mensaje_exito = "¡Inserciones realizadas con éxito!";

    // 4. CONSULTA DE PRUEBA CON JOIN
    $sql = "SELECT c.nombre AS cliente, c.telefono, v.placas, v.marca, v.modelo, v.anio 
            FROM clientes c 
            INNER JOIN vehiculos v ON c.id_cliente = v.id_cliente 
            WHERE c.id_cliente = :id_cliente";
    $stmtConsulta = $db->prepare($sql);
    $stmtConsulta->execute([':id_cliente' => $idClienteInsertado]);
    $registros_consulta = $stmtConsulta->fetchAll();

} catch (PDOException $e) {
    $error_msg = "Error en la operación: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de Conexión y CRUD - Taller El Estaño</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; padding: 30px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { color: #1a365d; margin-top: 0; }
        .alert-success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e0; padding: 10px; text-align: left; }
        th { background: #ebf8ff; color: #2b6cb0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Prueba de Conexión PDO a MySQL </h2>
        
        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert-success"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert-error"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <h3>Consulta de Registros Insertados (JOIN Cliente - Vehículo):</h3>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Placas</th>
                    <th>Vehículo</th>
                    <th>Año</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros_consulta as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($row['placas']); ?></td>
                        <td><?php echo htmlspecialchars($row['marca'] . ' ' . $row['modelo']); ?></td>
                        <td><?php echo htmlspecialchars($row['anio']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>