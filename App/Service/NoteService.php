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

  /**
   * Controlla se il database è disponibile
   */
  private static function isDatabaseAvailable(): bool {
    if (self::$dbAvailable !== null) {
        return self::$dbAvailable;
    }
    
    try {
        Database::getInstance();
        self::$dbAvailable = true;
        Logger::getInstance()->info("Database disponibile - uso dati reali");
    } catch (\Exception $e) {
        self::$dbAvailable = false;
        Logger::getInstance()->warning("Database non disponibile - uso mockup", [
            "error" => $e->getMessage()
        ]);
    }
    
    return self::$dbAvailable;
  }

  /**
   * Restituisce una nota completa per ID
   */
  public static function getFullNote(int $id): ?array {
    if (self::isDatabaseAvailable()) {
        return self::getFullNoteFromDatabase($id);
    }
    return self::getFullNoteFromMockup($id);
  }

  /**
   * Restituisce le note per la home
   */
  public static function getNotesForHome(int $limit = 10): array {
    if (self::isDatabaseAvailable()) {
        return self::getNotesFromDatabase($limit);
    }
    return self::getNotesFromMockup();
  }


  private static function getFullNoteFromDatabase(int $id): ?array {
    try {
        $note = Note::find($id);
        
        if (!$note || $note->is_deleted()) {
            return null;
        }
        
        $author = User::find($note->student_id());
        
        // Carica file
        $filesFromDb = (new File())->where('note_id', '=', $id)->get();
        $files = array_map(function($file) {
            return [
                'id' => $file->id(),
                'filename' => $file->filename(),
                'size' => round($file->size() / 1024 / 1024, 1) . ' MB',
                'current_version' => $file->current_version(),
                'mime_type' => $file->mime_type()
            ];
        }, $filesFromDb);
        
        // Carica commenti
        $commentsFromDb = (new Comment())->where('note_id', '=', $id)->get();
        $comments = array_map(function($comment) use ($note) {
            $commentAuthor = User::find($comment->student_id());
            return [
                'id' => $comment->id(),
                'author' => $commentAuthor->name(),
                'author_id' => $commentAuthor->id(),
                'content' => $comment->content(),
                'created_at' => $comment->created_at(),
                'is_author' => $comment->student_id() === $note->student_id()
            ];
        }, $commentsFromDb);
        
        // TODO: Implementare likes count e tags
        
        return [
            'id' => $note->id(),
            'title' => $note->title(),
            'description' => 'Descrizione dal database',
            'author' => [
                'id' => $author->id(),
                'name' => $author->name(),
                'reputation' => $author->reputation()
            ],
            'course' => 'Corso DB',
            'tags' => [],
            'visibility' => $note->visibility(),
            'created_at' => $note->create_at(),
            'updated_at' => $note->updated_at(),
            'likes_count' => 0,
            'user_has_liked' => false,
            'files' => $files,
            'comments' => $comments
        ];
        
    } catch (\Exception $e) {
        Logger::getInstance()->error("Errore caricamento nota da DB", [
            "note_id" => $id,
            "error" => $e->getMessage()
        ]);
        return null;
    }
  }

  private static function getNotesFromDatabase(int $limit): array {
    try {
        $notesFromDb = (new Note())
            ->where('is_deleted', '=', 0)
            ->where('visibility', '=', 'public')
            ->limit($limit)
            ->get();
        
        $notes = [];
        foreach ($notesFromDb as $note) {
            $author = User::find($note->student_id());
            
            $notes[] = [
                'id' => $note->id(),
                'titolo' => $note->title(),
                'autore' => $author->name(),
                'corso' => 'Corso',
                'desc' => 'Descrizione breve',
                'chatEnabled' => true,
                'tags' => []
            ];
        }
        
        return $notes;
        
    } catch (\Exception $e) {
        Logger::getInstance()->error("Errore caricamento note da DB", [
            "error" => $e->getMessage()
        ]);
        return [];
    }
  }



  private static function getFullNoteFromMockup(int $id): ?array {
    $mockNotes = self::getMockupData();
    
    foreach ($mockNotes as $note) {
        if ($note['id'] === $id) {
            return $note;
        }
    }
    
    return null;
  }

  private static function getNotesFromMockup(): array {
    $mockNotes = self::getMockupData();
    $simplified = [];
    
    foreach ($mockNotes as $note) {
        $simplified[] = [
            'id' => $note['id'],
            'titolo' => $note['title'],
            'autore' => $note['author']['name'],
            'corso' => $note['course'],
            'desc' => $note['description'],
            'chatEnabled' => !empty($note['files']),
            'tags' => $note['tags']
        ];
    }
    
    return $simplified;
  }

  /**
   * Dati mockup completi
   */
  private static function getMockupData(): array {
    return [
        [
            'id' => 1,
            'title' => 'Appunti di Analisi',
            'description' => 'Appunti completi del corso di Analisi Matematica con tutti i teoremi, dimostrazioni ed esempi svolti in classe.',
            'author' => [
                'id' => 1,
                'name' => 'Mario Rossi',
                'reputation' => 42
            ],
            'course' => 'Analisi 1',
            'tags' => ['PDF', 'Note', 'Salva'],
            'visibility' => 'public',
            'created_at' => '2024-01-15',
            'updated_at' => '2024-01-20',
            'likes_count' => 15,
            'user_has_liked' => false,
            'files' => [
                [
                    'id' => 1,
                    'filename' => 'analisi_parte1.pdf',
                    'size' => '2.5 MB',
                    'current_version' => 2,
                    'mime_type' => 'application/pdf'
                ]
            ],
            'comments' => [
              [
                  'id' => 1,
                  'author' => 'Luigi Verdi',
                  'author_id' => 2,
                  'content' => 'Ottimi appunti, molto chiari!',
                  'created_at' => '2024-01-16 10:30',
                  'is_author' => false,
                  'replies' => [
                      [
                          'id' => 10,
                          'author' => 'Mario Rossi',
                          'author_id' => 1,
                          'content' => 'Grazie mille!',
                          'created_at' => '2024-01-16 11:00'
                      ],
                      [
                          'id' => 11,
                          'author' => 'Anna Neri',
                          'author_id' => 6,
                          'content' => 'Sono d\'accordo, ottimo lavoro!',
                          'created_at' => '2024-01-16 12:30'
                      ]
                  ]
              ],
              [
                  'id' => 2,
                  'author' => 'Mario Rossi',
                  'author_id' => 1,
                  'content' => 'Grazie! Ho aggiornato la parte sui limiti.',
                  'created_at' => '2024-01-17 14:20',
                  'is_author' => true,
                  'replies' => []
              ]
          ]
        ],
        [
            'id' => 2,
            'title' => 'Fisica Generale',
            'description' => 'Riassunto delle lezioni di fisica con esempi ed esercizi svolti.',
            'author' => [
                'id' => 3,
                'name' => 'Luigi Bianchi',
                'reputation' => 28
            ],
            'course' => 'Fisica 1',
            'tags' => ['PDF'],
            'visibility' => 'public',
            'created_at' => '2024-01-10',
            'updated_at' => '2024-01-10',
            'likes_count' => 8,
            'user_has_liked' => false,
            'files' => [
                [
                    'id' => 2,
                    'filename' => 'fisica_meccanica.pdf',
                    'size' => '1.8 MB',
                    'current_version' => 1,
                    'mime_type' => 'application/pdf'
                ]
            ],
            'comments' => [
                [
                    'id' => 3,
                    'author' => 'Giuseppe Neri',
                    'author_id' => 4,
                    'content' => 'Molto utile per preparare l\'esame!',
                    'created_at' => '2024-01-12 15:00',
                    'is_author' => false
                ]
            ]
        ],
        [
            'id' => 3,
            'title' => 'Programmazione',
            'description' => 'Esercizi e soluzioni di programmazione in C e Python con spiegazioni dettagliate.',
            'author' => [
                'id' => 4,
                'name' => 'Anna Verdi',
                'reputation' => 35
            ],
            'course' => 'Informatica',
            'tags' => ['Code', 'Esercizi'],
            'visibility' => 'public',
            'created_at' => '2024-01-12',
            'updated_at' => '2024-01-18',
            'likes_count' => 22,
            'user_has_liked' => false,
            'files' => [
                [
                    'id' => 3,
                    'filename' => 'esercizi_c.pdf',
                    'size' => '3.2 MB',
                    'current_version' => 3,
                    'mime_type' => 'application/pdf'
                ],
                [
                    'id' => 4,
                    'filename' => 'soluzioni_python.pdf',
                    'size' => '2.1 MB',
                    'current_version' => 1,
                    'mime_type' => 'application/pdf'
                ]
            ],
            'comments' => [
                [
                    'id' => 4,
                    'author' => 'Marco Neri',
                    'author_id' => 5,
                    'content' => 'Gli esercizi sono molto utili!',
                    'created_at' => '2024-01-14 09:15',
                    'is_author' => false
                ],
                [
                    'id' => 5,
                    'author' => 'Anna Verdi',
                    'author_id' => 4,
                    'content' => 'Grazie! A breve aggiungerò altri esercizi su Python.',
                    'created_at' => '2024-01-15 11:30',
                    'is_author' => true
                ]
            ]
        ]
    ];
  }
}
