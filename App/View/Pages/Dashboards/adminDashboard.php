<?= \App\View\View::getComponent('Base/sectionHeader', [
    'title' => 'Pannello di amministrazione',
    'subtitle' => 'Gestisci utenti e contenuti del sito',
    
]) ?>


<?= \App\View\View::getComponent('Layout/Tabs/tabs', compact([
    'tabs',
    'activeTab',
    'baseUrl',
    'queryParams'
])) ?>