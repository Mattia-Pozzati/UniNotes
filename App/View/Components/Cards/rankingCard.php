<?php
$card = $card ?? [];
$index = $index ?? 0;
$type = $type ?? 'note'; // 'note' o 'like'

$colors = ['gold', 'silver', 'bronze'];
$colorCodes = [
    'gold' => '#ffd700',
    'silver' => '#c0c0c0',
    'bronze' => '#cd7f32'
];

$colorKey = $colors[$index] ?? 'silver';
$bgColor = $colorCodes[$colorKey];

$name = $card['name'] ?? 'Nome';
$count = $type === 'note' ? ($card['note_count'] ?? 0) : ($card['like_count'] ?? 0);
$label = $type === 'note' ? 'Note Caricate' : 'Like Ricevuti';
?>

<div class="card border-0" style="background-color: <?= $bgColor ?>;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center">
            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                 style="width: 40px; height: 40px; min-width: 40px;">
                <i class="bi bi-star-fill"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="mb-0 text-dark"><?= htmlspecialchars($name) ?></h2>
                <small class="text-dark"><?= $label ?>: <?= $count ?></small>
            </div>
        </div>
    </div>
</div>