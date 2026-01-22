<?php
$q = $q ?? '';
$cards = $cards ?? [];  // <-- cambia da $notes a $cards
$currentPage = $meta['current_page'] ?? null;
$totalPages = $meta['total_pages'] ?? null;
$queryParams = $queryParams ?? ['q' => $q];
$courses = $courses ?? [];
?>

<?= \App\View\View::getComponent('Base/sectionHeader', [
    'title' => 'Cerca note',
    'p' => 'Trova appunti, PDF, riassunti'
]) ?>

<?= \App\View\View::getComponent('Forms/searchForm', ['courses' => $courses, 'action' => $action]) ?>

<?= \App\View\View::getComponent('Layout/Grid/cardGrid', [
    'cards' => $cards,
    'columnsTablet' => 2,
    'columnsDesktop' => 3,
    'component' => "Cards/noteCard",
    'currentPage' => $currentPage,
    'totalPages' => $totalPages,
    'baseUrl' => '/search',
    'queryParams' => $queryParams,
]) ?>