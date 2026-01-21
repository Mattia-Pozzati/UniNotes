<?php
namespace App\View;

use Core\Helper\Logger;
use RuntimeException;

class View {
    public static function render(string $view, string $type, array $data = []): void
    {
        match ($type) {
            "component" => self::renderComponent($view, $data),
            "page" => self::renderPage($view, $data),
            default => Logger::getInstance()->error("View not found", ["view" => $view])
        };
    }

    /**
     * Stampa direttamente un componente (utile per componenti statici)
     */
    public static function renderComponent(string $view, array $data = []): void
    {
        echo self::getComponent($view, $data);
    }

    /**
     * Ritorna il contenuto di un componente come stringa (utile per componenti dinamici)
     */
    public static function getComponent(string $view, array $data = []): string
    {
        // Path del componente
        $viewFile = dirname(__DIR__) . '/View/Components/' . $view . '.php';

        if (!file_exists($viewFile)) {
            Logger::getInstance()->error("View not found", ["view" => $viewFile]);
            throw new RuntimeException("View not found: {$viewFile}");
        }

        // Data del componente
        extract($data, EXTR_SKIP);

        // Contenuto specifico del componente
        ob_start();
        require $viewFile;
        return ob_get_clean();
    }



    public static function renderPage(string $view, array $data = []): void {
        // Path della view "interna"
        $viewFile = dirname(__DIR__) . '/View/Pages/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("View non trovata: {$viewFile}");
        }
        
        // ($title, $user, ecc.)
        extract($data, EXTR_SKIP);

        // Rende disponibile la classe View come funzione helper
        $getComponent = function($component, $data = []) {
            return \App\View\View::getComponent($component, $data);
        };

        // Contenuto specifico della pagina
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Include il layout base
        require dirname(__DIR__) . '/View/template/base.php';
    }
}
