<?php namespace App\View; 

use Core\Helper\SessionManager;

// Verifica se l'utente è loggato
$isLoggedIn = SessionManager::isLoggedIn();
$user = SessionManager::user();
$isAdmin = SessionManager::isAdmin();

// Dashboard URL in base al ruolo
$dashboardUrl = $isAdmin ? '/admin' : '/user/dashboard';
?>

<header class="row p-5">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light"
        aria-label="Barra di navigazione principale">

        <div class="container-fluid p-0">

            <a class="navbar-brand fw-bold" href="/">Uninotes</a>

            <!-- Link desktop -->
            <div 
                class="collapse 
                    navbar-collapse 
                    d-none 
                    d-lg-flex 
                    justify-content-between 
                    align-items-center">

                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/#index" aria-current="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#aboutUs">Chi siamo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Classiffica</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cerca</a>
                    </li>
                </ul>
            </div>

            <div class="d-flex gap-1 align-items-center">

                <button class="btn" id="themeToggle" aria-label="Toogle tema">
                    <i class="bi bi-moon " aria-hidden="true"></i>
                </button>

                <?php if ($isLoggedIn): ?>
                    <!-- Menu utente loggato - SOLO DESKTOP -->
                    <div class="dropdown d-none d-lg-block">
                        <button class="btn btn-primary dropdown-toggle" type="button" 
                                id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= htmlspecialchars($user['name'] ?? 'Utente') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li>
                                <a class="dropdown-item" href="<?= $dashboardUrl ?>">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="/logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Icona utente - SOLO MOBILE -->
                    <button class="btn btn-primary d-lg-none" 
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#mobileMenu"
                            aria-label="Menu utente"
                            >
                        <i class="bi bi-person-circle"></i>
                    </button>
                <?php else: ?>
                    <!-- Bottone login per utente non loggato -->
                    <button class="btn btn-primary d-none d-lg-block" id="loginBtn" onClick="window.location.href='/login'">
                        Login
                    </button>
                    
                    <!-- Icona login mobile -->
                    <button class="btn btn-primary d-lg-none" href='/login'>
                        <i class="bi bi-box-arrow-in-right"></i>
                    </button>
                <?php endif; ?>

                <!-- Hamburger mobile - SOLO se NON loggato -->
                <?php if (!$isLoggedIn): ?>
                    <button class="btn d-lg-none"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#mobileMenu"
                        aria-controls="mobileMenu"
                        aria-label="Apri menu"
                        >
                        <i class="bi bi-list" aria-hidden="true"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Offcanvas mobile -->
    <nav
        class="offcanvas offcanvas-start"
        tabindex="-1"
        id="mobileMenu"
        aria-label="mobileMunu">

        <div class="offcanvas-header">
            <h2 class="offcanvas-title p-5">Uninotes</h2>
            <button 
                type="button" 
                class="btn-close" data-bs-dismiss="offcanvas">
            </button>
        </div>
        <div class="offcanvas-body 
                    d-flex 
                    flex-column
                    justify-content-between
                    p-5 
                    h-100">

            <ul class="navbar-nav d-flex gap-5">
                <li class="nav-item">
                    <a class="nav-link" onClick="window.location.href='/'" aria-current="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#aboutUs">Chi siamo?</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Classiffica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Cerca</a>
                </li>
                
            </ul>

            <?php if ($isLoggedIn): ?>
                <!-- Sezione utente mobile -->
                <div class="border-top pt-3">
                    <p class="text-muted small mb-2">Loggato come</p>
                    <p class="fw-bold mb-3">
                        <?= htmlspecialchars($user['name'] ?? 'Utente') ?>
                        <?php if ($isAdmin): ?>
                            <span class="badge bg-danger ms-2">Admin</span>
                        <?php endif; ?>
                    </p>
                    <a href="<?= $dashboardUrl ?>" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-speedometer2 me-2"></i>
                        <?= $isAdmin ? 'Admin Dashboard' : 'Dashboard' ?>
                    </a>
                    <a href="/logout" class="btn btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

</header>