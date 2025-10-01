@component(head)
@component(sidebar)

<div class="main-content" id="mainContent">
    <header class="app-header">
        <h1>
            <i class="fas fa-upload"></i>
            Publicar Material
        </h1>
        
        <div class="user-info">
            <span>¡Hola, <strong>{{ USER_NAME }}</strong>!</span>
        </div>
    </header>

    <main style="padding: 2rem;">
        <div style="max-width: 800px; margin: 0 auto;">
            
            <!-- Alertas -->
            {{ #if ERROR_PUBLICAR }}
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ ERROR_PUBLICAR }}
                </div>
            {{ /if }}

            {{ #if SUCCESS_PUBLICAR }}
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ SUCCESS_PUBLICAR }}
                </div>
            {{ /if }}

            <!-- Formulario de publicación -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: var(--color-acento-principal); margin-bottom: 25px;">
                    <i class="fas fa-recycle"></i>
                    Información del Material
                </h3>

                <form method="POST" action="?slug=publicar" enctype="multipart/form-data">
                    
                    <!-- Nombre del material -->
                    <div class="form-group">
                        <label for="nombre">
                            <i class="fas fa-tag"></i>
                            Nombre del Material *
                        </label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               placeholder="Ej: Plástico PET, Cartón Industrial, etc." required>
                    </div>

                    <!-- Tipo de material -->
                    <div class="form-group">
                        <label for="tipo_material_id">
                            <i class="fas fa-layer-group"></i>
                            Tipo de Material *
                        </label>
                        <select id="tipo_material_id" name="tipo_material_id" class="form-control" required>
                            <option value="0">Selecciona un tipo</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="form-group">
                        <label for="estado">
                            <i class="fas fa-info-circle"></i>
                            Estado del Material *
                        </label>
                        <select id="estado" name="estado" class="form-control" required>
                            <option value="">Selecciona el estado</option>
                            <option value="Nuevo">Nuevo</option>
                            <option value="Usado - Excelente">Usado - Excelente</option>
                            <option value="Usado - Bueno">Usado - Bueno</option>
                            <option value="Usado - Regular">Usado - Regular</option>
                        </select>
                    </div>

                    <!-- Cantidad y unidad -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="cantidad">
                                <i class="fas fa-weight"></i>
                                Cantidad *
                            </label>
                            <input type="number" id="cantidad" name="cantidad" class="form-control" 
                                   min="0" step="0.01" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="unidad_medida">
                                <i class="fas fa-ruler"></i>
                                Unidad de Medida *
                            </label>
                            <select id="unidad_medida" name="unidad_medida" class="form-control" required>
                                <option value="">Selecciona</option>
                                <option value="kg">Kilogramos (kg)</option>
                                <option value="ton">Toneladas (ton)</option>
                                <option value="m3">Metros cúbicos (m³)</option>
                                <option value="unidad">Unidades</option>
                                <option value="litros">Litros</option>
                            </select>
                        </div>
                    </div>

                    <!-- Precio -->
                    <div class="form-group">
                        <label for="precio">
                            <i class="fas fa-dollar-sign"></i>
                            Precio (opcional)
                        </label>
                        <input type="number" id="precio" name="precio" class="form-control" 
                               min="0" step="0.01" placeholder="0.00">
                        <small style="color: #666;">Deja en blanco si prefieres negociar</small>
                    </div>

                    <!-- Peligrosidad -->
                    <div class="form-group">
                        <label for="peligrosidad">
                            <i class="fas fa-exclamation-triangle"></i>
                            Nivel de Peligrosidad *
                        </label>
                        <select id="peligrosidad" name="peligrosidad" class="form-control" required>
                            <option value="">Selecciona el nivel</option>
                            <option value="No Peligroso">No Peligroso</option>
                            <option value="Baja">Baja</option>
                            <option value="Media">Media</option>
                            <option value="Alta">Alta</option>
                        </select>
                    </div>

                    <!-- Fábrica -->
                    <div class="form-group">
                        <label for="fabrica_id">
                            <i class="fas fa-industry"></i>
                            Fábrica / Origen
                        </label>
                        <select id="fabrica_id" name="fabrica_id" class="form-control">
                            <option value="1">No especificada</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="descripcion">
                            <i class="fas fa-align-left"></i>
                            Descripción *
                        </label>
                        <textarea id="descripcion" name="descripcion" class="form-control" 
                                  rows="4" placeholder="Describe el material, condiciones especiales, etc." 
                                  required style="resize: vertical;"></textarea>
                    </div>

                    <!-- Foto -->
                    <div class="form-group">
                        <label for="foto_material">
                            <i class="fas fa-image"></i>
                            Foto del Material
                        </label>
                        <input type="file" id="foto_material" name="foto_material" 
                               class="form-control" accept="image/*">
                        <small style="color: #666;">Formatos: JPG, PNG, GIF. Máximo 5MB</small>
                    </div>

                    <!-- Vista previa de imagen -->
                    <div id="previewContainer" style="display: none; margin-bottom: 20px;">
                        <label>Vista Previa:</label>
                        <div style="border: 2px dashed #ddd; border-radius: 8px; padding: 10px; text-align: center;">
                            <img id="imagePreview" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                        </div>
                    </div>

                    <!-- Recipiente -->
                    <input type="hidden" name="recipiente_id" value="1">

                    <!-- Botones -->
                    <div style="display: flex; gap: 15px; margin-top: 30px;">
                        <button type="submit" name="btn_publicar" class="btn btn-primary" 
                                style="flex: 1; padding: 15px; font-size: 16px;">
                            <i class="fas fa-upload"></i>
                            Publicar Material
                        </button>
                        
                        <a href="?slug=catalogo" class="btn btn-secondary" 
                           style="padding: 15px 30px; font-size: 16px;">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>

            <!-- Información adicional -->
            <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; margin-top: 20px; border-left: 4px solid var(--color-acento-principal);">
                <h4 style="color: var(--color-acento-principal); margin-bottom: 10px;">
                    <i class="fas fa-lightbulb"></i>
                    Consejos para publicar
                </h4>
                <ul style="color: #555; line-height: 1.8; margin-left: 20px;">
                    <li>Proporciona información clara y precisa del material</li>
                    <li>Incluye fotos de buena calidad para atraer más compradores</li>
                    <li>Indica correctamente el nivel de peligrosidad</li>
                    <li>Describe cualquier condición especial de almacenamiento</li>
                </ul>
            </div>

        </div>
    </main>
</div>

@component(footer)

<script>
// Cargar tipos de material desde el controlador
const tiposMaterial = {{{ TIPOS_MATERIAL }}};
const fabricas = {{{ FABRICAS }}};

document.addEventListener('DOMContentLoaded', function() {
    // Poblar select de tipos de material
    const selectTipos = document.getElementById('tipo_material_id');
    tiposMaterial.forEach(tipo => {
        const option = document.createElement('option');
        option.value = tipo.tipo_material_id;
        option.textContent = tipo.nombre;
        selectTipos.appendChild(option);
    });

    // Poblar select de fábricas
    const selectFabricas = document.getElementById('fabrica_id');
    fabricas.forEach(fabrica => {
        const option = document.createElement('option');
        option.value = fabrica.fabrica_id;
        option.textContent = fabrica.nombre;
        selectFabricas.appendChild(option);
    });

    // Vista previa de imagen
    document.getElementById('foto_material').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('previewContainer').style.display = 'none';
        }
    });
});
</script>

</body>
</html>