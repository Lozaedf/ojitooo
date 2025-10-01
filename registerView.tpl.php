@component(head)
@component(sidebar)

<div class="main-content" id="mainContent">
    <main style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
        <div class="form-container" style="max-width: 450px;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="font-size: 3rem; color: var(--color-acento-principal); margin-bottom: 1rem;">
                    <i class="fas fa-recycle"></i>
                </div>
                <h1 style="color: var(--color-acento-principal); margin-bottom: 0.5rem;">{{ APP_NAME }}</h1>
                <h2 style="color: var(--color-texto-principal); font-weight: 400;">Crear Cuenta</h2>
                <p style="color: var(--color-texto-secundario);">Únete a nuestra plataforma de reciclaje</p>
            </div>

            <!-- Alertas -->
            {{ #if ERROR_REGISTER }}
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ ERROR_REGISTER }}
                </div>
            {{ /if }}

            {{ #if SUCCESS_REGISTER }}
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ SUCCESS_REGISTER }}
                </div>
            {{ /if }}

            <form method="POST" action="?slug=register">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        Nombre de Usuario
                    </label>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Tu nombre de usuario" required 
                           pattern="[a-zA-Z0-9_]+" 
                           title="Solo letras, números y guión bajo">
                    <small style="color: #666;">Solo letras, números y guión bajo (_)</small>
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Correo Electrónico
                    </label>
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="tu@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Contraseña
                    </label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Mínimo 6 caracteres" required minlength="6">
                    <small style="color: #666;">Mínimo 6 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i>
                        Confirmar Contraseña
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                           placeholder="Repite tu contraseña" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" required>
                        <span style="font-size: 0.9rem; color: var(--color-texto-secundario);">
                            Acepto los términos y condiciones
                        </span>
                    </label>
                </div>

                <button type="submit" name="btn_registrar" class="btn btn-primary" 
                        style="width: 100%; padding: 15px; font-size: 16px; margin-bottom: 1rem;">
                    <i class="fas fa-user-plus"></i>
                    Crear Cuenta
                </button>
            </form>

            <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eee;">
                <p style="color: var(--color-texto-secundario); margin-bottom: 1rem;">
                    ¿Ya tienes una cuenta?
                </p>
                <a href="?slug=login" class="btn btn-secondary" style="width: 100%; padding: 12px;">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </a>
            </div>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="?slug=catalogo" style="color: var(--color-acento-principal); text-decoration: none;">
                    <i class="fas fa-arrow-left"></i>
                    Volver al catálogo
                </a>
            </div>
        </div>
    </main>
</div>

@component(footer)

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    // Validar que las contraseñas coincidan
    form.addEventListener('submit', function(e) {
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            confirmPassword.focus();
            return false;
        }
    });

    // Indicador visual de coincidencia de contraseñas
    confirmPassword.addEventListener('input', function() {
        if (this.value && password.value !== this.value) {
            this.style.borderColor = '#e74c3c';
        } else {
            this.style.borderColor = '';
        }
    });
});
</script>

</body>
</html>