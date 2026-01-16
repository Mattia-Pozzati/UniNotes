<?= \App\View\View::getComponent('Layout/Tabs/tabs', [
    'tabs' => $tabs,
    'activeTab' => $activeTab,
    'baseUrl' => '/user/dashboard',
    'queryParams' => $_GET
]) ?>
