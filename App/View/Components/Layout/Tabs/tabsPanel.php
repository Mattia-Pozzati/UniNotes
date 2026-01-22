<?php 
namespace App\View\Components\Layout\Tabs; 
use App\View\View;
?>

<div class="tab-content mt-3">

    <?php foreach ($tabs as $key => $tab): ?>
        <?php if ($activeTab !== $key) continue; ?>

        <div class="tab-pane show active" role="tabpanel">

            <?= View::getComponent('Base/sectionHeader', [
                'titolo' => $tab['label'],
                'p' => $tab['description'] ?? ''
            ]) ?>

            <?php if (!empty($tab['form'])): ?>
                <?= View::getComponent($tab['form'], [
                    'courses' => $tab['courses'] ?? [],
                    'action' => $tab['action'] ?? ''
                ]) ?>
            <?php endif; ?>

            <?php if (!empty($tab['cards'])): ?>
                <?php
                    $cardGridQuery = is_array($queryParams) ? array_merge($queryParams, ['tab' => $key]) : ['tab' => $key];
                ?>
                <?= View::getComponent('Layout/Grid/cardGrid', [
                    'cards' => $tab['cards'],
                    'component' => $tab['component'] ?? 'Cards/noteCard',
                    'currentPage' => $tab['pagination']['currentPage'] ?? null,
                    'totalPages' => $tab['pagination']['totalPages'] ?? null,
                    'baseUrl' => $baseUrl ?? '/user/dashboard',
                    'queryParams' => $cardGridQuery,
                    'pageParam' => $tab['pagination']['pageParam'] ?? 'page',
                ]) ?>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</div>
