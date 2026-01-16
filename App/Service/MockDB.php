<?php

namespace App\Service;

class MockDB
{
    private function makeNote(int $id, string $titlePrefix = 'Titolo'): array
    {
        return [
            'id' => $id,
            'titolo' => "{$titlePrefix} {$id}",
            'autore' => "Autore {$id}",
            'corso' => "Corso ".($id % 5),
            'desc' => "Descrizione esempio per {$titlePrefix} {$id}",
            'tags' => ['Tag' . ($id % 3), 'Argomento' . ($id % 4)],
            'likes' => rand(0, 200),
            'downloads' => rand(0, 500),
            'chatEnabled' => $id % 2 === 0,
            'buttonsEnabled' => [true, true],
            'buttons' => [
                ['text' => 'Visualizza', 'link' => '/note/' . $id, 'class' => 'btn-primary'],
                ['text' => 'Scarica', 'link' => '/note/' . $id . '/download', 'class' => 'btn-outline-secondary'],
            ]
        ];
    }

    public function getSearchNotes(int $count = 18): array
    {
        $res = [];
        for ($i = 1; $i <= $count; $i++) {
            $res[] = $this->makeNote($i, 'Nota');
        }
        return $res;
    }

    public function getAdminNotes(): array
    {
        $res = [];
        for ($i = 1; $i <= 13; $i++) {
            $res[] = $this->makeNote($i, 'Admin Nota');
        }
        return $res;
    }

    public function getNotifications(int $count = 8): array
    {
        $res = [];
        for ($i = 1; $i <= $count; $i++) {
            $res[] = [
                'id' => $i,
                'title' => "Notifica {$i}",
                'author' => 'Sistema',
                'desc' => "Testo notifica {$i}",
            ];
        }
        return $res;
    }

    public function getMyNotes(): array
    {
        return $this->getSearchNotes(20);
    }

    public function getDownloadedNotes(): array
    {
        return $this->getSearchNotes(20);
    }

    public function getTabs(string $baseUrl = '/user/dashboard'): array
    {
        return [
            'my-notes' => [
                'label' => 'Mie Note',
                'icon' => 'bi-file-earmark-text',
                'cards' => $this->getMyNotes(),
                'component' => 'Cards/noteCard',
                'pagination' => ['currentPage' => 1, 'totalPages' => 1, 'pageParam' => 'myNotesPage'],
            ],
            'downloaded' => [
                'label' => 'Note Scaricate',
                'icon' => 'bi-download',
                'cards' => $this->getDownloadedNotes(),
                'component' => 'Cards/noteCard',
                'pagination' => ['currentPage' => 1, 'totalPages' => 1, 'pageParam' => 'downloadedPage'],
            ],
            'notifications' => [
                'label' => 'Notifiche',
                'icon' => 'bi-bell',
                'badge' => count($this->getNotifications()),
                'cards' => $this->getNotifications(),
                'component' => 'Cards/notificationCard',
                'pagination' => ['currentPage' => 1, 'totalPages' => 1, 'pageParam' => 'notificationsPage'],
            ],
            'new-note' => [
                'label' => 'Nuova Nota',
                'icon' => 'bi-plus-circle',
                'form' => 'Forms/newNotesForm',
                'courses' => [],
                'tags' => [],
            ],
        ];
    }
}
