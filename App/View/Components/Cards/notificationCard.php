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

        <!-- Titolo -->
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-title">
                <?= $title ?> <!-- se il titolo contiene HTML, assicurati che sia sicuro -->
            </h5>
        </div>

        <!-- Autore -->
        <h6 class="card-subtitle my-2 text-muted">
            <?= htmlspecialchars($author) ?>
        </h6>

        <!-- Descrizione -->
        <p class="card-text">
            <?= htmlspecialchars($desc) ?>
        </p>
    </div>

    <!-- Footer con bottone "Letta" (usa POST) -->
    <footer class="card-footer d-flex justify-content-center gap-2">
        <form class="m-0"method="POST" action="/notification/<?= $id ?>/read">
            <button type="submit" class="btn btn-outline-secondary" aria-label="Segna notifica come letta">
                <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                Letta
            </button>
        </form>
    </footer>
</div>
