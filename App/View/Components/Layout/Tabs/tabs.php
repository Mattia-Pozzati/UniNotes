
<?= \App\View\View::getComponent('Layout/Tabs/tabsNav', compact([
    'tabs',
    'activeTab',
    'baseUrl'
])) ?>

<?= \App\View\View::getComponent('Layout/Tabs/tabsPanel', compact([
    'tabs',
    'activeTab',
    'baseUrl',
    'queryParams'
])) ?>