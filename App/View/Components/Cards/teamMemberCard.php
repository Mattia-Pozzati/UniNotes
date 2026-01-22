<?php
$name = $name ?? 'Nome Cognome';
$github = $github ?? '#';
$linkedin = $linkedin ?? '#';
$email = $email ?? '#';
?>

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-4 text-center">
        <!-- Nome -->
        <h3 class="card-title fw-bold mb-4"><?= htmlspecialchars($name) ?></h3>
        
        <!-- Social Icons -->
        <div class="d-flex gap-2 justify-content-center">
            <?php if ($github !== '#'): ?>
                <a href="<?= htmlspecialchars($github) ?>" 
                   target="_blank" 
                   class="btn btn-outline-secondary btn-sm" 
                   aria-label="GitHub di <?= htmlspecialchars($name) ?>">
                    <i class="bi bi-github"></i>
                </a>
            <?php endif; ?>
            
            <?php if ($linkedin !== '#'): ?>
                <a href="<?= htmlspecialchars($linkedin) ?>" 
                   target="_blank" 
                   class="btn btn-outline-secondary btn-sm" 
                   aria-label="Linkedin di <?= htmlspecialchars($name) ?>">
                    <i class="bi bi-linkedin"></i>
                </a>
            <?php endif; ?>
            
            <?php if ($email !== '#'): ?>
                <a href="mailto:<?= htmlspecialchars($email) ?>" 
                   class="btn btn-outline-secondary btn-sm" 
                   aria-label="Email di <?= htmlspecialchars($name) ?>">
                    <i class="bi bi-envelope"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>