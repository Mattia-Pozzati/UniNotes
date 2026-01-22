<?php
namespace App\Service\Ai;
use Exception;

class ClientLLM {
    public static function callLLM(string $fileName, string $fileContent, string $prompt): string {
        $envPath = dirname(__DIR__, 2) . '/.env.local';
        if (!file_exists($envPath)) {
            throw new Exception('ENV file not found');
        }

        $env = parse_ini_file($envPath);
        $apiKey = $env['OPENROUTER_API_KEY'] ?? null;
        if (!$apiKey) {
            throw new Exception('API key missing');
        }

        $systemPrompt = "You are a professor. Answer ONLY using the provided file content. If not in file, say so.";
        $userMessage = "FILE: {$fileName}\nCONTENT: {$fileContent}\nQUESTION: {$prompt}";

        $payload = [
            'model' => 'xiaomi/mimo-v2-flash:free',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage]
            ],
            'temperature' => 0.2
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Authorization: Bearer {$apiKey}\r\nContent-Type: application/json\r\n",
                'content' => json_encode($payload),
                'timeout' => 60
            ],
            'ssl' => ['verify_peer' => false]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents('https://openrouter.ai/api/v1/chat/completions', false, $context);

        if ($response === false) {
            throw new Exception('API request failed');
        }

        $data = json_decode($response, true);
        if (!isset($data['choices'][0]['message']['content'])) {
            throw new Exception('Invalid API response');
        }

        return $data['choices'][0]['message']['content'];
    }
}