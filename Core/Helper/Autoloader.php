<?php
namespace Core\Helper;
/**
 * Autoloader custom per poter utilizzare i namespace senza problemi.
 * Nota importante: utilizzare come namespace il percorso del file.
 * 
 * 
 * 
 * Viene chiamata ogni volta che non viene trovata una classe. La importa automaticamente (PHP forse non è così male)
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