<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Accedi</h2>
            
            <form action="/login" method="POST">

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        placeholder="esempio@uninotes.it"
                        required
                    >
                </div>

                <!-- Password con toggle -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password"
                            required
                        >
                        <button 
                            type="button" 
                            class="btn btn-outline-secondary" 
                            id="togglePassword"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    Accedi
                    <i class="bi bi-arrow-right ms-2"></i>
                </button>

                <!-- Link registrazione -->
                <div class="text-center">
                    <a href="/register" class="text-decoration-none">
                        Non ho un account
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
const pwd = document.getElementById('password');
const btn = document.getElementById('togglePassword');

btn.addEventListener('click', () => {
    const visible = pwd.type === 'text';
    pwd.type = visible ? 'password' : 'text';
    btn.innerHTML = visible 
        ? '<i class="bi bi-eye"></i>' 
        : '<i class="bi bi-eye-slash"></i>';
});
</script>
