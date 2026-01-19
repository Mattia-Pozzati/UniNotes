<?php
namespace App\Service;

use App\Model\Note;
use App\Model\User;
use App\Model\File;
use App\Model\Comment;
use Core\Database\Database;
use Core\Helper\Logger;

class NoteService {
  private static ?bool $dbAvailable = null;

  public static function getFullNote(int $id): ?array {
    return self::getFullNoteFromDatabase($id);
  }

  public static function getNotesForHome(int $limit = 10): array {
    return self::getNotesFromDatabase($limit);
  }

  private static function getFullNoteFromDatabase(int $id): ?array {
    try {
        $note = Note::find($id);
        
        // Verifica che la nota esista e non sia cancellata
        if (!$note || ($note['deleted_at'] ?? null) !== null) {
            Logger::getInstance()->info("Nota non trovata o cancellata", ["note_id" => $id]);
            return null;
        }
        
        // Carica autore
        $author = User::find($note['student_id']);
        if (!$author) {
            Logger::getInstance()->warning("Autore non trovato per nota", [
                "note_id" => $id,
                "student_id" => $note['student_id']
            ]);
            return null;
        }
        
        // Carica file
        $filesFromDb = (new File())->where('note_id', '=', $id)->get();
        $files = array_map(function($file) {
            return [
                'id' => $file['id'],
                'filename' => $file['filename'],
                'size' => round($file['size'] / 1024 / 1024, 1) . ' MB',
                'current_version' => 1, // TODO: Implementare versioning se necessario
                'mime_type' => $file['mime_type']
            ];
        }, $filesFromDb);
        
        // Carica commenti con JOIN per gli autori
        $commentsFromDb = (new Comment())
            ->select([
                'COMMENT.*',
                'USER.name AS author_name'
            ])
            ->join('USER', 'COMMENT.student_id', '=', 'USER.id')
            ->where('COMMENT.note_id', '=', $id)
            ->where('COMMENT.parent_comment_id', 'IS', null)
            ->order_by('COMMENT.created_at', 'DESC')
            ->get();
        
        $comments = array_map(function($comment) use ($note) {
            // Carica risposte se esistono
            $repliesFromDb = (new Comment())
                ->select([
                    'COMMENT.*',
                    'USER.name AS author_name'
                ])
                ->join('USER', 'COMMENT.student_id', '=', 'USER.id')
                ->where('COMMENT.parent_comment_id', '=', $comment['id'])
                ->order_by('COMMENT.created_at', 'ASC')
                ->get();
            
            $replies = array_map(function($reply) {
                return [
                    'id' => $reply['id'],
                    'author' => $reply['author_name'],
                    'author_id' => $reply['student_id'],
                    'content' => $reply['content'],
                    'created_at' => $reply['created_at']
                ];
            }, $repliesFromDb);
            
            return [
                'id' => $comment['id'],
                'author' => $comment['author_name'],
                'author_id' => $comment['student_id'],
                'content' => $comment['content'],
                'created_at' => $comment['created_at'],
                'is_author' => $comment['student_id'] === $note['student_id'],
                'replies' => $replies
            ];
        }, $commentsFromDb);
        
        // TODO: Implementare conteggio likes
        $likesCount = 0;
        $userHasLiked = false;
        
        return [
            'id' => $note['id'],
            'title' => $note['title'],
            'description' => $note['description'] ?? 'Nessuna descrizione',
            'author' => [
                'id' => $author['id'],
                'name' => $author['name'],
                'reputation' => $author['reputation'] ?? 0
            ],
            'course' => 'Corso', // TODO: Implementare relazione con corsi
            'tags' => [], // TODO: Implementare tags se necessario
            'visibility' => $note['visibility'],
            'created_at' => $note['created_at'],
            'updated_at' => $note['updated_at'] ?? $note['created_at'],
            'likes_count' => $likesCount,
            'user_has_liked' => $userHasLiked,
            'files' => $files,
            'comments' => $comments
        ];
        
    } catch (\Exception $e) {
        Logger::getInstance()->error("Errore caricamento nota da DB", [
            "note_id" => $id,
            "error" => $e->getMessage(),
            "trace" => $e->getTraceAsString()
        ]);
        return null;
    }
  }

  private static function getNotesFromDatabase(int $limit): array {
    try {
        $notesFromDb = (new Note())
            ->select(['NOTE.*', 'USER.name AS student_name'])
            ->join('USER', 'NOTE.student_id', '=', 'USER.id')
            ->where('NOTE.deleted_at', 'IS', null)
            ->where('NOTE.visibility', '=', 'public')
            ->order_by('NOTE.created_at', 'DESC')
            ->limit($limit)
            ->get();
        
        $notes = [];
        foreach ($notesFromDb as $note) {
            $notes[] = [
                'id' => $note['id'],
                'title' => $note['title'],
                'student_name' => $note['student_name'],
                'description' => $note['description'] ?? 'Nessuna descrizione',
                'note_type' => $note['note_type'],
                'format' => $note['format'],
                'university' => $note['university']
            ];
        }
        
        return $notes;
        
    } catch (\Exception $e) {
        Logger::getInstance()->error("Errore caricamento note da DB", [
            "error" => $e->getMessage(),
            "trace" => $e->getTraceAsString()
        ]);
        return [];
    }
  }
}