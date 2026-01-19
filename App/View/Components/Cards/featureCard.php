<?php
$icon = $icon ?? 'bi-question-circle';
$title = $title ?? 'Titolo';
$description = $description ?? 'Descrizione';
$iconBg = $iconBg ?? 'bg-primary';
?>

<div class="card h-100 border-0 shadow-sm">
    <div class="card-body text-center p-4">
        <!-- Icona circolare -->
        <div class="<?= htmlspecialchars($iconBg) ?> rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" 
             style="width: 80px; height: 80px;">
            <i class="bi <?= htmlspecialchars($icon) ?> text-white fs-1"></i>
        </div>
        
        <h3 class="h4 fw-bold mb-3"><?= htmlspecialchars($title) ?></h3>
        <p class="text-muted"><?= htmlspecialchars($description) ?></p>
    </div>
</div>