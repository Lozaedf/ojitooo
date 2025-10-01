@component(head)
@component(sidebar)

<div class="main-content" id="mainContent">
    <header class="app-header">
        <h1>
            <i class="fas fa-shopping-cart"></i>
            Catálogo de Materiales
        </h1>
        
        <div class="user-info">
            {{ #if SESSION_LOGGED_IN }}
                <span>¡Hola, <strong>{{ SESSION_USERNAME }}</strong>!</span>
                <a href="?slug=publicar" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Publicar Material
                </a>
            {{ #else }}
                <a href="?slug=login" class="btn btn-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </a>
                <a href="?slug=register" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Registrarse
                </a>
            {{ /if }}
        </div>
    </header>

    <main style="padding: 2rem;">
        <!-- Filtros -->
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 15px; color: var(--color-acento-principal);">
                <i class="fas fa-filter"></i>
                Filtros
            </h3>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
                <div>
                    <label for="filtroTipo" style="margin-right: 8px; font-weight: 600;">Tipo:</label>
                    <select id="filtroTipo" class="form-control" style="width: auto; display: inline-block;" onchange="filtrarResiduos()">
                        <option value="">Todos los tipos</option>
                        <option value="Plástico">Plástico</option>
                        <option value="Papel/Cartón">Papel/Cartón</option>
                        <option value="Vidrio">Vidrio</option>
                        <option value="Metal">Metal</option>
                        <option value="Orgánico">Orgánico</option>
                        <option value="Electrónica">Electrónica</option>
                        <option value="Madera">Madera</option>
                        <option value="Textil">Textil</option>
                    </select>
                </div>
                
                <div>
                    <label for="filtroPeligrosidad" style="margin-right: 8px; font-weight: 600;">Peligrosidad:</label>
                    <select id="filtroPeligrosidad" class="form-control" style="width: auto; display: inline-block;" onchange="filtrarResiduos()">
                        <option value="">Todas</option>
                        <option value="No Peligroso">No Peligroso</option>
                        <option value="Baja">Baja</option>
                        <option value="Media">Media</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>
                
                <button onclick="limpiarFiltros()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Grid de residuos -->
        <div id="residuosGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <!-- Los residuos se cargarán dinámicamente -->
        </div>

        <!-- Mensaje sin residuos -->
        <div id="noResiduos" style="text-align: center; color: #666; font-size: 1.1rem; margin-top: 50px; display: none;">
            <i class="fas fa-recycle" style="font-size: 3em; margin-bottom: 20px; color: #ccc;"></i>
            <p>No hay residuos que coincidan con los filtros aplicados.</p>
        </div>
    </main>
</div>

@component(footer)

<script>
// Datos de residuos desde el controlador
const residuosData = {{{ LISTA_DESPERDICIOS }}};
let residuosFiltrados = residuosData;

// Renderizar residuos
function renderizarResiduos(residuos) {
    const grid = document.getElementById('residuosGrid');
    const noResiduos = document.getElementById('noResiduos');
    
    if (residuos.length === 0) {
        grid.innerHTML = '';
        noResiduos.style.display = 'block';
        return;
    }
    
    noResiduos.style.display = 'none';
    
    grid.innerHTML = residuos.map(residuo => `
        <div class="residuo-card" data-tipo="${residuo.tipo_material_nombre}" data-peligrosidad="${residuo.peligrosidad}">
            ${residuo.foto_material ? 
                `<img src="uploads/${residuo.foto_material}" alt="${residuo.nombre}" style="width: 100%; height: 200px; object-fit: cover;">` :
                `<div style="width: 100%; height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0;">
                    <i class="fas fa-image" style="font-size: 2em; color: #ccc;"></i>
                </div>`
            }
            
            <div style="padding: 20px;">
                <div style="background-color: var(--color-acento-principal); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; display: inline-block; margin-bottom: 10px;">
                    ${residuo.tipo_material_nombre}
                </div>
                
                <h3 style="font-size: 1.2em; font-weight: bold; color: #333; margin-bottom: 10px;">
                    ${residuo.nombre}
                </h3>
                
                <div style="margin-bottom: 8px; color: #666;">
                    <strong>Estado:</strong> ${residuo.estado}
                </div>
                
                <div style="margin-bottom: 8px; color: #666;">
                    <strong>Cantidad:</strong> ${residuo.cantidad} ${residuo.unidad_medida}
                </div>
                
                ${residuo.fabrica_nombre ? 
                    `<div style="margin-bottom: 8px; color: #666;">
                        <strong>Fábrica:</strong> ${residuo.fabrica_nombre}
                    </div>` : ''
                }
                
                ${residuo.precio ? 
                    `<div style="font-size: 1.1em; font-weight: bold; color: #28a745; margin: 10px 0;">
                        Precio: $${parseFloat(residuo.precio).toFixed(2)}
                    </div>` : ''
                }
                
                <div style="padding: 4px 8px; border-radius: 4px; font-size: 0.8em; display: inline-block; margin: 10px 0; 
                    ${residuo.peligrosidad === 'No Peligroso' ? 'background-color: #d4edda; color: #155724;' :
                      residuo.peligrosidad === 'Baja' ? 'background-color: #fff3cd; color: #856404;' :
                      residuo.peligrosidad === 'Media' ? 'background-color: #ffeaa7; color: #e17055;' :
                      'background-color: #fab1a0; color: #d63031;'}">
                    Peligrosidad: ${residuo.peligrosidad}
                </div>
                
                ${residuo.descripcion ? 
                    `<div style="color: #666; margin-top: 10px; line-height: 1.4;">
                        ${residuo.descripcion}
                    </div>` : ''
                }
                
                <button class="btn btn-primary" style="margin-top: 15px; width: 100%;" onclick="contactarVendedor(${residuo.desperdicio_id})">
                    <i class="fas fa-envelope"></i>
                    Contactar
                </button>
            </div>
        </div>
    `).join('');
}

// Función para filtrar residuos
function filtrarResiduos() {
    const filtroTipo = document.getElementById('filtroTipo').value;
    const filtroPeligrosidad = document.getElementById('filtroPeligrosidad').value;
    
    residuosFiltrados = residuosData.filter(residuo => {
        let mostrar = true;
        
        if (filtroTipo && residuo.tipo_material_nombre !== filtroTipo) {
            mostrar = false;
        }
        
        if (filtroPeligrosidad && residuo.peligrosidad !== filtroPeligrosidad) {
            mostrar = false;
        }
        
        return mostrar;
    });
    
    renderizarResiduos(residuosFiltrados);
}

// Limpiar filtros
function limpiarFiltros() {
    document.getElementById('filtroTipo').value = '';
    document.getElementById('filtroPeligrosidad').value = '';
    filtrarResiduos();
}

// Contactar vendedor
function contactarVendedor(residuoId) {
    alert(`Función de contacto para residuo ID: ${residuoId}\n\nEsta función se implementaría con un sistema de mensajería o email.`);
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    renderizarResiduos(residuosData);
    
    // Aplicar estilos a las tarjetas
    const style = document.createElement('style');
    style.textContent = `
        .residuo-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .residuo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
    `;
    document.head.appendChild(style);
});
</script>

</body>
</html>