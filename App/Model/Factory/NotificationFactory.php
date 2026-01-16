<?php
namespace App\Model\Factory;

class NotificationView {
    private int $id;
    private int $sender_id;
    private int $recipient_id;
    private string $title;
    private string $author;
    private string $desc;
    private ?string $noteLink;

    public function __construct(
        int $id,
        int $sender_id,
        int $recipient_id,
        string $title,
        string $author,
        string $desc,
        ?string $noteLink = null
    ) {
        $this->id = $id;
        $this->sender_id = $sender_id;
        $this->recipient_id = $recipient_id;
        $this->title = $title;
        $this->author = $author;
        $this->desc = $desc;
        $this->noteLink = $noteLink;
    }

    public static function systemNotification(
        int $id,
        int $sender_id,
        int $recipient_id,
        string $author,
        string $desc
    ): array {
        return new self(
            $id,
            $sender_id,
            $recipient_id,
            "System",
            $author,
            $desc
        )->toArray();
    }

    public static function commentNotification(
        int $id,
        int $sender_id,
        int $recipient_id,
        int $noteId,
        string $from,
        string $author,
        string $desc,
        string $noteTitle = "Titolo Nota"
    ): array {
        $noteLink = "/note/$noteId";
        $title = sprintf(
            "Comment %s on %s",
            htmlspecialchars($from),
            htmlspecialchars($noteTitle)
        );

        return new self(
            $id,
            $sender_id,
            $recipient_id,
            $title,
            $author,
            $desc,
            $noteLink
        )->toArray();
    }

    public static function likeNotification(
        int $id,
        int $sender_id,
        int $recipient_id,
        int $noteId,
        string $from,
        string $author,
        string $desc,
        string $noteTitle = "Titolo Nota"
    ): array {
        $noteLink = "/note/$noteId";
        $title = sprintf(
            "Like %s on %s",
            htmlspecialchars($from),
            htmlspecialchars($noteTitle)
        );

        return new self(
            $id,
            $sender_id,
            $recipient_id,
            $title,
            $author,
            $desc,
            $noteLink
        )->toArray();
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "sender_id" => $this->sender_id,
            "recipient_id" => $this->recipient_id,
            "title" => $this->title,
            "author" => $this->author,
            "desc" => $this->desc,
            "noteLink" => $this->noteLink,
        ];
    }
}