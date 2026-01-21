<?php 
namespace App\Model\Factory;

class NoteFactory {
    private int $id;
    private string $title;
    private string $author;
    private string $course;
    private string $desc;
    private ?string $note_type;
    private ?string $format;
    private ?string $university;
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
        ?string $format,
        ?string $university,
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
        $this->format = $format;
        $this->university = $university;
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
        ?string $format,
        ?string $university,
        int $likes,
        int $downloads,
    ) : array {
        return (new self(
            $id,
            $title,
            $author,
            $course,
            $desc,
            $note_type,
            $format,
            $university,
            false,
            $likes,
            $downloads,
            [true, true],
            [
                [
                    "text" => "Vai alla nota", 
                    "icon" => true, 
                    "class" => "btn-primary", 
                    "link" => "/note/{$id}", 
                    "icon-class" => "bi-arrow-right"
                ],
                [
                    "text" => "Blocca", 
                    "icon" => false, 
                    "class" => "btn-outline-secondary", 
                    "link" => "#", 
                    "icon-class" => ""
                ]
            ]
        ))->toArray();
    }
    
    public static function userDashboardNoteView (
        int $id,
        string $title,
        string $author,
        string $course,
        string $desc,
        ?string $note_type,
        ?string $format,
        ?string $university,
        int $likes,
        int $downloads,
    ) : array {
        return (new self(
            $id,
            $title,
            $author,
            $course,
            $desc,
            $note_type,
            $format,
            $university,
            false,
            $likes,
            $downloads,
            [true, true],
            [ 
                [
                    "text" => "Vai alla nota", 
                    "icon" => true, 
                    "class" => "btn-primary", 
                    "link" => "/note/{$id}", 
                    "icon-class" => "bi-arrow-right"
                ],
                [
                    "text" => "Modifica", 
                    "icon" => true, 
                    "class" => "btn-outline-secondary", 
                    "link" => "/note/{$id}/edit", 
                    "icon-class" => "bi-pencil"
                ]
            ]
        ))->toArray();
    }

   public static function searchNoteView (
        int $id,
        string $title,
        string $author,
        string $course,
        string $desc,
        ?string $note_type,
        ?string $format,
        ?string $university,
        int $likes,
        int $downloads,
    ) : array {
        return (new self(
            $id,
            $title,
            $author,
            $course,
            $desc,
            $note_type,
            $format,
            $university,
            false,
            $likes,
            $downloads,
            [true, true],
            [
                [
                    "text" => "Vai alla nota", 
                    "icon" => true, 
                    "class" => "btn-primary", 
                    "link" => "/note/{$id}", 
                    "icon-class" => "bi-arrow-right"
                ],
                [
                    "text" => "Like", 
                    "icon" => true, 
                    "class" => "btn-outline-secondary", 
                    "link" => "/note/{$id}/toggleLike", 
                    "icon-class" => "bi-hand-thumbs-up"
                ]
            ]
        ))->toArray();
    }

    public static function userNoteView (
        int $id,
        string $title,
        string $author,
        string $course,
        string $desc,
            ?string $note_type,
        ?string $format,
        ?string $university,
        int $likes,
        int $downloads,
    ) : array {
        return (new self(
            $id,
            $title,
            $author,
            $course,
            $desc,
            $note_type,
            $format,
            $university,
            true,
            $likes,
            $downloads,
            [false, false],
            [[],[]]
        ))->toArray();
    }

    private function toArray() {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "author" => $this->author,            
            "course" => $this->course,        
            "desc" => $this->desc,
            "note_type" => $this->note_type,
            "format"=> $this->format,
            "university"=> $this->university,
            "chatEnabled" => $this->chatEnabled,
            "likes" => $this->likes,
            "downloads" => $this->downloads,
            "buttonsEnabled" => $this->buttonsEnabled,
            "buttons" => $this->buttons,
        ];
        
    }
}