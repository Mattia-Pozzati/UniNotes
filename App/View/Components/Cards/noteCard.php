<?php
$card = $card ?? [];

$titolo = $card['title'] ?? 'Titolo';
$autore = $card['author'] ?? 'Autore';
$corso = $card['course'] ?? 'Corso';
$desc = $card['desc'] ?? ($card['description'] ?? 'Descrizione non disponibile');
$chatEnabled = !empty($card['chatEnabled']);
$likes = $card['likes'];
$downloads = $card['downloads'];
$buttonsEnabled = $card['buttonsEnabled'] ?? [];
$buttons = $card['buttons'] ?? [];
$visibility = $card['visibility'] ??'public';
// Metadati della nota (mostrati al posto dei tag)
$format = $card['format'] ?? null;
$university = $card['university'] ?? null;
$note_type = $card['note_type'] ?? ($card['type'] ?? null);
?>

<div class="card shadow border-primary h-100">
    <div class="card-body">

        <!-- Titolo e Chat -->
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-title"><?= htmlspecialchars($titolo) ?></h5>
            <?php if ($visibility === 'private'): ?>
                <p> Private </p>
            <?php endif; ?>
        </div>

        <!-- Sottotitolo -->
        <h6 class="card-subtitle my-2 text-muted">
            <?= htmlspecialchars($autore) ?> &middot; <?= htmlspecialchars($corso) ?>
        </h6>

        <!-- Descrizione -->
        <p class="card-text"><?= htmlspecialchars($desc) ?></p>

        <!-- Metadati: formato, università, tipo di nota -->
        <div class="d-flex justify-content-start gap-2 flex-wrap" aria-label="Metadati della nota">
            <?php if (!empty($format)): ?>
                <span class="badge badge-contrast bg-light text-dark border"><?= htmlspecialchars($format) ?></span>
            <?php endif; ?>

            <?php if (!empty($university)): ?>
                <span class="badge badge-contrast bg-light text-dark border"><?= htmlspecialchars($university) ?></span>
            <?php endif; ?>

            <?php if (!empty($note_type)): ?>
                <span class="badge badge-contrast bg-light text-dark border"><?= htmlspecialchars($note_type) ?></span>
            <?php endif; ?>
        </div>

        <!-- Likes & Downloads -->
        <div class="d-flex justify-content-start gap-3 mt-2" aria-label="Statistiche della nota">
            <div class="d-flex align-items-center">
                <span class="btn btn-sm" aria-label="<?= $likes ?> mi piace"><?= htmlspecialchars($likes) ?></span>
                <i class="bi bi-hand-thumbs-up fw-bold ms-1" aria-hidden="true"></i>
            </div>
            <div class="d-flex align-items-center">
                <span class="btn btn-sm"
                    aria-label="<?= $downloads ?> download"><?= htmlspecialchars($downloads) ?></span>
                <i class="bi bi-download" aria-hidden="true"></i>
            </div>
        </div>

    </div>

    <!-- Footer con bottoni -->
    <?php if (!empty($buttonsEnabled) && !empty($buttons)): ?>
        <footer class="card-footer d-flex justify-content-center gap-2">
            <?php foreach ($buttons as $index => $btn): ?>
                <?php if ($buttonsEnabled[$index] ?? false): ?>
                    <?php if (!empty($btn['method']) && strtolower($btn['method']) === 'post'): ?>
                        <form method="POST" action="<?= htmlspecialchars($btn['link'] ?? '#') ?>" class="d-inline">
                            <button type="submit" class="btn <?= htmlspecialchars($btn['class'] ?? 'btn-primary') ?>"
                                aria-label="<?= trim($btn['text'] ?? '') !== '' ? htmlspecialchars($btn['text']) : htmlspecialchars($btn['icon-class'] ?? 'Azione') ?>">
                                <?php if (trim($btn['text'] ?? '') !== ''): ?>
                                    <?= htmlspecialchars($btn['text']) ?>
                                <?php endif ?>
                                <?php if (!empty($btn['icon'])): ?>
                                    <i class="bi <?= htmlspecialchars($btn["icon-class"]) ?> fw-bold ms-1" aria-hidden="true"></i>
                                <?php endif ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($btn['link'] ?? '#') ?>"
                            class="btn <?= htmlspecialchars($btn['class'] ?? 'btn-primary') ?>"
                            aria-label="<?= trim($btn['text'] ?? '') !== '' ? htmlspecialchars($btn['text']) : htmlspecialchars($btn['icon-class'] ?? 'Azione') ?>">

                            <?php if (trim($btn['text'] ?? '') !== ''): ?>
                                <?= htmlspecialchars($btn['text']) ?>
                            <?php endif ?>

                            <?php if (!empty($btn['icon'])): ?>
                                <i class="bi <?= htmlspecialchars($btn["icon-class"]) ?> fw-bold ms-1" aria-hidden="true"></i>
                            <?php endif ?>
                        </a>
                    <?php endif; ?>

                <?php endif; ?>
            <?php endforeach; ?>
        </footer>
    <?php endif; ?>

</div>