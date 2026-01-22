<?php
namespace App\Controller;

use App\Model\File;
use Core\Helper\Logger;
use Core\Helper\SessionManager;

class FileController
{
    /**
     * Download di un file
     */
    public function download($id): void{
        $fileId = (int)$id;
        
        Logger::getInstance()->info("Richiesta download file", ["file_id" => $fileId]);
        
        try {
            // Trova il file nel database
            $file = (new File())->where('id', '=', $fileId)->first();
            
            if (!$file) {
                Logger::getInstance()->warning("File non trovato", ["file_id" => $fileId]);
                http_response_code(404);
                echo "File non trovato";
                exit;
            }
            
            // Costruisci il path completo
            $filePath = dirname(__DIR__, 2) . '/' . ltrim($file['filepath'], '/');
            
            Logger::getInstance()->info("Path file", [
                "file_id" => $fileId,
                "path" => $filePath,
                "exists" => file_exists($filePath)
            ]);
            
            if (!file_exists($filePath)) {
                Logger::getInstance()->error("File fisico non trovato", [
                    "file_id" => $fileId,
                    "path" => $filePath
                ]);
                http_response_code(404);
                echo "File non disponibile";
                exit;
            }
            
            // Registra il download (opzionale - per statistiche)
            if (SessionManager::isLoggedIn()) {
                $userId = SessionManager::userId();
                $noteId = $file['note_id'];
                
                // Inserisci in NOTE_DOWNLOAD se non esiste già
                $db = \Core\Database\Database::getInstance();
                $stmt = $db->prepare("
                    INSERT IGNORE INTO NOTE_DOWNLOAD (student_id, note_id, downloaded_at)
                    VALUES (?, ?, NOW())
                ");
                $stmt->execute([$userId, $noteId]);
                
                Logger::getInstance()->info("Download registrato", [
                    "user_id" => $userId,
                    "note_id" => $noteId,
                    "file_id" => $fileId
                ]);
            }
            
            // Imposta gli header per il download
            header('Content-Description: File Transfer');
            header('Content-Type: ' . ($file['mime_type'] ?? 'application/octet-stream'));
            header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
            header('Content-Length: ' . ($file['size'] ?? filesize($filePath)));
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            
            // Pulisci il buffer di output
            if (ob_get_level()) {
                ob_clean();
            }
            
            // Leggi e invia il file
            readfile($filePath);
            
            Logger::getInstance()->info("File scaricato con successo", [
                "file_id" => $fileId,
                "filename" => $file['filename']
            ]);
            
            exit;
            
        } catch (\Exception $e) {
            Logger::getInstance()->error("Errore download file", [
                "file_id" => $fileId,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
            
            http_response_code(500);
            echo "Errore durante il download";
            exit;
        }
    }
}