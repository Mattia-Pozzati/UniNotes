<?php
$q = $q ?? '';
$cards = $cards ?? [];  // <-- cambia da $notes a $cards
$currentPage = $currentPage ?? null;
$totalPages = $totalPages ?? null;
$queryParams = $queryParams ?? ['q' => $q];  // <-- aggiungi questo
?>

<?= \App\View\View::getComponent('section-header', [
    'titolo' => 'Cerca note',
    'p' => 'Trova appunti, PDF, riassunti'
]) ?>

<?= \App\View\View::getComponent('search-form') ?>

<?= \App\View\View::getComponent('card-grid', [
    'cards' => $cards,  // <-- usa $cards
    'columnsTablet' => 2,
    'columnsDesktop' => 3,
    'component' => "note-card",
    'currentPage' => $currentPage,  // <-- passa direttamente, non con ?? null
    'totalPages' => $totalPages,  // <-- passa direttamente
    'baseUrl' => '/search',
    'queryParams' => $queryParams,  // <-- passa questo
]) ?>