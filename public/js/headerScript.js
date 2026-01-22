    document.addEventListener('DOMContentLoaded', function () {
        const offcanvasElement = document.getElementById('mobileMenu');
        const navLinks = offcanvasElement.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                const href = this.getAttribute('href');

                if (href && href.includes('#')) {
                    e.preventDefault();

                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) {
                        offcanvas.hide();
                    }

                    const isExternalPage = href.startsWith('/#');

                    if (isExternalPage) {
                        offcanvasElement.addEventListener('hidden.bs.offcanvas', function () {
                            window.location.href = href;
                        }, { once: true });
                    } else {
                        offcanvasElement.addEventListener('hidden.bs.offcanvas', function () {
                            const targetId = href.substring(1);
                            const targetElement = document.getElementById(targetId);
                            if (targetElement) {
                                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        }, { once: true });
                    }
                } else {
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) {
                        offcanvas.hide();
                    }
                }
            });
        });
    });