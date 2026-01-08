<?php
$title = $title ?? 'Titolo';
$subtitle = $subtitle ?? '';
$subtitleIcon = $subtitleIcon ?? '';
$p = $p ?? '';
?>

<header class="container p-5">
  <div class="row">
    <div class="col text-center px-3">
      <h1><?= htmlspecialchars($title) ?></h1>
      <?php if ($subtitle): ?>
        <h3><i class="bi <?= htmlspecialchars($subtitleIcon) ?>"></i> <?= htmlspecialchars($subtitle) ?></h3>
      <?php endif; ?>
      <p><?= htmlspecialchars($p) ?></p>
    </div>
  </div>
</header>
</header>