<?php
$titolo = $titolo ?? 'Titolo';
$p = $p ?? 'Descrizione';
?>

<header class="container-fluid px-3">
    <div class="d-flex container-fluid justify-content-center pb-5">
        <h1 class="row"><?= htmlspecialchars($titolo); ?></h1>
    </div>
    <p class="row"><?= htmlspecialchars($p); ?></p>
</header>