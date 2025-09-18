<!-- Botón Toggle -->
<button class="toggle-btn" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay para móviles -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-recycle"></i>
            {{ APP_NAME }}
        </div>
    </div>
    
    <nav class="sidebar-menu">
        <a href="?slug=catalogo" class="menu-item" id="menu-catalogo">
            <i class="fas fa-shopping-cart"></i>
            Catálogo
        </a>
        
        <a href="?slug=foro" class="menu-item" id="menu-foro">
            <i class="fas fa-comments"></i>
            Foro
        </a>
        
        <!-- Menús para usuarios autenticados -->
        <div class="auth-menu" style="display: {{ SESSION_LOGGED_IN }};">
            <a href="?slug=publicar" class="menu-item" id="menu-publicar">
                <i class="fas fa-upload"></i>
                Publicar
            </a>
            
            <a href="?slug=perfil" class="menu-item" id="menu-perfil">
                <i class="fas fa-user"></i>
                Mi Perfil
            </a>
            
            <a href="#" class="menu-item" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </div>
        
        <!-- Menús para invitados -->
        <div class="guest-menu" style="display: {{ SESSION_LOGGED_IN }};">
            <a href="?slug=login" class="menu-item" id="menu-login">
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </a>
            
            <a href="?slug=register" class="menu-item" id="menu-register">
                <i class="fas fa-user-plus"></i>
                Registrarse
            </a>
        </div>
    </nav>
</div>

<script>
// Funcionalidad de la sidebar
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');
    
    // Mostrar/ocultar menús según estado de login
    const isLoggedIn = '{{ SESSION_LOGGED_IN }}' === 'true';
    const authMenus = document.querySelectorAll('.auth-menu');
    const guestMenus = document.querySelectorAll('.guest-menu');
    
    if (isLoggedIn) {
        authMenus.forEach(menu => menu.style.display = 'block');
        guestMenus.forEach(menu => menu.style.display = 'none');
    } else {
        authMenus.forEach(menu => menu.style.display = 'none');
        guestMenus.forEach(menu => menu.style.display = 'block');
    }
    
    // Toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('active');
        
        if (window.innerWidth > 768) {
            mainContent.classList.toggle('shifted');
        }
    });
    
    // Cerrar sidebar al hacer click en overlay
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('active');
        mainContent.classList.remove('shifted');
    });
    
    // Marcar menú activo
    const currentSlug = new URLSearchParams(window.location.search).get('slug') || 'catalogo';
    const activeMenuItem = document.getElementById('menu-' + currentSlug);
    if (activeMenuItem) {
        activeMenuItem.classList.add('active');
    }
});

// Función de logout
function logout() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        fetch('?slug=logout', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error al cerrar sesión');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = '?slug=catalogo';
        });
    }
}
</script>