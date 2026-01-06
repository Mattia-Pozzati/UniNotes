 // Theme toggle usando data-bs-theme
const themeBtn = document.getElementById('themeToggle');
if (themeBtn) {
    themeBtn.addEventListener('click', () => {
        const html = document.documentElement;
        if (html.getAttribute('data-bs-theme') === 'light') {
            html.setAttribute('data-bs-theme', 'dark');
            themeBtn.innerHTML = '<i class="bi bi-sun"></i>';
        } else {
            html.setAttribute('data-bs-theme', 'light');
            themeBtn.innerHTML = '<i class="bi bi-moon"></i>';
        }
    });
}

// Login demo
document.getElementById('loginBtn').addEventListener('click', () => alert('Login cliccato!')); 