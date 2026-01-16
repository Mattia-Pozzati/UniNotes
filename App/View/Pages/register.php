
<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Registrati</h2>
            
            <form action="/register" method="POST">
                <!-- Nome -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nome completo</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="name" 
                        name="name" 
                        placeholder="Mario Rossi"
                        required
                    >
                </div>

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

                <!-- Password -->
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

              <div class="mb-3">
                  <label for="confirmPassword" class="form-label">Conferma Password</label>
                  <div class="input-group">
                      <input 
                          type="password" 
                          class="form-control" 
                          id="confirmPassword"
                          name="confirmPassword"
                          required
                      >
                      <button 
                          type="button" 
                          class="btn btn-outline-secondary" 
                          id="toggleConfirmPassword"
                      >
                          <i class="bi bi-eye"></i>
                      </button>
                  </div>
              </div>


                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    Registrati
                    <i class="bi bi-arrow-right ms-2"></i>
                </button>

                <!-- Link login -->
                <div class="text-center">
                    <a href="/login" class="text-decoration-none">
                        Ho già un account
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
function bindToggle(inputId, btnId) {
    const input = document.getElementById(inputId);
    const btn = document.getElementById(btnId);

    btn.addEventListener('click', () => {
        const visible = input.type === 'text';
        input.type = visible ? 'password' : 'text';
        btn.innerHTML = visible
            ? '<i class="bi bi-eye"></i>'
            : '<i class="bi bi-eye-slash"></i>';
    });
}

bindToggle('password', 'togglePassword');
bindToggle('confirmPassword', 'toggleConfirmPassword');
</script>

