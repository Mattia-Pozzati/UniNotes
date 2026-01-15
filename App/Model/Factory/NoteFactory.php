<?php 
namespace App\Model\ViewModels;

class NoteView {
    private int $id;
    private string $title;
    private string $author;
    private string $course;
    private string $desc;
    private ?string $note_type;
    private bool $chatEnabled;
    private int $likes;
    private int $downloads;
    /** @var bool[] quali pulsanti sono visibili, in ordine */
    private array $buttonsEnabled;
    /** @var array[] configurazione pulsanti nel footer */
    private array $buttons;

    /**
     * Costruttore generale da chiamare nelle factory statiche
     */
    public function __construct(
        int $id,
        string $title,
        string $author,
        string $course,
        string $desc,
        ?string $note_type,
        bool $chatEnabled,
        int $likes,
        int $downloads,
        array $buttonsEnabled,
        array $buttons
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->course = $course;
        $this->desc = $desc;
        $this->note_type = $note_type;
        $this->chatEnabled = $chatEnabled;
        $this->likes = $likes;
        $this->downloads = $downloads;
        $this->buttonsEnabled = $buttonsEnabled;
        $this->buttons = $buttons;
    }

    public static function adminNoteView (
        int $id,
        string $title,
        string $author,
        string $course,
        string $desc,
        ?string $note_type,
        int $likes,
        int $downloads,
    ) : array {
        return new self(
            $id,
            $title,
            $author,
            $course,
            $desc,
            $note_type,
            false,
            $likes,
            $downloads,
            [false, true],
            [
                [], 
                [
                    "text" => "Blocca", 
                    "icon" => false, 
                    "class" => "btn-outline-secondary", 
                    "link" => "#", 
                    "icon-class" => ""]
            ]
        )->toArray();
    }
    public static function userDashboardNoteView (
        int $id,
        string $title,
        string $author,
        string $course,
        string $desc,
        ?string $note_type,
        int $likes,
        int $downloads,
    ) : array {
        return new self(
            $id,
            $title,
            $author,
            $course,
            $desc,
            $note_type,
            false,
            $likes,
            $downloads,
            [false, true],
            [ 
                [
                    "text" => "Modifica", 
                    "icon" => true, 
                    "class" => "btn-primary", 
                    "link" => "#", 
                    "icon-class" => "bi-arrow-right"
                ],[]

            ]
        )->toArray();
    }

   public static function searchNoteView (
        int $id,
        string $title,
        string $author,
        string $course,
        string $desc,
        ?string $note_type,
        int $likes,
        int $downloads,
    ) : array {
        return new self(
            $id,
            $title,
            $author,
            $course,
            $desc,
            $note_type,
            false,
            $likes,
            $downloads,
            [true, true],
            [
                [
                    "text" => "Vai alla nota", 
                    "icon" => true, 
                    "class" => "btn-primary", 
                    "link" => "#", 
                    "icon-class" => "bi-arrow-right"
                ],
                [
                    "text" => "Like", 
                    "icon" => true, 
                    "class" => "btn-outline-secondary", 
                    "link" => "#", 
                    "icon-class" => "bi-hand-thumbs-up"
                ]
            ]
        )->toArray();
    }

    public static function userNoteView (
        int $id,
        string $title,
        string $author,
        string $course,
        string $desc,
            ?string $note_type,
        int $likes,
        int $downloads,
    ) : array {
        return new self(
            $id,
            $title,
            $author,
            $course,
                $desc,
                $note_type,
                true,
            $likes,
            $downloads,
            [false, false],
            [[],[]]
        )->toArray();
    }

    private function toArray() {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "author" => $this->author,            
            "course" => $this->course,        
            "desc" => $this->desc,
            "note_type" => $this->note_type,
            "chatEnabled" => $this->chatEnabled,
            "likes" => $this->likes,
            "downloads" => $this->downloads,
            "buttonsEnabled" => $this->buttonsEnabled,
            "buttons" => $this->buttons,
        ];
        
    }
}
?>