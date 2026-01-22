<?php namespace App\View; ?>

<?= View::getComponent('Base/sectionHeader', [
    'title' => 'Classifiche',
    'p' => 'I migliori contributori della community'
]) ?>

<section class="container py-5">
    <div class="row justify-content-center g-4">
        
        <!-- Card Classifiche -->
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    
                    <!-- Tabs Navigation -->
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <button class="btn btn-primary" id="uploaders-tab">
                            <i class="bi bi-upload me-2"></i>
                            <span class="d-none d-md-inline">Note Caricate</span>
                            <span class="d-md-none">Note</span>
                        </button>
                        <button class="btn btn-outline-secondary" id="liked-tab">
                            <i class="bi bi-hand-thumbs-up me-2"></i>
                            <span class="d-none d-md-inline">Like Ricevuti</span>
                            <span class="d-md-none">Like</span>
                        </button>
                    </div>

                    <!-- Classifiche Content -->
                    <div id="uploaders-content">
                        <?php if (empty($topUploaders)): ?>
                            <p class="text-center text-muted">Nessun dato disponibile</p>
                        <?php else: ?>
                            <div class="d-grid gap-3">
                                <?php foreach (array_slice($topUploaders, 0, 3) as $index => $user): ?>
                                    <?= View::getComponent('Cards/rankingCard', [
                                        'card' => $user,
                                        'index' => $index,
                                        'type' => 'note'
                                    ]) ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="liked-content" style="display: none;">
                        <?php if (empty($topLiked)): ?>
                            <p class="text-center text-muted">Nessun dato disponibile</p>
                        <?php else: ?>
                            <div class="d-grid gap-3">
                                <?php foreach (array_slice($topLiked, 0, 3) as $index => $user): ?>
                                    <?= View::getComponent('Cards/rankingCard', [
                                        'card' => $user,
                                        'index' => $index,
                                        'type' => 'like'
                                    ]) ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Come funzionano le classifiche -->
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-question-circle display-4"></i>
                    </div>
                    <h3 class="card-title mb-3">Come funzionano le classifiche</h3>
                    <p class="card-text text-muted">
                        Le classifiche vengono aggiornate in tempo reale in base all'attività della community. 
                        Carica più appunti e ricevi più like per scalare le posizioni e guadagnare badge esclusivi!
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
// Gestione tabs
document.getElementById('uploaders-tab').addEventListener('click', function() {
    document.getElementById('uploaders-content').style.display = 'block';
    document.getElementById('liked-content').style.display = 'none';
    
    this.classList.remove('btn-outline-secondary');
    this.classList.add('btn-primary');
    
    document.getElementById('liked-tab').classList.remove('btn-primary');
    document.getElementById('liked-tab').classList.add('btn-outline-secondary');
});

document.getElementById('liked-tab').addEventListener('click', function() {
    document.getElementById('uploaders-content').style.display = 'none';
    document.getElementById('liked-content').style.display = 'block';
    
    this.classList.remove('btn-outline-secondary');
    this.classList.add('btn-primary');
    
    document.getElementById('uploaders-tab').classList.remove('btn-primary');
    document.getElementById('uploaders-tab').classList.add('btn-outline-secondary');
});
</script>