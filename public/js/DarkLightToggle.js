 // Theme toggle usando data-bs-theme

const themeBtn = document.getElementById('themeToggle');
const themeBtnconfig = new Map([
    ['dark', '<i class="bi bi-sun"></i>'],
    ['light', '<i class="bi bi-moon"></i>'],
]);
if (themeBtn) {
    themeBtn.addEventListener('click', () => {
        const html = document.documentElement;
        let newTheme = html.getAttribute("data-bs-theme") === "dark" ?  "light" : "dark";
        html.setAttribute('data-bs-theme', newTheme)
        themeBtn.innerHTML = themeBtnconfig.get(newTheme);
        localStorage.setItem('theme', newTheme);    });
}

// Login demo
document.getElementById('loginBtn').addEventListener('click', () => alert('Login cliccato!')); 