<?php
$tabs = $tabs ?? [];
$activeTab = $activeTab ?? (count($tabs) ? array_key_first($tabs) : null);
$baseUrl = $baseUrl ?? '/user/dashboard';
$queryParams = $queryParams ?? [];
?>

<nav>
    <ul class="nav nav-pills nav-justified" role="tablist" id="dashboardTabs">
        <?php foreach ($tabs as $key => $tab):
            $sanitizedKey = preg_replace('/[^A-Za-z0-9\-_]/', '-', (string) $key);
            $isActive = ($activeTab === $key);
            ?>
            <li class="nav-item" role="presentation">
                <a href="<?= htmlspecialchars($baseUrl . '?tab=' . urlencode((string) $key)) ?>"
                    id="tab-link-<?= htmlspecialchars($sanitizedKey) ?>" role="tab"
                    aria-controls="tab-<?= htmlspecialchars($sanitizedKey) ?>"
                    class="nav-link <?= $isActive ? 'active' : '' ?>" aria-selected="<?= $isActive ? 'true' : 'false' ?>"
                    tabindex="<?= $isActive ? '0' : '-1' ?>" aria-label="<?= htmlspecialchars($tab['label'] ?? '') ?>">
                    <i class="bi <?= htmlspecialchars($tab['icon'] ?? '') ?>" aria-hidden="true"></i>
                    <span class="d-none d-md-inline"><?= htmlspecialchars($tab['label'] ?? '') ?></span>
                    <?php if (!empty($tab['badge'])): ?>
                        <span class="badge bg-danger ms-1"><?= htmlspecialchars($tab['badge']) ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>