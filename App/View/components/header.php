<header class="p-5">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid p-0">
            <h5><a class="navbar-brand" href="#">Uninotes</a></h5>
            <!-- Navbar desktop -->
            <div class="collapse navbar-collapse d-none d-lg-flex justify-content-between align-items-center">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Chi siamo</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Servizi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contatti</a></li>
                </ul>
            </div>

            <div class="d-flex gap-1"> 
                <button class="btn w-100" id="themeToggle">
                    <i class="bi bi-moon "></i>
                </button>
                <button class="btn btn-primary" id="loginBtn">Login</button>
                <!-- Hamburger mobile -->
                <button class="btn d-lg-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Offcanvas mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title p-5">Uninotes</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex justify-content-center align-items-start p-5 h-100">
            <ul class="navbar-nav d-flex gap-5">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About-Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Reward</a></li>
            </ul>
        </div>
    </div>

</header>