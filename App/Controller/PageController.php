<?php
namespace App\Controller;

use App\Model\ViewModels\NotificationView;
use App\Model\ViewModels\NoteView;
use App\View\View;

class PageController
{
    public function index()
    {
        View::render('home', "page", ["title" => "Home"]);
    }

    public function search()
    {
        $q = $_GET['q'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;

        $allNotes = [
            // ... le tue note

            NoteView::searchNoteView(1, 'Titolo A', 'Autore A', 'Corso A', 'Descrizione A', ['PDF'], 300, 1000),
            NoteView::searchNoteView(2, 'Titolo B', 'Autore B', 'Corso B', 'Descrizione B', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::searchNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
        ];

        $totalCount = count($allNotes);
        $totalPages = max(1, ceil($totalCount / $perPage));
        $notes = array_slice($allNotes, ($page - 1) * $perPage, $perPage);

        View::render('search', 'page', [
            'q' => $q,
            'cards' => $notes,  // <-- mantieni 'cards' per coerenza con card-grid
            'currentPage' => $page,  // <-- questo viene passato
            'totalPages' => $totalPages,  // <-- questo viene passato
            'totalCount' => $totalCount,
            'perPage' => $perPage,
            'queryParams' => ['q' => $q],  // <-- aggiungi questo!
        ]);
    }



    public function adminDashboard()
    {
        $q = $_GET['q'] ?? '';
        $notesPage = max(1, (int) ($_GET['notesPage'] ?? 1));
        $notificationsPage = max(1, (int) ($_GET['notificationsPage'] ?? 1));
        $perPage = 6;

        // Note
        $allNotes = [
            NoteView::adminNoteView(1, 'Titolo A', 'Autore A', 'Corso A', 'Descrizione A', ['PDF'], 300, 1000),
            NoteView::adminNoteView(2, 'Titolo B', 'Autore B', 'Corso B', 'Descrizione B', ['Note'], 235, 10201),
            NoteView::adminNoteView(3, 'Titolo C', 'Autore C', 'Corso C', 'Descrizione C', ['Note'], 235, 10201),
            NoteView::adminNoteView(4, 'Titolo D', 'Autore D', 'Corso D', 'Descrizione D', ['Note'], 235, 10201),
            NoteView::adminNoteView(5, 'Titolo E', 'Autore E', 'Corso E', 'Descrizione E', ['Note'], 235, 10201),
            NoteView::adminNoteView(6, 'Titolo F', 'Autore F', 'Corso F', 'Descrizione F', ['Note'], 235, 10201),
            NoteView::adminNoteView(7, 'Titolo G', 'Autore G', 'Corso G', 'Descrizione G', ['Note'], 235, 10201),
            NoteView::adminNoteView(8, 'Titolo H', 'Autore H', 'Corso H', 'Descrizione H', ['Note'], 235, 10201),
            NoteView::adminNoteView(9, 'Titolo I', 'Autore I', 'Corso I', 'Descrizione I', ['Note'], 235, 10201),
            NoteView::adminNoteView(10, 'Titolo J', 'Autore J', 'Corso J', 'Descrizione J', ['Note'], 235, 10201),
            NoteView::adminNoteView(11, 'Titolo K', 'Autore K', 'Corso K', 'Descrizione K', ['Note'], 235, 10201),
            NoteView::adminNoteView(12, 'Titolo L', 'Autore L', 'Corso L', 'Descrizione L', ['Note'], 235, 10201),
            NoteView::adminNoteView(13, 'Titolo M', 'Autore M', 'Corso M', 'Descrizione M', ['Note'], 235, 10201),
        ];

        $notesTotalCount = count($allNotes);
        $notesTotalPages = max(1, ceil($notesTotalCount / $perPage));
        $notes = array_slice($allNotes, ($notesPage - 1) * $perPage, $perPage);

        // Notifiche
        $allNotification = [
            NotificationView::systemNotification(1, "PippoAdmin", "TomareHomo"),
            NotificationView::commentNotification(2, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::likeNotification(3, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::systemNotification(4, "PippoAdmin", "TomareHomo"),
            NotificationView::commentNotification(5, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::likeNotification(6, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::systemNotification(7, "PippoAdmin", "TomareHomo"),
            NotificationView::commentNotification(8, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::likeNotification(9, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::systemNotification(10, "PippoAdmin", "TomareHomo"),
            NotificationView::commentNotification(11, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::likeNotification(12, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::systemNotification(13, "PippoAdmin", "TomareHomo"),
            NotificationView::commentNotification(14, 10, "Pluto", "PippoAdmin", "TomareHomo"),
            NotificationView::likeNotification(15, 10, "Pluto", "PippoAdmin", "TomareHomo"),
        ];

        $notificationsTotalCount = count($allNotification);
        $notificationsTotalPages = max(1, ceil($notificationsTotalCount / $perPage));
        $notification = array_slice($allNotification, ($notificationsPage - 1) * $perPage, $perPage);

        View::render('adminDashboard', 'page', [
            'q' => $q,
            'notes' => $notes,
            'notesCurrentPage' => $notesPage,
            'notesTotalPages' => $notesTotalPages,
            'notifications' => $notification,
            'notificationsCurrentPage' => $notificationsPage,
            'notificationsTotalPages' => $notificationsTotalPages,
            'queryParams' => ['q' => $q],
        ]);
    }

    public function userDashboard()
    {
        $activeTab = $_GET['tab'] ?? 'my-notes'; // 'my-notes', 'downloaded', 'notifications', 'new-note'
        $perPage = 6;

        // Panel 1: Le mie note pubblicate
        $myNotesPage = max(1, (int) ($_GET['myNotesPage'] ?? 1));

        $allMyNotes = [
            NoteView::userNoteView(1, 'Mia Nota 1', 'Autore', 'Corso', 'Descrizione', ['PDF'], 300, 1000),
            NoteView::userNoteView(2, 'Mia Nota 2', 'Autore', 'Corso', 'Descrizione', ['Note'], 235, 10201),
            // ... altre note dell'utente
        ];

        $myNotesTotalCount = count($allMyNotes);
        $myNotesTotalPages = max(1, ceil($myNotesTotalCount / $perPage));
        $myNotes = array_slice($allMyNotes, ($myNotesPage - 1) * $perPage, $perPage);

        // Panel 2: Note scaricate
        $downloadedPage = max(1, (int) ($_GET['downloadedPage'] ?? 1));

        $allDownloadedNotes = [
            NoteView::searchNoteView(10, 'Nota Scaricata 1', 'Autore', 'Corso', 'Descrizione', ['PDF'], 300, 1000),
            NoteView::searchNoteView(11, 'Nota Scaricata 2', 'Autore', 'Corso', 'Descrizione', ['Note'], 235, 10201),
            // ... altre note scaricate
        ];

        $downloadedTotalCount = count($allDownloadedNotes);
        $downloadedTotalPages = max(1, ceil($downloadedTotalCount / $perPage));
        $downloadedNotes = array_slice($allDownloadedNotes, ($downloadedPage - 1) * $perPage, $perPage);

        // Panel 3: Notifiche
        $notificationsPage = max(1, (int) ($_GET['notificationsPage'] ?? 1));

        $allNotifications = [
            NotificationView::systemNotification(1, "Admin", "Messaggio sistema"),
            NotificationView::commentNotification(2, 10, "Utente", "Admin", "Hai un nuovo commento"),
            // ... altre notifiche
        ];

        $notificationsTotalCount = count($allNotifications);
        $notificationsTotalPages = max(1, ceil($notificationsTotalCount / $perPage));
        $notifications = array_slice($allNotifications, ($notificationsPage - 1) * $perPage, $perPage);

        // Panel 4: Form nuova nota (non serve paginazione)
        $courses = []; // TODO: carica i corsi disponibili
        $tags = []; // TODO: carica i tag disponibili

        View::render('userDashboard', 'page', [
            'activeTab' => $activeTab,
            'myNotes' => $myNotes,
            'myNotesCurrentPage' => $myNotesPage,
            'myNotesTotalPages' => $myNotesTotalPages,
            'downloadedNotes' => $downloadedNotes,
            'downloadedCurrentPage' => $downloadedPage,
            'downloadedTotalPages' => $downloadedTotalPages,
            'notifications' => $notifications,
            'notificationsCurrentPage' => $notificationsPage,
            'notificationsTotalPages' => $notificationsTotalPages,
            'courses' => $courses,
            'tags' => $tags,
            'queryParams' => [],
        ]);
    }
}

?>