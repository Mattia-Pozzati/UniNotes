<?php namespace App\View; ?>


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
                        <a class="nav-link" href="/index" aria-current="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Chi siamo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Servizi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contatti</a>
                    </li>
                </ul>
            </div>

            <div class="d-flex gap-1">

                <button class="btn" id="themeToggle" aria-label="Toogle tema">
                    <i class="bi bi-moon " aria-hidden="true"></i>
                </button>

                <button class="btn btn-primary" id="loginBtn" onClick="window.location.href='/login'">
                    Login
                </button>

                <!-- Hamburger mobile -->
                <button class="btn d-lg-none"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu"
                    aria-controls="mobileMenu"
                    aria-label="Apri menu"
                    >
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>
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
                class="btn-close" 
                data-bs-dismiss="offcanvas">
            </button>
        </div>
        <div class="offcanvas-body 
                    d-flex 
                    justify-content-center 
                    align-items-start 
                    p-5 
                    h-100">

            <ul class="navbar-nav d-flex gap-5">
                <li class="nav-item">
                    <a class="nav-link" onClick="window.location.href='/'" aria-current="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About-Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Reward</a>
                </li>
            </ul>
        </div>
    </nav>

</header>
