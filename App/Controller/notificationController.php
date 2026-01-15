<?php
namespace App\Controller;


use App\Model\Notification;
use Core\Helper\Logger;

class notesInteractionController
{
    public function sendNotification(int $toUserId, int $fromUserId, string $type, string $message): void
    {
        // Per ora, solo un log di esempio
        Logger::getInstance()->info("Notifica inviata", [
            "user_id" => $toUserId,
            "type" => $type,
            "message" => $message
        ]);
    }
}


?>