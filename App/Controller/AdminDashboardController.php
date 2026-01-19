<?php
namespace App\Controller;

use App\View\View;
use App\Controller\NotesController;
use App\Model\Factory\NoteFactory;
use Core\Helper\SessionManager;
use Core\Helper\Logger;

class AdminDashboardController
{
    public function show(): void
    {
        // Verifica autenticazione E che sia admin
        if (!SessionManager::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        
        if (!SessionManager::isAdmin()) {
            // Utente non admin -> redirect a user dashboard
            header('Location: /user/dashboard');
            exit;
        }

        $user = SessionManager::user();
        
        $perPage = 6;
        $activeTab = $_GET['tab'] ?? 'ban';
        
        // Preparazione tabs
        $tabs = [];
        
        // Tab 1: Ban Notes (tutte le note pubbliche per moderazione)
        if ($activeTab === 'ban') {
            $banPage = max(1, (int)($_GET['banPage'] ?? 1));
            $notesResult = NotesController::getAllNotes([
                'per_page' => $perPage,
                'page' => $banPage,
                'order' => ['field' => 'created_at', 'direction' => 'DESC'],
                'visibility' => 'public'
            ]);
            
            $banCards = array_map(function ($noteData) {
                return NoteFactory::adminNoteView(
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
            }, $notesResult['data'] ?? []);
            
            $tabs['ban'] = [
                'label' => 'Ban Notes',
                'icon' => 'bi-file-earmark-text',
                'cards' => $banCards,
                'component' => 'Cards/noteCard',
                'pagination' => [
                    'currentPage' => $notesResult['meta']['current_page'] ?? 1,
                    'totalPages' => $notesResult['meta']['total_pages'] ?? 1,
                    'pageParam' => 'banPage'
                ]
            ];
        } else {
            $tabs['ban'] = [
                'label' => 'Ban Notes',
                'icon' => 'bi-file-earmark-text',
                'cards' => [],
                'component' => 'Cards/noteCard'
            ];
        }
        
        // Tab 2: Notifiche
        if ($activeTab === 'notifications') {
            // TODO: implementare logica notifiche admin quando disponibile
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
        
        Logger::getInstance()->info("Admin dashboard caricata", [
            "admin_id" => $user['id'],
            "active_tab" => $activeTab
        ]);
        
        View::render('Dashboards/adminDashboard', 'page', [
            'title' => 'Admin Dashboard',
            'tabs' => $tabs,
            'activeTab' => $activeTab,
            'baseUrl' => '/admin',
            'queryParams' => $_GET
        ]);
    }
}