<?php
namespace Controller;

use Core\Helper\PdfExtractor;
use Core\Helper\Logger;
use Service\Ai\ClientLLM;
use Exception;

/**
 * Analizza file di massimo 2 MB e restituisce del testo 
 */
class AnalyzeFileController {

    public static function getResponseFromFile() : void {
        try {

            // File o prompt mancanti
            if (!isset($_FILES['file'], $_POST['prompt'])) {
                self::errorResponse('File or prompt missing', 400, 'INVALID_INPUT');
            }

            $prompt = $_POST['prompt'];

            // File troppo grande
            if ($_FILES['file']['size'] > 2_000_000) {
                self::errorResponse('File too large (max 2MB)', 413, 'FILE_TOO_LARGE');
            }


            $fileContent = self::extractFileContent($_FILES['file']);
            Logger::getInstance()->info("File estratto", []);

            // File vuoto
            if (trim($fileContent) === '') {
                self::errorResponse('File contains no readable text', 422, 'EMPTY_CONTENT');
            }

            $fileContent = self::UTF8($fileContent);
            
            $answer = ClientLLM::callLLM($_FILES['file']['name'], $fileContent, $prompt);

            self::jsonResponse([
                'success' => true,
                'file_name' => $_FILES['file']['name'],
                'answer' => $answer
            ]);
            
        } catch (Exception $e) {
            self::errorResponse('Internal server error', 500, 'UNEXPECTED_ERROR');
            Logger::getInstance()->error("Internal server error", ["ex" => $e]);
        }
    }

    public static function extractFileContent(array $file) :  string {
        
        // File
        $tmp = $file["tmp_name"];

        //Estensione file
        $est = mime_content_type($tmp);

        // Nome file
        $name = strtolower($file["name"]);

        return match (true) {
            //PDF
            $est === "application/pdf" => PDFExtractor::extract($tmp),

            //Markdown o Latex
            str_ends_with($name, ".md") || str_ends_with($name, ".tex") => file_get_contents($tmp), 

            default => self::errorResponse("Unsupported file type", 415, 'UNSUPPORTED_TYPE')
        };
    }

    private static function jsonResponse(array $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        Logger::getInstance()->info(
            "Json-Response", 
            ["data" => $data, "status" => $status]
        );
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * @return void 
     */
    private static function errorResponse(string $msg, int $status = 400, ?string $code=null) : void {

        self::jsonResponse([
            'error' => true,
            'message' => $msg,
            'code' => $code
        ], $status);
        Logger::getInstance()->error("$msg", ["code" => $code]);
    }

    /**
     * Converte il testo in UTF8 se non lo è già
     * @return string -> ( string ) testo codificato in UTF8
     */
    private static function UTF8(string $text): string {
        if (!mb_check_encoding($text, 'UTF-8')) {
            return mb_convert_encoding($text, 'UTF-8', 'auto');
        }
        Logger::getInstance()->info("text UTF8", ["text" => $text]);
        return $text;
    }
}




?>

