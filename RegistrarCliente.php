<?php
/**
 * Módulo de Registro de Clientes y Vehículos - Taller "El Estaño"
 * Aplicando Principios de Código Limpio (Clean Code) y PDO Seguro.
 */
require_once 'Conexion.php';

// Función única para validar y limpiar entradas de texto (Evita XSS e inyecciones básicas)
function limpiarEntrada(string $data): string {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$mensajeAlerta = "";
$tipoAlerta = "";

// Proceso del formulario al recibir método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Obtener instancia Singleton de la base de datos
        $db = Conexion::getInstance()->getConnection();
        
        // Iniciar transacción ACID para asegurar atomicidad entre cliente y vehículo
        $db->beginTransaction();

        // 1. Recolección y sanitización de datos del cliente
        $nombreCliente = limpiarEntrada($_POST['nombre'] ?? '');
        $telefonoCliente = limpiarEntrada($_POST['telefono'] ?? '');
        $correoCliente = limpiarEntrada($_POST['correo'] ?? '');

        if (empty($nombreCliente) || empty($telefonoCliente)) {
            throw new Exception("El nombre y el teléfono del cliente son obligatorios.");
        }

        // Inserción segura del Cliente usando sentencia preparada
        $sqlCliente = "INSERT INTO cliente (nombre, telefono, correo) VALUES (:nombre, :telefono, :correo)";
        $stmtCliente = $db->prepare($sqlCliente);
        $stmtCliente->execute([
            ':nombre' => $nombreCliente,
            ':telefono' => $telefonoCliente,
            ':correo' => $correoCliente
        ]);
        
        // Recuperar el ID generado automáticamente para la relación foránea
        $idClienteVinculado = $db->lastInsertId();

        // 2. Recolección y sanitización de datos del vehículo
        $placasVehiculo = strtoupper(limpiarEntrada($_POST['placas'] ?? ''));
        $marcatVehiculo = limpiarEntrada($_POST['marca'] ?? '');
        $modeloVehiculo = limpiarEntrada($_POST['modelo'] ?? '');
        $anioVehiculo = (int)($_POST['anio'] ?? date("Y"));

        if (!empty($placasVehiculo)) {
            $sqlVehiculo = "INSERT INTO vehiculo (id_cliente, placas, marca, modelo, anio) VALUES (:id_cliente, :placas, :marca, :modelo, :anio)";
            $stmtVehiculo = $db->prepare($sqlVehiculo);
            $stmtVehiculo->execute([
                ':id_cliente' => $idClienteVinculado,
                ':placas' => $placasVehiculo,
                ':marca' => $marcatVehiculo,
                ':modelo' => $modeloVehiculo,
                ':anio' => $anioVehiculo
            ]);
        }

        // Confirmar transacción exitosa (Commit)
        $db->commit();
        $mensajeAlerta = "¡Cliente y vehículo registrados con éxito en el sistema!";
        $tipoAlerta = "success";

    } catch (Exception $e) {
        // Revertir cambios en caso de error (Rollback)
        if (isset($db) && $db->inTransaction()) {
            $db->rollBack();
        }
        $mensajeAlerta = "Error en el registro: " . $e->getMessage();
        $tipoAlerta = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Clientes - Taller El Estaño</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Taller Mecánico "El Estaño" - Alta de Cliente y Vehículo</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($mensajeAlerta)): ?>
                            <div class="alert alert-<?php echo $tipoAlerta; ?>" role="alert">
                                <?php echo $mensajeAlerta; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="registrar_cliente.php" method="POST">
                            <h5 class="text-secondary">1. Datos del Cliente</h5>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico:</label>
                                <input type="email" class="form-control" id="correo" name="correo">
                            </div>

                            <hr>
                            <h5 class="text-secondary">2. Datos del Vehículo</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="placas" class="form-label">Placas:</label>
                                    <input type="text" class="form-control" id="placas" name="placas" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control" id="marca" name="marca" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control" id="modelo" name="modelo" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="anio" class="form-label">Año:</label>
                                    <input type="number" class="form-control" id="anio" name="anio" value="2020" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mt-3">Guardar Registro en Base de Datos</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>