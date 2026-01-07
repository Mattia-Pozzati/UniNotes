<?php
$activeTab = $activeTab ?? 'my-notes';
$myNotes = $myNotes ?? [];
$downloadedNotes = $downloadedNotes ?? [];
$notifications = $notifications ?? [];
$courses = $courses ?? [];
$tags = $tags ?? [];
$queryParams = $queryParams ?? [];
?>

<nav>
    <ul class="nav nav-pills  nav-justified border-primary" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link <?= $activeTab === 'my-notes' ? 'active' : '' ?>" 
                data-bs-toggle="tab" 
                data-bs-target="#my-notes-panel" 
                type="button"
                role="tab">
                <i class="bi bi-file-earmark-text me-1"></i>
                Le mie note
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link <?= $activeTab === 'downloaded' ? 'active' : '' ?>" 
                data-bs-toggle="tab" 
                data-bs-target="#downloaded-panel" 
                type="button"
                role="tab">
                <i class="bi bi-download me-1"></i>
                Note scaricate
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link <?= $activeTab === 'notifications' ? 'active' : '' ?>" 
                data-bs-toggle="tab" 
                data-bs-target="#notifications-panel" 
                type="button"
                role="tab">
                <i class="bi bi-bell me-1"></i>
                Notifiche
                <?php if (count($notifications) > 0): ?>
                    <span class="badge bg-danger ms-1"><?= count($notifications) ?></span>
                <?php endif; ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link <?= $activeTab === 'new-note' ? 'active' : '' ?>" 
                data-bs-toggle="tab" 
                data-bs-target="#new-note-panel" 
                type="button"
                role="tab">
                <i class="bi bi-plus-circle me-1"></i>
                Nuova nota
            </button>
        </li>
    </ul>
</nav>

<div class="tab-content mt-3">
    <!-- Panel 1: Le mie note pubblicate -->
    <div 
        class="tab-pane fade <?= $activeTab === 'my-notes' ? 'show active' : '' ?>" 
        id="my-notes-panel" 
        role="tabpanel">
        
        <?= \App\View\View::getComponent('section-header', [
            'titolo' => 'Le mie note',
            'p' => 'Gestisci le tue note pubblicate'
        ]) ?>
        
        <?= \App\View\View::getComponent('card-grid', [
            'cards' => $myNotes,
            'columnsTablet' => 2,
            'columnsDesktop' => 3,
            'component' => 'note-card',
            'currentPage' => $myNotesCurrentPage ?? null,
            'totalPages' => $myNotesTotalPages ?? null,
            'baseUrl' => '/user/dashboard',
            'queryParams' => array_merge($queryParams, ['tab' => 'my-notes']),
            'pageParam' => 'myNotesPage',
        ]) ?>
    </div>
    
    <!-- Panel 2: Note scaricate -->
    <div 
        class="tab-pane fade <?= $activeTab === 'downloaded' ? 'show active' : '' ?>" 
        id="downloaded-panel" 
        role="tabpanel">
        
        <?= \App\View\View::getComponent('section-header', [
            'titolo' => 'Note scaricate',
            'p' => 'Le note che hai scaricato'
        ]) ?>
        
        <?= \App\View\View::getComponent('card-grid', [
            'cards' => $downloadedNotes,
            'columnsTablet' => 2,
            'columnsDesktop' => 3,
            'component' => 'note-card',
            'currentPage' => $downloadedCurrentPage ?? null,
            'totalPages' => $downloadedTotalPages ?? null,
            'baseUrl' => '/user/dashboard',
            'queryParams' => array_merge($queryParams, ['tab' => 'downloaded']),
            'pageParam' => 'downloadedPage',
        ]) ?>
    </div>
    
    <!-- Panel 3: Notifiche -->
    <div 
        class="tab-pane fade <?= $activeTab === 'notifications' ? 'show active' : '' ?>" 
        id="notifications-panel" 
        role="tabpanel">
        
        <?= \App\View\View::getComponent('section-header', [
            'titolo' => 'Notifiche',
            'p' => 'Le tue notifiche recenti'
        ]) ?>
        
        <?= \App\View\View::getComponent('card-grid', [
            'cards' => $notifications,
            'columnsTablet' => 2,
            'columnsDesktop' => 3,
            'component' => 'notification-card',
            'currentPage' => $notificationsCurrentPage ?? null,
            'totalPages' => $notificationsTotalPages ?? null,
            'baseUrl' => '/user/dashboard',
            'queryParams' => array_merge($queryParams, ['tab' => 'notifications']),
            'pageParam' => 'notificationsPage',
        ]) ?>
    </div>
    
    <!-- Panel 4: Form nuova nota -->
    <div 
        class="tab-pane fade <?= $activeTab === 'new-note' ? 'show active' : '' ?>" 
        id="new-note-panel" 
        role="tabpanel">
        
        <?= \App\View\View::getComponent('section-header', [
            'titolo' => 'Nuova nota',
            'p' => 'Pubblica una nuova nota'
        ]) ?>

        <?= \App\View\View::getComponent('newNotesForm', [
            'courses' => $courses
        ]) ?>
        
        
    </div>
</div>