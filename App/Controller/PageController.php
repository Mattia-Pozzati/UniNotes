<?php
namespace App\Controller;

use App\Model\ViewModels\NotificationView;
use App\Model\ViewModels\NoteView;
use App\View\View;
use App\Service\MockDB;

class PageController
{
    public function index(): void
    {
        View::render('home', 'page', ['title' => 'Home']);
    }

    public function search()
    {
        $q = $_GET['q'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;

        if (defined('USE_MOCK_DB') && USE_MOCK_DB) {
            $mock = new MockDB();
            $allNotes = $mock->getSearchNotes(24);
        } else {
            // fallback: alcuni sample NoteView
            $allNotes = [
                NoteView::searchNoteView(1, 'Titolo A', 'Autore A', 'Corso A', 'Descrizione A', ['PDF'], 300, 1000),
                NoteView::searchNoteView(2, 'Titolo B', 'Autore B', 'Corso B', 'Descrizione B', ['Note'], 235, 10201),
                NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            ];
        }

        $totalCount = count($allNotes);
        $totalPages = max(1, ceil($totalCount / $perPage));
        $notes = array_slice($allNotes, ($page - 1) * $perPage, $perPage);

        View::render('Sites/search', 'page', [
            'q' => $q,
            'cards' => $notes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'perPage' => $perPage,
            'queryParams' => ['q' => $q],
        ]);
    }

    public function adminDashboard(): void
    {
        $perPage = 6;
        $activeTab = $_GET['tab'] ?? 'ban';
        $baseUrl = '/admin?';
        $queryParams = $_GET;

        if (defined(constant_name: 'USE_MOCK_DB') && USE_MOCK_DB) {
            $mock = new MockDB();
            $allNotes = $mock->getMyNotes();
            $allNotifications = $mock->getNotifications(10);
        } else {
            $allNotes = [
                NoteView::userNoteView(1, 'Mia Nota 1', 'Autore', 'Corso', 'Descrizione', ['PDF'], 300, 1000),
                NoteView::userNoteView(2, 'Mia Nota 2', 'Autore', 'Corso', 'Descrizione', ['Note'], 235, 10201),
            ];

            $allNotifications = [
                NotificationView::systemNotification(1, 'Admin', 'Messaggio sistema'),
                NotificationView::commentNotification(2, 10, 'Utente', 'Admin', 'Hai un nuovo commento'),
            ];
        }

        // Paginate datasets for tabs
        $notesPaginated = $this->paginate($allNotes, $perPage, 'notesPage');
        $notificationsPaginated = $this->paginate($allNotifications, $perPage, 'notificationsPage');

        // Tabs config per admin
        $tabs = [
            'ban' => [
                'label' => 'Ban Notes',
                'icon' => 'bi-file-earmark-text',
                'cards' => $notesPaginated['items'],
                'component' => 'Cards/noteCard',
                'pagination' => $notesPaginated,
            ],
            'notifications' => [
                'label' => 'Notifiche',
                'icon' => 'bi-bell',
                'cards' => $notificationsPaginated['items'],
                'component' => 'Cards/notificationCard',
                'pagination' => $notificationsPaginated,
            ],
        ];

        View::render('Dashboards/adminDashboard', 'page', compact([
            'tabs',
            'activeTab',
            'baseUrl',
            'queryParams'
        ]));
    }

    private function paginate(array $items, int $perPage, string $pageParam): array
    {
        $page = max(1, (int) ($_GET[$pageParam] ?? 1));
        $total = count($items);
        $totalPages = max(1, ceil($total / $perPage));

        return [
            'items' => array_slice($items, ($page - 1) * $perPage, $perPage),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pageParam' => $pageParam,
        ];
    }

    public function userDashboard() : void
    {
        $perPage = 6;
        $activeTab = $_GET['tab'] ?? 'my-notes';

        if (defined('USE_MOCK_DB') && USE_MOCK_DB) {
            $mock = new MockDB();
            $allMyNotes = $mock->getMyNotes();
            $allDownloadedNotes = $mock->getDownloadedNotes();
            $allNotifications = $mock->getNotifications(10);
        } else {
            $allMyNotes = [
                NoteView::userNoteView(1, 'Mia Nota 1', 'Autore', 'Corso', 'Descrizione', ['PDF'], 300, 1000),
                NoteView::userNoteView(2, 'Mia Nota 2', 'Autore', 'Corso', 'Descrizione', ['Note'], 235, 10201),
            ];

            $allDownloadedNotes = [
                NoteView::searchNoteView(10, 'Nota Scaricata 1', 'Autore', 'Corso', 'Descrizione', ['PDF'], 300, 1000),
                NoteView::searchNoteView(11, 'Nota Scaricata 2', 'Autore', 'Corso', 'Descrizione', ['Note'], 235, 10201),
            ];

            $allNotifications = [
                NotificationView::systemNotification(1, 'Admin', 'Messaggio sistema'),
                NotificationView::commentNotification(2, 10, 'Utente', 'Admin', 'Hai un nuovo commento'),
            ];
        }

        $myNotesPaginated = $this->paginate($allMyNotes, $perPage, 'myNotesPage');
        $downloadedPaginated = $this->paginate($allDownloadedNotes, $perPage, 'downloadedPage');
        $notificationsPaginated = $this->paginate($allNotifications, $perPage, 'notificationsPage');

        $courses = [];
        $tags = [];

        $tabs = [
            'my-notes' => [
                'label' => 'Mie Note',
                'icon' => 'bi-file-earmark-text',
                'cards' => $myNotesPaginated['items'],
                'component' => 'Cards/noteCard',
                'pagination' => $myNotesPaginated,
            ],
            'downloaded' => [
                'label' => 'Note Scaricate',
                'icon' => 'bi-download',
                'cards' => $downloadedPaginated['items'],
                'component' => 'Cards/noteCard',
                'pagination' => $downloadedPaginated,
            ],
            'notifications' => [
                'label' => 'Notifiche',
                'icon' => 'bi-bell',
                'badge' => count($allNotifications),
                'cards' => $notificationsPaginated['items'],
                'component' => 'Cards/notificationCard',
                'pagination' => $notificationsPaginated,
            ],
            'new-note' => [
                'label' => 'Nuova Nota',
                'icon' => 'bi-plus-circle',
                'form' => 'Forms/newNotesForm',
                'courses' => $courses,
                'tags' => $tags,
            ],
        ];

        View::render('Dashboards/userDashboard', 'page', [
            'tabs' => $tabs,
            'activeTab' => $activeTab,
            'queryParams' => $_GET,
        ]);
    }
}

?>