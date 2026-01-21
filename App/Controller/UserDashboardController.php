<?php
namespace App\Controller;

use App\View\View;
use App\Controller\NotesController;
use App\Model\Factory\NoteFactory;
use App\Model\Course;
use Core\Helper\SessionManager;
use Core\Helper\Logger;

class UserDashboardController
{
    public function show(): void
    {
        // Verifica autenticazione
        if (!SessionManager::isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $user = SessionManager::user();
        $userId = $user['id'];
        
        $perPage = 6;
        $activeTab = $_GET['tab'] ?? 'my-notes';
        
        Logger::getInstance()->info("User dashboard - inizio caricamento", [
            "user_id" => $userId,
            "active_tab" => $activeTab
        ]);
        
        // Preparazione tabs
        $tabs = [];
        
        // Tab 1: Mie Note
        if ($activeTab === 'my-notes') {
            $myNotesPage = max(1, (int)($_GET['myNotesPage'] ?? 1));
            $myNotesResult = NotesController::getMyNotes($userId, [
                'per_page' => $perPage,
                'page' => $myNotesPage,
                'order' => ['field' => 'created_at', 'direction' => 'DESC']
            ]);
            
            $myNotesCards = array_map(function ($noteData) {
                return NoteFactory::userDashboardNoteView(
                    (int)$noteData['id'],
                    $noteData['title'],
                    $noteData['student_name'],
                    'Corso', // TODO: aggiungere corso
                    $noteData['description'] ?? 'Nessuna descrizione',
                    $noteData['note_type'] ?? null,
                    $noteData['format'] ?? null,
                    $noteData['university'] ?? null,
                    0, // likes
                    0  // downloads
                );
            }, $myNotesResult['data'] ?? []);
            
            $tabs['my-notes'] = [
                'label' => 'Mie Note',
                'icon' => 'bi-file-earmark-text',
                'cards' => $myNotesCards,
                'component' => 'Cards/noteCard',
                'pagination' => [
                    'currentPage' => $myNotesResult['meta']['current_page'] ?? 1,
                    'totalPages' => $myNotesResult['meta']['total_pages'] ?? 1,
                    'pageParam' => 'myNotesPage'
                ]
            ];
        } else {
            $tabs['my-notes'] = [
                'label' => 'Mie Note',
                'icon' => 'bi-file-earmark-text',
                'cards' => [],
                'component' => 'Cards/noteCard'
            ];
        }
        
        // Tab 2: Note Scaricate
        if ($activeTab === 'downloaded') {
            // TODO: implementare logica note scaricate quando disponibile
            $tabs['downloaded'] = [
                'label' => 'Note Scaricate',
                'icon' => 'bi-download',
                'cards' => [],
                'component' => 'Cards/noteCard',
                'pagination' => [
                    'currentPage' => 1,
                    'totalPages' => 1,
                    'pageParam' => 'downloadedPage'
                ]
            ];
        } else {
            $tabs['downloaded'] = [
                'label' => 'Note Scaricate',
                'icon' => 'bi-download',
                'cards' => [],
                'component' => 'Cards/noteCard'
            ];
        }
        
        // Tab 3: Notifiche
        if ($activeTab === 'notifications') {
            // TODO: implementare logica notifiche quando disponibile
            $tabs['notifications'] = [
                'label' => 'Notifiche',
                'icon' => 'bi-bell',
                'badge' => 0,
                'cards' => [],
                'component' => 'Cards/notificationCard',
                'pagination' => [
                    'currentPage' => 1,
                    'totalPages' => 1,
                    'pageParam' => 'notificationsPage'
                ]
            ];
        } else {
            $tabs['notifications'] = [
                'label' => 'Notifiche',
                'icon' => 'bi-bell',
                'badge' => 0,
                'cards' => [],
                'component' => 'Cards/notificationCard'
            ];
        }
        
        // Tab 4: Nuova Nota - CARICA SEMPRE I CORSI
        $courses = [];
        try {
            Logger::getInstance()->info("Caricamento corsi dal database...");
            
            $courseModel = new Course();
            $courses = $courseModel->getAll();
            
            Logger::getInstance()->info("Corsi caricati con successo", [
                "count" => count($courses),
                "courses" => $courses
            ]);
        } catch (\Exception $e) {
            Logger::getInstance()->error("Errore caricamento corsi", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
        }
        
        $tabs['new-note'] = [
            'label' => 'Nuova Nota',
            'icon' => 'bi-plus-circle',
            'form' => 'Forms/newNotesForm',
            'courses' => $courses,
            'tags' => [],
            'action' => '/note/create'
        ];
        
        Logger::getInstance()->info("User dashboard caricata", [
            "user_id" => $userId,
            "active_tab" => $activeTab,
            "courses_count" => count($courses)
        ]);
        
        View::render('Dashboards/userDashboard', 'page', [
            'title' => 'Dashboard - ' . htmlspecialchars($user['name']),
            'tabs' => $tabs,
            'activeTab' => $activeTab,
            'baseUrl' => '/user/dashboard',
            'queryParams' => $_GET
        ]);
    }
}