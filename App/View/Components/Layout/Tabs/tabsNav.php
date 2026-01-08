<?php
$tabs = $tabs ?? [];
$activeTab = $activeTab ?? (count($tabs) ? array_key_first($tabs) : null);
$baseUrl = $baseUrl ?? '/user/dashboard';
$queryParams = $queryParams ?? [];
?>

<nav>
    <ul class="nav nav-pills nav-justified" role="tablist" id="dashboardTabs">
        <?php foreach ($tabs as $key => $tab): ?>
            <li class="nav-item">
                <a
                    href="<?= $baseUrl ?>?tab=<?= $key ?>"
                    class="nav-link <?= $activeTab === $key ? 'active' : '' ?>"
                    role="tab"
                    aria-selected="<?= $activeTab === $key ? 'true' : 'false' ?>">

                    <i class="bi <?= $tab['icon'] ?>" aria-hidden="true"></i>
                    <span class="d-none d-md-inline"><?= $tab['label'] ?></span>

                    <?php if (!empty($tab['badge'])): ?>
                        <span class="badge bg-danger ms-1"><?= $tab['badge'] ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
