<?php 
namespace App\Controller;

use App\Model\Note;
use Core\Helper\Logger;
use App\Model\User;
use Core\Helper\SessionManager;
use App\Controller\NotificationController;

class NotesInteractionController
{
    public function downloadFile(int $noteId): void
    {

    }

    public static function banNote(int $noteId): void
    {
        $author = new User()
            ->select(['user.*', 'note.title AS note_title'])
            ->join('note', 'note.student_id', '=', 'user.id')
            ->where('note.id', '=', $noteId)
            ->first();
        
        if ($author) {
            // Crea notifica
            NotificationController::sendNotification(
                $noteId,
                SessionManager::get('user')['id'],
                $author['id'],
                'admin',
                "La tua nota \"" . $author['note_title'] . "\" è stata rimossa dagli amministratori per violazione dei termini di servizio. Se ritieni che si tratti di una svista, contatta il supporto.",
            );
        } else {
            Logger::getInstance()->warning("Autore della nota non trovato durante il ban della nota", ["note_id" => $noteId]);
        }
        new Note() 
        -> where('id', '=', $noteId) 
        -> update(['visibility' => 'private']);
    }



}
























?>