<?php
$card = $card ?? [];

$id = $card['id'] ?? 0;
$title = $card['title'] ?? 'Titolo';
$author = $card['author'] ?? 'Autore';
$desc = $card['desc'] ?? 'Descrizione non disponibile';
$noteLink = $card['noteLink'] ?? null;
?>

<div class="card shadow border-primary h-100">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-title">
                <?= $title ?> <!-- title può contenere HTML (link) -->
            </h5>
        </div>

        <h6 class="card-subtitle my-2 text-muted">
            <?= htmlspecialchars($author) ?>
        </h6>

        <p class="card-text">
            <?= htmlspecialchars($desc) ?>
        </p>
    </div>

    <footer class="card-footer d-flex justify-content-center gap-2">
        <a href="/notification/<?= $id ?>/read" class="btn btn-outline-secondary">
            <i class="bi bi-check-circle me-1"></i>
            Letta
        </a>
    </footer>
</div>