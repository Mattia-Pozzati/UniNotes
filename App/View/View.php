<?php
namespace App\View;

class View {
    public static function render(string $view, array $data = []): void
    {
        // Path della view "interna"
        $viewFile = __DIR__.'/pages/'.$view.'.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View non trovata: {$viewFile}");
        }

        // ($title, $user, ecc.)
        extract($data, EXTR_SKIP);

        // Contenuto specifico della pagina
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Include il layout base che userà $content e, se vuoi, $title
        require __DIR__ . '/template/base.php';
    }
}




?>