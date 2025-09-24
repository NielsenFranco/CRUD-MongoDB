<?php
// Seguridad: Desactivada temporalmente para desarrollo local.
// Para producción, reactiva y ajusta IPs permitidas.
// if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
//     die("Acceso denegado. Solo para localhost.");
// }

// Configuración
$dbName = 'biblioteca';  // Nombre de tu DB
$backupDir = 'backup';   // Carpeta en el proyecto

// Crea la carpeta backup si no existe
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
    echo "<p>Carpeta 'backup' creada.</p>";
}

// Timestamp para el backup (ej: biblioteca_2024-10-15_14-30-00)
$timestamp = date('Y-m-d_H-i-s');
$backupPath = $backupDir . '/' . $dbName . '_' . $timestamp;

// Comando mongodump (ajusta si MongoDB tools están en otra ruta)
$command = sprintf('mongodump --db %s --out "%s" 2>&1', escapeshellarg($dbName), escapeshellarg($backupPath));

// Ejecuta el comando y captura output
$output = shell_exec($command);

// Verifica éxito (busca "done dumping" o ausencia de errores graves)
if (strpos($output, 'done dumping') !== false || (strpos($output, 'error') === false && !empty($output))) {
    echo "<h2 style='color: green;'>¡Backup exitoso!</h2>";
    echo "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";
    echo "<p><strong>Ruta del backup:</strong> $backupPath (incluye tus 9 libros)</p>";
    echo "<p><strong>Output del comando:</strong><pre>" . htmlspecialchars($output) . "</pre></p>";
    echo "<p><a href='index.php' class='btn btn-primary'>Volver al CRUD</a> | <a href='backup.php'>Nuevo Backup</a></p>";
} else {
    echo "<h2 style='color: red;'>Error en el backup</h2>";
    echo "<p>Posibles causas:</p>";
    echo "<ul>";
    echo "<li>MongoDB no está corriendo (ejecuta 'mongod' en terminal).</li>";
    echo "<li>'mongodump' no está en el PATH (verifica con 'mongodump --version' en CMD).</li>";
    echo "<li>shell_exec deshabilitado en php.ini (raro en XAMPP).</li>";
    echo "<li>No hay permisos para escribir en la carpeta 'backup'.</li>";
    echo "</ul>";
    echo "<p><strong>Output del comando:</strong><pre>" . htmlspecialchars($output ?? 'No output (comando falló)') . "</pre></p>";
    echo "<p><a href='index.php'>Volver al CRUD</a></p>";
}

// Muestra lista de backups existentes (opcional)
echo "<hr><h3>Backups anteriores:</h3>";
if (is_dir($backupDir)) {
    $backups = glob($backupDir . '/' . $dbName . '_*');
    if (!empty($backups)) {
        echo "<ul>";
        rsort($backups);  // Más recientes primero
        foreach ($backups as $backup) {
            $name = basename($backup);
            $size = $this->formatBytes(filesize($backup));  // Función simple para tamaño (agrega abajo)
            $date = date('Y-m-d H:i', filemtime($backup));
            echo "<li><strong>$name</strong> ($date, ~" . $size . ") - <a href='#' onclick='alert(\"Restaurar manual: mongorestore --db biblioteca $backup\");'>Restaurar</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay backups anteriores.</p>";
    }
}

// Función helper para tamaño de archivos (agrega al final del archivo)
function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB');
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) $size /= 1024;
    return round($size, $precision) . ' ' . $units[$i];
}
?>
