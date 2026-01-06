<?php
namespace Core\Helper;

/**
 * Estrattore pdf universale. (Funziona sia su sistema windows sia in unix)
 * Utilizza il comando <pdftotext>
 * Possibilità di gesrire i comandi da non eseguire sul server. Per ora non esploro
 */
class PdfExtractor
{
    /**
     * Estrae testo da pdf
     * @return string
     */
    public static function extract(string $path): string
    {
        if (PHP_OS_FAMILY == "Windows") {
            return self::windows($path);
        }
        return self::unix($path);
    }

    private static function windows(string $path): string
    {
        // Default path a pdftotext.exe
        $bin = 'C:\\poppler\\Library\\bin\\pdftotext.exe';
        return shell_exec("\"$bin\" " . escapeshellarg($path) . " -") ?? '';
    }

    private static function unix(string $path): string
    {
        return shell_exec("pdftotext " . escapeshellarg($path) . " -") ?? '';
    }
}



















?>
