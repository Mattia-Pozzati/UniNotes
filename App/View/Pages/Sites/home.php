<?php namespace App\View; ?>

<!-- Hero Section -->
<section class="hero text-center py-5 my-5">
    <h1 class="display-3 fw-bold mb-4">Prova UniNotes!</h1>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
        <button class="btn btn-primary btn-lg px-5">
            Inizia Ora 
            <i class="bi bi-arrow-right ms-2"></i>
        </button>
        <button class="btn btn-outline-secondary btn-lg px-5">
            Scopri di più
        </button>
    </div>
</section>

<!-- Come Funziona Section -->
<section class="how-it-works py-5 my-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3">Come Funziona</h2>
        <p class="lead text-muted">Tre semplici passi per iniziare a condividere e scoprire appunti</p>
    </div>
    
    <div class="row g-4">
        <!-- Card 1: Cerca Appunti -->
        <div class="col-12 col-md-6 col-lg-4">
            <?= \App\View\View::getComponent('Cards/featureCard', [
                'icon' => 'bi-search',
                'iconBg' => 'bg-primary',
                'title' => 'Cerca Appunti',
                'description' => 'Trova rapidamente gli appunti di cui hai bisogno tra migliaia di risorse'
            ]) ?>
        </div>

        <!-- Card 2: Condividi -->
        <div class="col-12 col-md-6 col-lg-4">
            <?= \App\View\View::getComponent('Cards/featureCard', [
                'icon' => 'bi-share',
                'iconBg' => 'bg-success',
                'title' => 'Condividi',
                'description' => 'Carica i tuoi appunti e aiuta altri studenti'
            ]) ?>
        </div>

        <!-- Card 3: Reward -->
        <div class="col-12 col-md-6 col-lg-4">
            <?= \App\View\View::getComponent('Cards/featureCard', [
                'icon' => 'bi-gift',
                'iconBg' => 'bg-warning',
                'title' => 'Reward',
                'description' => 'Guadagna reputazione condividendo contenuti di qualità'
            ]) ?>
        </div>
    </div>
</section>

<!-- Sezione CTA finale -->
<section class="cta-section text-center py-5 my-5">
    <button class="btn btn-primary btn-lg px-5">
        Pronto ad Iniziare
    </button>
    <div class="mt-4">
        <a href="#" class="btn btn-outline-secondary">
            Registrati gratuitamente
        </a>
    </div>
</section>