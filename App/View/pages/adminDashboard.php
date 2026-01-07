<?php
$q = $q ?? '';
$notes = $notes ?? [];
$notifications = $notifications ?? [];
$queryParams = $queryParams ?? [];

?>

<?= \App\View\View::getComponent('section-header', [
    'titolo' => 'Blocca',
    'p' => 'Blocca Appunti che non rispettano il regolamento, PDF, riassunti'
]) ?>

<?= \App\View\View::getComponent('search-form') ?>

<?= \App\View\View::getComponent('card-grid', [
    'cards' => $notes,
    'columnsTablet' => 2,
    'columnsDesktop' => 3,
    'component' => "note-card",
    'currentPage' => $notesCurrentPage ?? null,
    'totalPages' => $notesTotalPages ?? null,
    'baseUrl' => '/admin',
    'queryParams' => $queryParams,
    'pageParam' => 'notesPage',
]) ?>

<?= \App\View\View::getComponent('section-header', [
    'titolo' => 'Notifiche',
    'p' => 'Notifiche admin'
]) ?>

<?= \App\View\View::getComponent('card-grid', [
    'cards' => $notifications,
    'columnsTablet' => 2,
    'columnsDesktop' => 3,
    'component' => 'notification-card',
    'currentPage' => $notificationsCurrentPage ?? null,
    'totalPages' => $notificationsTotalPages ?? null,
    'baseUrl' => '/admin',
    'queryParams' => $queryParams,
    'pageParam' => 'notificationsPage',
]) ?>