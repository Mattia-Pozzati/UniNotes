<?php
namespace App\Model;

use Core\ORM\BaseModel;
use Core\Helper\Logger;
use Exception;
use finfo;

/**
 * 
 * 
 */
class File extends BaseModel
{

    protected $table = "FILE";

    /**
     * Ottiene il path base per gli upload
     * @return string
     */
    private static function getBaseDir(): string
    {
        return dirname(__DIR__, 2) . "/public/uploads/";
    }

    /**
     * Crea la directory se non esiste
     * La nuova directory ha solo permessi di lettura per prevenire esecuzione di codice dallo storage
     * @throws Exception se la creazione della directory fallisce
     * @return void
     */
    private static function createUploadDirIfNotExists(int $noteId): string
    {
        $dir = rtrim(self::getBaseDir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "note_{$noteId}";
        if (!is_dir($dir) && !mkdir($dir, 0555, true)) {
            Logger::getInstance()->error("Unable to create directory: {$dir}");
            throw new Exception("Unable to create directory: {$dir}");
        }

        return $dir;
    }

    public function createForNote(int $noteId, array $upload): string
    {
        if (empty($upload['tmp_path']) || empty($upload['original_name'])) {
            Logger::getInstance()->error('Invalid upload payload: missing tmp_path or original_name');
            throw new Exception('Invalid upload payload');
        }

        // Info file
        $tmp = $upload['tmp_path'];
        $orig = $upload['original_name'];

        // Tipo MIME del file
        $mime = @mime_content_type($tmp) ?: 'application/octet-stream';

        // Dimensione del file
        $size = $upload['size'] ?? @filesize($tmp) ?? 0;

        // Estensione del file
        $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION) ?: 'bin');


        $fileDir = self::createUploadDirIfNotExists($noteId);

        $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($orig, PATHINFO_FILENAME));
        // Identificativo univoco del file
        $unique = time() . '_' . bin2hex(random_bytes(4));

        // Nome del file salvato
        $filename = "{$safeBase}.{$ext}";
        $target = $fileDir . DIRECTORY_SEPARATOR . "file_{$unique}.{$ext}";

        $moved = is_uploaded_file($tmp) ? @move_uploaded_file($tmp, $target) : @rename($tmp, $target);
        if (!$moved) {
            throw new Exception("Failed to move uploaded file to storage");
        }

        $hash = @hash_file('sha256', $target) ?: null;

        $data = [
            'note_id' => $noteId,
            'filename' => $filename,
            'filepath' => ltrim(str_replace(dirname(__DIR__, 2) . '/', '', $target), '/'),
            'mime_type' => $mime,
            'size' => $size,
            'format' => $ext,
            'hash' => $hash,
            'uploaded_at' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data);
    }


    public static function replace(int $id, array $upload): bool
    {
        $existing = File::find($id);

        if (empty($existing)) {
            Logger::getInstance()->error("File not found for replacement: {$id}");
            throw new Exception("File not found: {$id}");
        }


        $noteId = (int) $existing['note_id'];
        $tmp = $upload['tmp_path'] ?? null;
        $orig = $upload['original_name'] ?? null;

        if (!$tmp || !$orig) {
            Logger::getInstance()->error('Invalid upload payload for replace: missing tmp_path or original_name');
            throw new Exception('Invalid upload payload for replace');
        }

        // prepare new storage path
        $ext = pathinfo($orig, PATHINFO_EXTENSION) ?: ($existing['format'] ?? 'bin');

        $fileDir = self::createUploadDirIfNotExists($noteId);

        $target = $fileDir . "/file_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        $moved = is_uploaded_file($tmp) ? move_uploaded_file($tmp, $target) : rename($tmp, $target);
        if (!$moved) {
            throw new Exception("Failed to move replacement file to storage");
        }

        $hash = hash_file('sha256', $target);
        $mime = @mime_content_type($target) ?: 'application/octet-stream';
        $size = $upload['size'] ?? filesize($target);

        $update = [
            'filename' => preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($orig, PATHINFO_FILENAME)) . '.' . $ext,
            'filepath' => ltrim(str_replace(dirname(__DIR__, 2) . '/', '', $target), '/'),
            'mime_type' => $mime,
            'size' => $size,
            'format' => $ext,
            'hash' => $hash,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $status = (bool) (new self())
            ->where('id', '=', $id)
            ->update($update);

        if ($status) {
            // remove old file if exists
            $oldPath = dirname(__DIR__, 2) . '/' . ltrim($existing['filepath'], '/');
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
            return true;
        }

        // rollback file move
        @unlink($target);
        return false;
    }


    /**
     * Hard delete: remove DB record and unlink file
     */
    public static function deleteFile(int $id): bool
    {
        $existing = File::find($id);

        if (empty($existing)) {
            return false;
        }
        $path = dirname(__DIR__, 2) . '/' . ltrim($existing['filepath'], '/');

        $status = (new self())
            ->where('id', '=', $id)
            ->delete();

        if ($status && is_file($path)) {
            @unlink($path);
        }
        return $status;
    }

    /**
     * Stream file to client (simple implementation).
     * Performs basic existence check; permission checks should be done by controller.
     */
    public static function serveFile(int $id): void
    {
        $file = File::find($id);

        if (empty($file)) {
            Logger::getInstance()->error("File not found for serving: {$id}");
            http_response_code(404);
            echo 'File not found';
            exit;
        }

        $path = dirname(__DIR__, 2) . '/' . ltrim($file['filepath'], '/');
        if (!is_file($path) || !is_readable($path)) {
            http_response_code(404);
            Logger::getInstance()->error("File not accessible for serving: {$path}");
            echo 'File not available';
            exit;
        }

        // headers
        header('Content-Description: File Transfer');
        header('Content-Type: ' . ($file['mime_type'] ?? 'application/octet-stream'));
        header('Content-Length: ' . ($file['size'] ?? filesize($path)));
        header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
        header('Cache-Control: private, max-age=10800, pre-check=10800');
        header('Pragma: public');
        readfile($path);
        exit;
    }


}


?>