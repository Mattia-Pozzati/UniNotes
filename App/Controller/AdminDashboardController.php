<?php
namespace App\Controller;

use App\View\View;
use App\Controller\NotesController;
use App\Model\Factory\NoteFactory;
use Core\Helper\SessionManager;
use Core\Helper\Logger;
use App\Model\Factory\NotificationFactory;

class AdminDashboardController
{

    private function getSearchedNotesTab(int $perPage): array
    {
        $q = $_GET['q'] ?? '';
        $perPage = 6;
        $banPage = max(1, (int) ($_GET['banPage'] ?? 1));
        $notesResult = NotesController::searchNotes(
            [
                'text' => $q,
                'university' => $_GET['university'] ?? '',
                'format' => $_GET['format'] ?? '',
                'note_type' => $_GET['note_type'] ?? '',
                'course' => $_GET['course'] ?? ''
            ],
            [
                'per_page' => $perPage,
                'page' => $banPage,
                'order' => ['field' => 'created_at', 'direction' => 'DESC'],
            ]
        );

        $banCards = array_map(function ($noteData) {
            return NoteFactory::adminNoteView(
                (int) $noteData['id'],
                $noteData['title'],
                $noteData['student_name'],
                $noteData['course_name'] ?? 'Corso non specificato',
                $noteData['description'] ?? 'Nessuna descrizione',
                $noteData['note_type'] ?? null,
                $noteData['format'] ?? null,
                $noteData['university'] ?? null,
                $noteData['likes_count'] ?? 0,
                $noteData['downloads_count'] ?? 0
            );
        }, $notesResult['data'] ?? []);

        return [
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

    }

    private function getNotificationTab(int $adminId, int $perPage): array
    {
        $notificationResult = NotificationController::getMyNotifications($adminId);

        $notificationCards = array_map(function ($n) {
            return NotificationFactory::systemNotification(
                (int) $n['id'],
                $n['sender_id'],
                $n['recipient_id'],
                $n['sender_name'] ?? 'Sistema',
                $n['desc'] ?? 'Sistema Notification',
            );
        }, $notificationResult['data'] ?? []);

        return [
            'label' => 'Notifiche',
            'icon' => 'bi-bell',
            'badge' => $notificationResult['meta']['total'] ?? 0,
            'cards' => $notificationCards,
            'component' => 'Cards/notificationCard',
            'pagination' => [
                'currentPage' => $notificationResult['meta']['current_page'] ?? 1,
                'totalPages' => $notificationResult['meta']['total_pages'] ?? 1,
                'pageParam' => 'notificationsPage'
            ]
        ];

    }
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
        $tabs = [
            'ban' => $this->getSearchedNotesTab($perPage),
            'notification' => $this->getNotificationTab($user['id'], $perPage)
        ];

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