<?php
// $notes deve essere un array di array associativi con i dati delle note
$notes = $notes ?? [];
$columnsMobile = $columnsMobile ?? 1;  // Colonne su mobile (default 1)
$columnsTablet = $columnsTablet ?? 2; // Colonne su tablet (default 2)
$columnsDesktop = $columnsDesktop ?? 3; // Colonne su desktop (default 3)
?>

<section class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    <div class="row g-3 g-md-4">
        <?php foreach ($notes as $note): ?>
            <div class="col-12 col-md-<?= 12 / $columnsTablet ?> col-lg-<?= 12 / $columnsDesktop ?>">
                <?= \App\View\View::getComponent('note-card', [
                    'titolo' => $note['titolo'] ?? 'Titolo',
                    'autore' => $note['autore'] ?? 'Autore',
                    'corso' => $note['corso'] ?? 'Corso',
                    'desc' => $note['desc'] ?? 'Descrizione non disponibile',
                    'chatEnabled' => $note['chatEnabled'] ?? false,
                    'tags' => $note['tags'] ?? [],
                    'buttonsEnabled' => $note['buttonsEnabled'] ?? [true, true],
                    'buttons' => $note['buttons'] ?? [
                        ["text" => "Visualizza", "icon" => true, "class" => "btn-primary", "link" => "#", "icon-class" => "bi-arrow-right"],
                        ["text" => "Like", "icon" => true, "class" => "btn-outline-secondary", "link" => "#", "icon-class" => "bi-hand-thumbs-up"]
                    ]
                ]) ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>