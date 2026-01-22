<?php
namespace App\Controller;

use App\View\View;
use App\Controller\NotesController;
use App\Model\User;
use App\Model\Factory\NoteFactory;
use App\Model\Factory\NotificationFactory;
use App\Model\Course;
use Core\Helper\SessionManager;
use Core\Helper\Logger;

class UserDashboardController
{

    private static function getMyNotesTab(int $studentId, int $perPage, int $page): array
    {
        $myNotesPage = max(1, (int) ($_GET['myNotesPage'] ?? 1));
        $myNotesResult = NotesController::getMyNotes($studentId, [
            'per_page' => $perPage,
            'page' => $myNotesPage,
            'order' => ['field' => 'created_at', 'direction' => 'DESC']
        ]);

        $myNotesCards = array_map(function ($noteData) {
            return NoteFactory::userDashboardNoteView(
                (int) $noteData['id'],
                $noteData['title'],
                $noteData['student_name'],
                $noteData['course_name'] ?? 'Corso Sconosciuto',
                $noteData['description'] ?? 'Nessuna descrizione',
                $noteData['note_type'] ?? null,
                $noteData['format'] ?? null,
                $noteData['university'] ?? null,
                $noteData['likes'] ?? 0, // likes
                $noteData['downloads'] ?? 0,  // downloads
                $noteData['visibility'] ?? 0
            );
        }, $myNotesResult['data'] ?? []);

        return [
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

    }

    private static function getDownloadedTab(int $studentId, int $perPage, int $page): array
    {

        $downloadedPage = max(1, (int) ($_GET['downloadedPage'] ?? 1));
        $downloadedResult = NotesController::getDownloadedNotes($studentId, [
            'per_page' => $perPage,
            'page' => $downloadedPage,
            'order' => ['field' => 'created_at', 'direction' => 'DESC']
        ]);

        $downloadedCards = array_map(function ($noteData) {
            return NoteFactory::searchNoteView(
                (int) $noteData['id'],
                $noteData['title'],
                $noteData['student_name'],
                $noteData['course_name'] ?? 'Corso Sconosciuto',
                $noteData['description'] ?? 'Nessuna descrizione',
                $noteData['note_type'] ?? null,
                $noteData['format'] ?? null,
                $noteData['university'] ?? null,
                $noteData['likes'] ?? 0, // likes
                $noteData['downloads'] ?? 0  // downloads
            );
        }, $downloadedResult['data'] ?? []);

        return [
            'label' => 'Note Scaricate',
            'icon' => 'bi-file-earmark-text',
            'cards' => $downloadedCards,
            'component' => 'Cards/noteCard',
            'pagination' => [
                'currentPage' => $downloadedResult['meta']['current_page'] ?? 1,
                'totalPages' => $downloadedResult['meta']['total_pages'] ?? 1,
                'pageParam' => 'downloadedPage'
            ]
        ];

    }

    private static function getNotificationsTab(int $studentId, int $perPage, int $page): array
    {
        $notificationPage = max(1, (int) ($_GET['notificationPage'] ?? 1));
        $notificationResult = NotificationController::getMyNotifications($studentId, [
            'per_page' => $perPage,
            'page' => $notificationPage,
            'order' => ['field' => 'created_at', 'direction' => 'DESC']
        ]);

        $notificationCards = array_map(function ($n) {
            $author = User::find($n['note_author_id']);
            return match ($n['type']) {
                'like' => NotificationFactory::likeNotification(
                    $n['id'],
                    $n['sender_id'],
                    $n['recipient_id'],
                    $n['note_id'],
                    $n['sender_name'],
                    $author['name'] ?? 'Sistema',
                    $n['message'],
                    $n['note_title'] ?? 'Titolo Nota'
                ),
                'comment' => NotificationFactory::commentNotification(
                    $n['id'],
                    $n['sender_id'],
                    $n['recipient_id'],
                    $n['note_id'],
                    $n['sender_name'],
                    $author['name'] ?? 'Sistema',
                    $n['message'],
                    $n['note_title'] ?? 'Titolo Nota'
                ),
                'system' => NotificationFactory::systemNotification(
                    $n['id'],
                    $n['sender_id'],
                    $n['recipient_id'],
                    $author['name'] ?? 'Sistema',
                    $n['message']
                ),
                default => null
            };
        }, $notificationResult['data'] ?? []);

        return [
            'label' => 'Notifiche',
            'icon' => 'bi-bell',
            'cards' => $notificationCards,
            'component' => 'Cards/notificationCard',
            'badge' => $notificationResult['meta']['total'] ?? 0,
            'pagination' => [
                'currentPage' => $notificationResult['meta']['current_page'] ?? 1,
                'totalPages' => $notificationResult['meta']['total_pages'] ?? 1,
                'pageParam' => 'notificationPage'
            ]
        ];
    }

    private static function getNewNotesTab(int $studentId, int $perPage, int $page): array
    {

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

        return  [
            'label' => 'Nuova Nota',
            'icon' => 'bi-plus-circle',
            'form' => 'Forms/newNotesForm',
            'courses' => $courses,
            'action' => '/note/create'
        ];
    }
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
        $tabs = [
            "my-notes"=> self::getMyNotesTab($userId, $perPage, 1),
            "downloaded" => self::getDownloadedTab($userId, $perPage, 1),
            "notifications" => self::getNotificationsTab($userId, $perPage, 1),
            "new-note" => self::getNewNotesTab($userId, $perPage, 1)
        ];

        View::render('Dashboards/userDashboard', 'page', [
            'title' => 'Dashboard - ' . htmlspecialchars($user['name']),
            'tabs' => $tabs,
            'activeTab' => $activeTab,
            'baseUrl' => '/user/dashboard',
            'queryParams' => $_GET
        ]);
    }
}