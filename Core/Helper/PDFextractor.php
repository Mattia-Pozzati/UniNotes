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
        if (file_exists($bin)) {
            $cmd = "\"$bin\" " . escapeshellarg($path) . ' -';
        } else {
            $cmd = 'pdftotext ' . escapeshellarg($path) . ' -';
        }
        [$out, $err, $exit] = self::runCommand($cmd);
        return $out ?? '';
    }

    private static function unix(string $path): string
    {
        $cmd = '/opt/homebrew/bin/pdftotext ' . escapeshellarg($path) . ' -';
        [$out, $err, $exit] = self::runCommand($cmd);
        return $out ?? '';
    }

    private static function runCommand(string $cmd): array
    {
        $descriptors = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $process = @proc_open($cmd, $descriptors, $pipes);
        if (!is_resource($process)) {
            return ['', 'proc_open failed', 1];
        }
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $exit = proc_close($process);
        return [trim($stdout), trim($stderr), $exit];
    }
}



















?>
