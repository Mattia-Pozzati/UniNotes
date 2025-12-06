<?php
namespace Core\Helper;
/**
 * Autoloader custom per poter utilizzare i namespace senza problemi.
 * Nota importante: utilizzare come namespace il percorso del file.
 */
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../../';
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});