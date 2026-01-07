<?php
$title = $title ?? 'Titolo';
$subtitle = $subtitle ?? '';
$subtitleIcon = $subtitleIcon ?? '';
$p = $p ?? 'Descrizione';
?>

<header class="container text-center px-3">
    <h1 class="row p-5"> <?= htmlspecialchars($title); ?></h1>
    <?php if ($subtitle): ?>
        <h3 class="row p-3">
            <?= htmlspecialchars($subtitle); ?>
            <i class="bi <?= $subtitleIcon ?>"></i>
        </h3>
    <?php endif; ?>

    <p class="row"><?= htmlspecialchars($p); ?></p>
</header>