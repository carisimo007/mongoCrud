<?php
require_once "../includes/conexion.php";

header('Content-Type: application/json');

try {
    // Crear directorio de backup si no existe
    $backupDir = __DIR__ . '/../backup';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }

    // Nombre del archivo con timestamp
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = $backupDir . '/biblioteca_backup_' . $timestamp . '.json';

    // Obtener todos los libros
    $librosCursor = $librosCollection->find([], ['sort' => ['titulo' => 1]]);
    $libros = iterator_to_array($librosCursor);

    // Estructura del backup
    $backupData = [
        'database' => 'biblioteca',
        'collection' => 'libros',
        'export_date' => date('c'),
        'document_count' => count($libros),
        'documents' => []
    ];

    foreach ($libros as $libro) {
        $libroData = iterator_to_array($libro);
        
        // Convertir ObjectId a string
        $libroData['_id'] = (string)$libroData['_id'];
        
        $backupData['documents'][] = $libroData;
    }

    // Guardar backup en archivo
    $jsonContent = json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    if (file_put_contents($backupFile, $jsonContent)) {
        $fileSize = filesize($backupFile);
        
        // Formatear tamaño del archivo
        function formatBytes($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        }
        
        // Respuesta de éxito
        echo json_encode([
            'success' => true,
            'message' => 'Backup creado exitosamente',
            'file' => basename($backupFile),
            'document_count' => count($libros),
            'file_size' => formatBytes($fileSize),
            'export_date' => date('Y-m-d H:i:s')
        ]);
        
    } else {
        throw new Exception('No se pudo escribir el archivo de backup');
    }

} catch (Exception $e) {
    // Respuesta de error
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear backup: ' . $e->getMessage()
    ]);
}
?>