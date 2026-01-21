<?php
namespace App\Controller;


use App\Model\Notification;
use Core\Helper\Logger;

class NotificationController
{
    public static function sendNotification(int $noteId, int $toUserId, int $fromUserId, string $type, string $message): void
    {
        Logger::getInstance()->info("Notifica inviata", [
            "user_id" => $toUserId,
            "type" => $type,
            "message" => $message
        ]);

        (new Notification())->insert([
            "note_id" => $noteId,
            "receiver_id" => $toUserId,
            "sender_id" => $fromUserId,
            "type" => $type,
            "message" => $message,
            "created_at" => date("Y-m-d H:i:s")
        ]);
    }
}


?>