<?php
$card = $card ?? [];

$titolo = $card['titolo'] ?? ($card['title'] ?? 'Titolo');
$autore = $card['autore'] ?? ($card['author'] ?? 'Autore');
$corso  = $card['corso'] ?? 'Corso';
$desc   = $card['desc'] ?? ($card['description'] ?? 'Descrizione non disponibile');
$chatEnabled = !empty($card['chatEnabled']);
$tags = $card['tags'] ?? [];
$likes = $card['likes'] ?? 0;
$downloads = $card['downloads'] ?? 0;
$buttonsEnabled = $card['buttonsEnabled'] ?? [];
$buttons = $card['buttons'] ?? [];

?>

<div class="card shadow border-primary h-100">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">   
            <h5 class="card-title"><?= htmlspecialchars($titolo) ?></h5>
            <?php if ($chatEnabled): ?>
                <a href="#" class="ms-2" aria-label="Chat Ai" role="link">
                    <i class="bi bi-chat-left-dots"></i>
                </a>
            <?php endif; ?>
        </div>

        <h6 class="card-subtitle my-2 text-muted">
            <?= htmlspecialchars($autore) ?> · <?= htmlspecialchars($corso) ?>
        </h6>

        <p class="card-text"><?= htmlspecialchars($desc) ?></p>

        <div class="d-flex justify-content-between gap-2 flex-wrap">
            <?php foreach ($tags as $tag): ?>
                <span class="btn btn-outline-secondary btn-sm"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach ?>
        </div>

        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <div>
                <span class="btn btn-sm"><?= htmlspecialchars($likes) ?></span>
                <i class="bi bi-hand-thumbs-up fw-bold ms-1"></i>
            </div>
            <div>
                <span class="btn btn-sm"><?= htmlspecialchars($downloads) ?></span>
                <i class="bi bi-download"></i>
            </div>
        </div>
    </div>

    <?php if (!empty($buttonsEnabled) && !empty($buttons)): ?>
        <footer class="card-footer d-flex justify-content-center gap-2">
            <?php foreach ($buttons as $index => $btn): ?>
                <?php if ($buttonsEnabled[$index] ?? false): ?>
                    <a href="<?= htmlspecialchars($btn['link'] ?? '#') ?>" class="btn <?= htmlspecialchars($btn['class'] ?? 'btn-primary') ?>">
                        <?= htmlspecialchars($btn['text'] ?? 'Button') ?>
                        <?php if (!empty($btn['icon'])): ?>
                            <i class="bi <?= htmlspecialchars($btn["icon-class"] ?? '') ?> fw-bold ms-1"></i>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </footer>
    <?php endif; ?>
</div>
