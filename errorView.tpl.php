@component(head)
@component(sidebar)

<div class="main-content" id="mainContent">
    <main style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
        <div style="text-align: center; max-width: 600px;">
            <div style="font-size: 8rem; color: var(--color-acento-principal); margin-bottom: 2rem; animation: bounce 1s infinite;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <h1 style="font-size: 3rem; color: var(--color-texto-principal); margin-bottom: 1rem;">
                {{ ERROR_TITLE }}
            </h1>
            
            <p style="font-size: 1.2rem; color: var(--color-texto-secundario); margin-bottom: 3rem;">
                {{ ERROR_MESSAGE }}
            </p>
            
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="?slug=catalogo" class="btn btn-primary" style="padding: 15px 30px; font-size: 16px;">
                    <i class="fas fa-home"></i>
                    Volver al Inicio
                </a>
                
                <button onclick="history.back()" class="btn btn-secondary" style="padding: 15px 30px; font-size: 16px;">
                    <i class="fas fa-arrow-left"></i>
                    Página Anterior
                </button>
            </div>
        </div>
    </main>
</div>

@component(footer)

<style>
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}
</style>

</body>
</html>