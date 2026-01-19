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
            <?= $getComponent('Cards/featureCard', [
                'icon' => 'bi-search',
                'iconBg' => 'bg-primary',
                'title' => 'Cerca Appunti',
                'description' => 'Trova rapidamente gli appunti di cui hai bisogno tra migliaia di note.'
            ]) ?>
        </div>

        <!-- Card 2: share -->
        <div class="col-12 col-md-6 col-lg-4">
            <?= $getComponent('Cards/featureCard', [
                'icon' => 'bi-share',
                'iconBg' => 'bg-primary',
                'title' => 'Condividi',
                'description' => 'Condividi i tuoi appunti con tutti.'
            ]) ?>
        </div>

        <!-- Card 3: Reward -->
        <div class="col-12 col-md-6 col-lg-4">
            <?= $getComponent('Cards/featureCard', [
                'icon' => 'bi-gift',
                'iconBg' => 'bg-primary',
                'title' => 'Reward',
                'description' => 'Pubblica le tue note per scalare la classifica e ottenere fantastici premi.'
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


<section class="aboutUs text-center fw-bold py-3 my-3">
    <h1>
        Chi Siamo?
    </h1>
</section>

<section class="mission py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <h2 class="h3 fw-bold mb-4 text-center">La nostra missione</h2>
                <p class="text-muted text-start">
                    UniNotes nasce con l'obiettivo di creare una community di studenti 
                    universitari che collaborano per il successo comune. 
                    Crediamo nella potenza della condivisione della conoscenza 
                    e nel supporto reciproco durante il percorso accademico.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="team py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <h2 class="h3 fw-bold mb-4 text-center">Il nostro team</h2>
                <p class="text-muted text-start">
                    UniNotes nasce con l'obiettivo di creare una community di studenti 
                    universitari che collaborano per il successo comune. 
                    Crediamo nella potenza della condivisione della conoscenza 
                    e nel supporto reciproco durante il percorso accademico.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="creator py-5">
    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <?= $getComponent('Cards/teamMemberCard', [
                'name' => 'Mattia Pozzati',
                'github' => 'https://github.com/Mattia-Pozzati',
                'linkedin' => '',
                'email' => 'mattia.pozzati3@studio.unibo.it',
            ]) ?>
        </div>
    
        <div class="col-12 col-md-6 col-lg-4">
            <?= $getComponent('Cards/teamMemberCard', [
                'name' => 'Simone Brunelli',
                'github' => 'https://github.com/Purp7ePi3',
                'linkedin' => '',
                'email' => 'simone.brunelli3@studio.unibo.it',
            ]) ?>
        </div>
    
        <div class="col-12 col-md-6 col-lg-4">
            <?= $getComponent('Cards/teamMemberCard', [
                'name' => 'Tommaso Nori',
                'github' => 'https://github.com/TommasoNori',
                'linkedin' => '',
                'email' => 'tommaso.nori@studio.unibo.it',
            ]) ?>
        </div>
    </div>
</section>
