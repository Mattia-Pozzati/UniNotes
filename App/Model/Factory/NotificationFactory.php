<?php
namespace App\Model\ViewModels;

class NotificationView {
    private int $id;
    private string $title;
    private string $author;
    private string $desc;
    private ?string $noteLink;

    public function __construct(
        int $id,
        string $title,
        string $author,
        string $desc,
        ?string $noteLink = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->desc = $desc;
        $this->noteLink = $noteLink;
    }

    public static function systemNotification(
        int $id,
        string $author,
        string $desc
    ): array {
        return new self(
            $id,
            "System",
            $author,
            $desc
        )->toArray();
    }

    public static function commentNotification(
        int $id,
        int $noteId,
        string $from,
        string $author,
        string $desc,
        string $noteTitle = "Titolo Nota"
    ): array {
        $noteLink = "/note/$noteId";
        $title = sprintf(
            "Comment %s on <a href='%s'>%s</a>",
            htmlspecialchars($from),
            htmlspecialchars($noteLink),
            htmlspecialchars($noteTitle)
        );

        return new self(
            $id,
            $title,
            $author,
            $desc,
            $noteLink
        )->toArray();
    }

    public static function likeNotification(
        int $id,
        int $noteId,
        string $from,
        string $author,
        string $desc,
        string $noteTitle = "Titolo Nota"
    ): array {
        $noteLink = "/note/$noteId";
        $title = sprintf(
            "Like %s on <a href='%s'>%s</a>",
            htmlspecialchars($from),
            htmlspecialchars($noteLink),
            htmlspecialchars($noteTitle)
        );

        return new self(
            $id,
            $title,
            $author,
            $desc,
            $noteLink
        )->toArray();
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "author" => $this->author,
            "desc" => $this->desc,
            "noteLink" => $this->noteLink,
        ];
    }
}