<?php
$titolo = $titolo ?? 'Titolo';
$autore = $autore ?? 'Autore';
$corso  = $corso ?? 'Corso';
$desc   = $desc ?? 'Descrizione non disponibile';
$chatEnabled = $chatEnabled ?? false;
$tags = $tags ?? ["PDF", "Note", "Salva"];
$buttonsEnabled = $buttonsEnabled ?? [true, true];
$buttons = $buttons ?? [
    ["text" => "Primary", "icon" => true, "class" => "btn-primary", "link" => "#", "icon-class" => "bi-arrow-right"],
    ["text" => "Like", "icon" => true, "class" => "btn-outline-secondary", "link" => "#", "icon-class" => "bi-hand-thumbs-up"]
];
?>

<section class="card border-primary h-100"> <!-- Rimuovi mx-5, aggiungi h-100 per altezza uniforme -->
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-title">
                <?= htmlspecialchars($titolo) ?>
            </h5>

            <?php if ($chatEnabled): ?>
                <a href="#" class="ms-2" aria-label="Chat disponibile" role="link">
                    <i class="bi bi-chat-left-dots"></i>
                </a>
            <?php endif; ?>
        </div>

        <h6 class="card-subtitle my-2 text-muted">
            <?= htmlspecialchars($autore) ?> · <?= htmlspecialchars($corso) ?>
        </h6>

        <p class="card-text">
            <?= htmlspecialchars($desc) ?>
        </p>

        <div class="d-flex justify-content-between gap-2 flex-wrap">
            <?php foreach ($tags as $tag): ?>
                <button disabled class="btn btn-outline-secondary btn-sm"><?= htmlspecialchars($tag) ?></button>
            <?php endforeach ?>
        </div>
    </div>

    <?php if (!empty($buttonsEnabled) && !empty($buttons)): ?>
        <footer class="card-footer d-flex justify-content-center gap-2">
            <?php foreach ($buttons as $index => $btn): ?>
                <?php if ($buttonsEnabled[$index] ?? false): ?>
                    <a  href="<?= htmlspecialchars($btn['link'] ?? '#') ?>"
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
            <?php endforeach; ?>
        </footer>
    <?php endif; ?>
</section>
