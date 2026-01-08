const themeBtn = document.getElementById('themeToggle');
const html = document.documentElement;

function applyTheme(theme) {
    html.setAttribute('data-bs-theme', theme);
    if (theme === 'dark') {
        themeBtn.innerHTML = '<i class="bi bi-sun"></i>';
    } else {
        themeBtn.innerHTML = '<i class="bi bi-moon"></i>';
    }
}

const savedTheme = localStorage.getItem('theme') || 'light';
applyTheme(savedTheme);

if (themeBtn) {
    themeBtn.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        applyTheme(newTheme);
        localStorage.setItem('theme', newTheme);
    });
}

// if (themeBtn) {
//     themeBtn.addEventListener('click', () => {
//         const html = document.documentElement;
//         if (html.getAttribute('data-bs-theme') === 'light') {
//             html.setAttribute('data-bs-theme', 'dark');
//             themeBtn.innerHTML = '<i class="bi bi-sun"></i>';
//         } else {
//             html.setAttribute('data-bs-theme', 'light');
//             themeBtn.innerHTML = '<i class="bi bi-moon"></i>';
//         }
//     });
// }

// // Login demo
// document.getElementById('loginBtn').addEventListener('click', () => alert('Login cliccato!')); 
