<?php
// index.php - Verificación del Entorno de Desarrollo Taller El Estaño
$host = '127.0.0.1';
$db   = 'taller_el_estano';
$user = 'root';
$pass = ''; // Por defecto en XAMPP la contraseña de root está vacía
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $status_db = "<span style='color: green; font-weight: bold;'>CONEXIÓN A MYSQL EXITOSA (PDO)</span>";
} catch (\PDOException $e) {
    $status_db = "<span style='color: red; font-weight: bold;'>ERROR DE CONEXIÓN: " . $e->getMessage() . "</span>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entorno de Desarrollo - Taller El Estaño</title>
    <style>
        body { font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; padding: 40px; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h2 { color: #1a365d; margin-top: 0; }
        .item { margin-bottom: 12px; font-size: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Entorno de Desarrollo Configurado</h2>
        <div class="item"><strong>Proyecto:</strong> Sistema de Gestión Taller "El Estaño"</div>
        <div class="item"><strong>Versión de PHP:</strong> <?php echo phpversion(); ?></div>
        <div class="item"><strong>Servidor Web:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></div>
        <div class="item"><strong>Estado Base de Datos:</strong> <?php echo $status_db; ?></div>
        <div class="item"><strong>Estado del Entorno:</strong> <span style="color: green; font-weight: bold;">LISTO PARA DESARROLLO</span></div>
    </div>
</body>
</html>