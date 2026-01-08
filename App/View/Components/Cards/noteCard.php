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

        <!-- Titolo e Chat -->
        <div class="d-flex align-items-center justify-content-between">   
            <h5 class="card-title"><?= htmlspecialchars($titolo) ?></h5>
            <?php if ($chatEnabled): ?>
                <a href="#" class="ms-2" aria-label="Apri chat AI">
                    <i class="bi bi-chat-left-dots" aria-hidden="true"></i>
                </a>
            <?php endif; ?>
        </div>

        <!-- Sottotitolo -->
        <h6 class="card-subtitle my-2 text-muted">
            <?= htmlspecialchars($autore) ?> &middot; <?= htmlspecialchars($corso) ?>
        </h6>

        <!-- Descrizione -->
        <p class="card-text"><?= htmlspecialchars($desc) ?></p>

        <!-- Tags -->
        <?php if (!empty($tags)): ?>
        <div class="d-flex justify-content-start gap-2 flex-wrap" aria-label="Tag della nota">
            <?php foreach ($tags as $tag): ?>
                <span class="btn btn-outline-secondary btn-sm" role="button" tabindex="0">
                    <?= htmlspecialchars($tag) ?>
                </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Likes & Downloads -->
        <div class="d-flex justify-content-start gap-3 mt-2" aria-label="Statistiche della nota">
            <div class="d-flex align-items-center">
                <span class="btn btn-sm" aria-label="<?= $likes ?> mi piace"><?= htmlspecialchars($likes) ?></span>
                <i class="bi bi-hand-thumbs-up fw-bold ms-1" aria-hidden="true"></i>
            </div>
            <div class="d-flex align-items-center">
                <span class="btn btn-sm" aria-label="<?= $downloads ?> download"><?= htmlspecialchars($downloads) ?></span>
                <i class="bi bi-download" aria-hidden="true"></i>
            </div>
        </div>

    </div>

    <!-- Footer con bottoni -->
    <?php if (!empty($buttonsEnabled) && !empty($buttons)): ?>
        <footer class="card-footer d-flex justify-content-center gap-2">
            <?php foreach ($buttons as $index => $btn): ?>
                <?php if ($buttonsEnabled[$index] ?? false): ?>
                    <a href="<?= htmlspecialchars($btn['link'] ?? '#') ?>"
                       class="btn <?= htmlspecialchars($btn['class'] ?? 'btn-primary') ?>"
                       role="button"
                       aria-label="<?= htmlspecialchars($btn['text'] ?? 'Button') ?>">
                        <?= htmlspecialchars($btn['text'] ?? 'Button') ?>
                        <?php if (!empty($btn['icon'])): ?>
                            <i class="bi <?= htmlspecialchars($btn["icon-class"] ?? '') ?> fw-bold ms-1" aria-hidden="true"></i>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </footer>
    <?php endif; ?>

</div>
