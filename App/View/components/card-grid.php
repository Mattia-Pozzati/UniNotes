<?php
$cards = $cards ?? [];
$columnsMobile = $columnsMobile ?? 1;
$columnsTablet = $columnsTablet ?? 2;
$columnsDesktop = $columnsDesktop ?? 3;
$component = $component ?? "";

// Parametri paginazione (opzionali)
$currentPage = $currentPage ?? null;
$totalPages = $totalPages ?? null;
$baseUrl = $baseUrl ?? '/search';
$queryParams = $queryParams ?? [];
$pageParam = $pageParam ?? 'page';
$showPagination = $currentPage !== null && $totalPages !== null;
?>

<section class="container-fluid py-4">
    <div class="row g-3 g-md-5">
        <?php foreach ($cards as $card): ?>
            <div class="col-12 col-md-<?= 12 / $columnsTablet ?> col-lg-<?= 12 / $columnsDesktop ?>">
                <?= \App\View\View::getComponent($component, ["card" => $card]) ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($showPagination && $totalPages > 1): ?>
        <?php
        $buildUrl = function($page) use ($baseUrl, $queryParams, $pageParam) {
            $params = array_merge($queryParams, [$pageParam => $page]);
            return $baseUrl . '?' . http_build_query($params);
        };
        ?>
        <nav aria-label="Paginazione" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- Prima pagina -->
                <li class="btn <?= $currentPage == 1 ? 'disabled' : '' ?>">
                    <a href="<?= $buildUrl(1) ?>">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
                
                <!-- Pagina precedente -->
                <li class="btn <?= $currentPage == 1 ? 'disabled' : '' ?>">
                    <a href="<?= $buildUrl($currentPage - 1) ?>">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>

                <!-- Numeri delle pagine -->
                <?php
                $start = max(1, $currentPage - 2);
                $end = min($totalPages, $currentPage + 2);
                
                if ($start > 1): ?>
                    <li class="btn">
                        <a href="<?= $buildUrl(1) ?>">1</a>
                    </li>
                    <?php if ($start > 2): ?>
                        <li class="btn disabled">
                            <span>...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="btn <?= $i == $currentPage ? 'active' : '' ?>">
                        <a href="<?= $buildUrl($i) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?>
                        <li class="btn disabled">
                            <span>...</span>
                        </li>
                    <?php endif; ?>
                    <li class="btn">
                        <a href="<?= $buildUrl($totalPages) ?>"><?= $totalPages ?></a>
                    </li>
                <?php endif; ?>

                <!-- Pagina successiva -->
                <li class="btn <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                    <a href="<?= $buildUrl($currentPage + 1) ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
                
                <!-- Ultima pagina -->
                <li class="btn <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                    <a href="<?= $buildUrl($totalPages) ?>">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</section>