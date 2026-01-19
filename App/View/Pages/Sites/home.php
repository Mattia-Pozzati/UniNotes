<?php namespace App\View; ?>

<?php
$cards = $cards ?? [];
?>

<!-- Header sezione -->
<?= View::getComponent('Base/sectionHeader', [
    'title' => 'Benvenuto su UniNotes',
    'subtitle' => 'La piattaforma per condividere appunti universitari',
    'subtitleIcon' => 'bi-book',
    'p' => 'Esplora migliaia di appunti condivisi dalla community'
]) ?>

<!-- Barra di ricerca rapida -->
<section class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="/search" method="GET" class="d-flex gap-2">
                <input type="text" 
                       name="q" 
                       class="form-control" 
                       placeholder="Cerca appunti, corsi, argomenti..."
                       aria-label="Cerca note">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Cerca
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Note recenti -->
<section class="container py-4">
    <h2 class="mb-4">
        <i class="bi bi-clock-history me-2"></i>
        Note più recenti
    </h2>
    
    <?php if (empty($cards)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Nessuna nota disponibile al momento. 
            <a href="/login">Accedi</a> per caricare la prima nota!
        </div>
    <?php else: ?>
        <?= View::getComponent('Layout/Grid/cardGrid', [
            'cards' => $cards,
            'columnsTablet' => 2,
            'columnsDesktop' => 3,
            'component' => 'Cards/noteCard'
        ]) ?>
    <?php endif; ?>
</section>

<!-- Call to action -->
<section class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-upload" style="font-size: 3rem; color: var(--bs-primary);"></i>
                    <h5 class="card-title mt-3">Carica i tuoi appunti</h5>
                    <p class="card-text">Condividi le tue note con altri studenti</p>
                    <a href="/login" class="btn btn-primary">Inizia ora</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-search" style="font-size: 3rem; color: var(--bs-primary);"></i>
                    <h5 class="card-title mt-3">Trova appunti</h5>
                    <p class="card-text">Cerca tra migliaia di note condivise</p>
                    <a href="/search" class="btn btn-outline-primary">Esplora</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-chat-dots" style="font-size: 3rem; color: var(--bs-primary);"></i>
                    <h5 class="card-title mt-3">Chat con AI</h5>
                    <p class="card-text">Fai domande sulle note con l'intelligenza artificiale</p>
                    <a href="/search" class="btn btn-outline-primary">Prova</a>
                </div>
            </div>
        </div>
    </div>
</section>