<div class="tab-content mt-3">

    <?php foreach ($tabs as $key => $tab): ?>
        <?php if ($activeTab !== $key) continue; ?>

        <div class="tab-pane show active" role="tabpanel">

            <?= \App\View\View::getComponent('Base/sectionHeader', [
                'titolo' => $tab['label'],
                'p' => $tab['description'] ?? ''
            ]) ?>

            <?php if (!empty($tab['cards'])): ?>
                <?php
                    $cardGridQuery = is_array($queryParams) ? array_merge($queryParams, ['tab' => $key]) : ['tab' => $key];
                ?>
                <?= \App\View\View::getComponent('Layout/Grid/cardGrid', [
                    'cards' => $tab['cards'],
                    'component' => $tab['component'] ?? 'Cards/noteCard',
                    'currentPage' => $tab['pagination']['currentPage'] ?? null,
                    'totalPages' => $tab['pagination']['totalPages'] ?? null,
                    'baseUrl' => $baseUrl ?? '/user/dashboard',
                    'queryParams' => $cardGridQuery,
                    'pageParam' => $tab['pagination']['pageParam'] ?? 'page',
                ]) ?>
            <?php endif; ?>

            <?php if (!empty($tab['form'])): ?>
                <?= \App\View\View::getComponent($tab['form']) ?>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</div>
