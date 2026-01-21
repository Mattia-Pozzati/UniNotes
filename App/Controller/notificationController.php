<?php
namespace App\Controller;


use App\Model\Notification;
use Core\Helper\Logger;
use Exception;

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
            "recipient_id" => $toUserId,
            "sender_id" => $fromUserId,
            "type" => $type,
            "message" => $message,
            "created_at" => date("Y-m-d H:i:s")
        ]);
    }

    public static function getMyNotifications(int $userId, array $opts = []): array
    {
        $order = $opts['order'] ?? ['field' => 'created_at', 'direction' => 'DESC'];
        $perPage = $opts['per_page'] ?? 12;
        $page = $opts['page'] ?? 1;

        return (new Notification())
            ->select(['NOTIFICATION.*', 'USER.name AS sender_name', 'NOTE.title AS note_title', 'NOTE.student_id AS note_author_id'])
            ->leftJoin('USER', 'NOTIFICATION.sender_id', '=', 'USER.id')
            ->leftJoin('NOTE', 'NOTIFICATION.note_id', '=', 'NOTE.id')
            ->where('NOTIFICATION.recipient_id', '=', $userId)
            ->where("NOTIFICATION.is_read", "=", 0)
            ->order_by($order['field'], $order['direction'])
            ->paginate($perPage, $page);
    }

    public static function readNotification(int $id): void
    {
        try {
            (new Notification())->where('id', '=', $id)->update(['is_read' => 1]);
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/user/dashboard');
        } catch (Exception $e) {
            Logger::getInstance()->error("Errore nel marcare la notifica come letta", [
                "notification_id" => $id,
                "error" => $e->getMessage()
            ]);
        }
    }
}


?>