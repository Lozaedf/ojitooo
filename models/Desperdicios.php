<?php 

	/**
	 * Modelo Desperdicios
	 * Maneja todas las operaciones relacionadas con desperdicios/residuos
	 */
	class Desperdicios extends DBAbstract
	{
		public $desperdicio_id, $nombre, $estado, $cantidad, $unidad_medida;
		public $precio, $peligrosidad, $descripcion, $activo;
		public $fabrica_id, $tipo_material_id, $recipiente_id, $foto_material;
		
		function __construct()
		{
			parent::__construct();
		}

		/**
		 * Obtiene todos los desperdicios activos con información relacionada
		 */
		public function obtenerTodos()
		{
			$sql = "SELECT d.*, tm.nombre as tipo_material_nombre, f.nombre as fabrica_nombre 
					FROM desperdicios d 
					LEFT JOIN tipo_material tm ON d.tipo_material_id = tm.tipo_material_id 
					LEFT JOIN fabricas f ON d.fabrica_id = f.fabrica_id 
					WHERE d.activo = 1 
					ORDER BY d.desperdicio_id DESC";
			
			return $this->consultar($sql);
		}

		/**
		 * Obtiene desperdicios filtrados por tipo
		 */
		public function obtenerPorTipo($tipo_id)
		{
			$sql = "SELECT d.*, tm.nombre as tipo_material_nombre, f.nombre as fabrica_nombre 
					FROM desperdicios d 
					LEFT JOIN tipo_material tm ON d.tipo_material_id = tm.tipo_material_id 
					LEFT JOIN fabricas f ON d.fabrica_id = f.fabrica_id 
					WHERE d.activo = 1 AND d.tipo_material_id = ?
					ORDER BY d.desperdicio_id DESC";
			
			return $this->consultar($sql, [$tipo_id]);
		}

		/**
		 * Obtiene un desperdicio específico por ID
		 */
		public function obtenerPorId($id)
		{
			$sql = "SELECT d.*, tm.nombre as tipo_material_nombre, f.nombre as fabrica_nombre 
					FROM desperdicios d 
					LEFT JOIN tipo_material tm ON d.tipo_material_id = tm.tipo_material_id 
					LEFT JOIN fabricas f ON d.fabrica_id = f.fabrica_id 
					WHERE d.desperdicio_id = ?";
			
			$result = $this->consultar($sql, [$id]);
			return count($result) > 0 ? $result[0] : null;
		}

		/**
		 * Publica un nuevo desperdicio
		 */
		public function publicar($datos, $archivo = null)
		{
			// Validaciones
			if (empty($datos['nombre']) || empty($datos['estado']) || 
				$datos['cantidad'] <= 0 || empty($datos['unidad_medida']) ||
				empty($datos['peligrosidad']) || empty($datos['descripcion']) ||
				$datos['tipo_material_id'] == 0) {
				return ["errno" => 400, "error" => "Campos requeridos faltantes"];
			}

			// Procesar imagen si existe
			$foto_material = null;
			if ($archivo && $archivo['error'] == UPLOAD_ERR_OK) {
				$resultado_upload = $this->procesarImagen($archivo);
				if ($resultado_upload['errno'] != 200) {
					return $resultado_upload;
				}
				$foto_material = $resultado_upload['filename'];
			}

			// Insertar en base de datos
			$sql = "INSERT INTO desperdicios (nombre, estado, cantidad, unidad_medida, precio, 
					peligrosidad, descripcion, activo, fabrica_id, tipo_material_id, 
					recipiente_id, foto_material) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$params = [
				$datos['nombre'],
				$datos['estado'],
				$datos['cantidad'],
				$datos['unidad_medida'],
				$datos['precio'] ?: null,
				$datos['peligrosidad'],
				$datos['descripcion'],
				1, // activo
				$datos['fabrica_id'] ?: 1,
				$datos['tipo_material_id'],
				$datos['recipiente_id'] ?: 1,
				$foto_material
			];

			$resultado = $this->ejecutar($sql, $params);

			return $resultado ? 
				["errno" => 201, "error" => "Desperdicio publicado exitosamente"] :
				["errno" => 500, "error" => "Error al publicar desperdicio"];
		}

		/**
		 * Procesa la imagen subida
		 */
		private function procesarImagen($archivo)
		{
			$upload_dir = 'uploads/';
			
			// Crear directorio si no existe
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}

			$file_info = pathinfo($archivo['name']);
			$extension = strtolower($file_info['extension']);

			// Validar extensión
			$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
			if (!in_array($extension, $allowed_extensions)) {
				return ["errno" => 400, "error" => "Tipo de archivo no permitido"];
			}

			// Validar tamaño (5MB máximo)
			if ($archivo['size'] > 5 * 1024 * 1024) {
				return ["errno" => 400, "error" => "Archivo demasiado grande"];
			}

			// Generar nombre único
			$filename = uniqid('img_') . '.' . $extension;
			$upload_path = $upload_dir . $filename;

			if (move_uploaded_file($archivo['tmp_name'], $upload_path)) {
				return ["errno" => 200, "filename" => $filename];
			}

			return ["errno" => 500, "error" => "Error al subir imagen"];
		}

		/**
		 * Obtiene estadísticas de desperdicios
		 */
		public function obtenerEstadisticas()
		{
			$stats = [];
			
			// Total de desperdicios activos
			$result = $this->consultar("SELECT COUNT(*) as total FROM desperdicios WHERE activo = 1");
			$stats['total_activos'] = $result[0]['total'];

			// Por tipo de material
			$result = $this->consultar("SELECT tm.nombre, COUNT(*) as total 
										FROM desperdicios d 
										JOIN tipo_material tm ON d.tipo_material_id = tm.tipo_material_id 
										WHERE d.activo = 1 
										GROUP BY tm.tipo_material_id");
			$stats['por_tipo'] = $result;

			return $stats;
		}
	}
?>