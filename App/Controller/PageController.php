<?php
namespace App\Controller;

use App\Model\Factory\NoteFactory;
use App\View\View;
use App\Controller\NotesController;
use Core\Helper\SessionManager;

class PageController
{
    public function index(): void
    {
        // Inizializza la sessione
        SessionManager::start();
        
        // Carica le note più recenti
        $notesResult = NotesController::getAllNotes([
            'per_page' => 6,
            'page' => 1,
            'order' => ['field' => 'created_at', 'direction' => 'DESC']
        ]);
        
        $notes = $notesResult['data'] ?? [];
        
        // Trasforma le note in card usando NoteFactory
        $cards = array_map(function ($noteData) {
            return NoteFactory::searchNoteView(
                $noteData['id'],
                $noteData['title'],
                $noteData['student_name'],
                'Corso',  // TODO: aggiungere corso quando disponibile
                $noteData['description'] ?? 'Nessuna descrizione',
                $noteData['note_type'] ?? 'note',
                $noteData['format'] ?? 'PDF',
                $noteData['university'] ?? 'N/A',
                0,  // likes - TODO: aggiungere quando implementato
                0   // downloads - TODO: aggiungere quando implementato
            );
        }, $notes);
        
        // Crea un oggetto wrapper per la sessione
        $session = new class {
            public function isLoggedIn(): bool {
                return SessionManager::isLoggedIn();
            }
            
            public function getUserName(): ?string {
                $user = SessionManager::user();
                return $user['name'] ?? null;
            }
        };
        
        View::render('home', 'page', [
            'title' => 'Home',
            'cards' => $cards,
            'session' => $session
        ]);
    }

    public function search() : void
    {
        $q = $_GET['q'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;

        // Costruisci filtri/parametri per NotesController::searchNotes
        $filters = [
            'text' => $q,
            'university' => $_GET['university'] ?? '',
            'format' => $_GET['format'] ?? ''
        ];
        
        $paginate = [
            'page' => $page,
            'per_page' => $perPage
        ];

        $res = NotesController::searchNotes(
            $filters,
            $paginate
        );

        $notes = $res['data'] ?? []; 
        $meta = $res['meta'] ?? 
            [
                'total' => 0, 
                'per_page' => $perPage, 
                'current_page' => $page, 
                'total_pages' => 1
            ];

        $cards = array_map(function ($noteData) {
            return NoteFactory::searchNoteView(
                $noteData['id'],
                $noteData['title'],
                $noteData['student_name'],
                $noteData['course_name'] ?? 'Corso Sconosciuto',
                $noteData['description'],
                $noteData['note_type'],
                $noteData['format'] ?? 'PDF',
                $noteData['university'] ?? 'Unibo',
                $noteData['likes'],
                $noteData['downloads']
            );
        }, $notes);

        View::render('Sites/search', 'page', [
            'q' => $q,
            'cards' => $cards,
            'meta' => $meta,
            'queryParams' => ['q' => $q],
        ]);
    }
}