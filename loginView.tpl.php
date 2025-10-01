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
                <h2 style="color: var(--color-texto-principal); font-weight: 400;">Iniciar Sesión</h2>
                <p style="color: var(--color-texto-secundario);">Accede a tu cuenta para continuar</p>
            </div>

            <!-- Alertas -->
            <div id="alertContainer"></div>
            
            {{ #if ERROR_LOGIN }}
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ ERROR_LOGIN }}
                </div>
            {{ /if }}

            {{ #if SUCCESS_LOGIN }}
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ SUCCESS_LOGIN }}
                </div>
            {{ /if }}

            <form id="loginForm" method="POST" action="?slug=login">
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
                           placeholder="Tu contraseña" required>
                </div>

                <button type="submit" name="btn_ingresar" class="btn btn-primary" 
                        style="width: 100%; padding: 15px; font-size: 16px; margin-bottom: 1rem;">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eee;">
                <p style="color: var(--color-texto-secundario); margin-bottom: 1rem;">
                    ¿No tienes una cuenta?
                </p>
                <a href="?slug=register" class="btn btn-secondary" style="width: 100%; padding: 12px;">
                    <i class="fas fa-user-plus"></i>
                    Crear cuenta nueva
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
    const form = document.getElementById('loginForm');
    const alertContainer = document.getElementById('alertContainer');
    
    // Manejo del formulario con AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const data = {
            email: formData.get('email'),
            password: formData.get('password')
        };
        
        // Mostrar estado de carga
        const submitBtn = form.querySelector