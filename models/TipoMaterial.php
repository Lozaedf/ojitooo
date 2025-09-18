<?php 

	/**
	 * Modelo TipoMaterial
	 * Maneja los tipos de materiales disponibles
	 */
	class TipoMaterial extends DBAbstract
	{
		public $tipo_material_id, $nombre, $descripcion;
		
		function __construct()
		{
			parent::__construct();
		}

		/**
		 * Obtiene todos los tipos de material
		 */
		public function obtenerTodos()
		{
			return $this->consultar("SELECT * FROM tipo_material ORDER BY nombre");
		}

		/**
		 * Obtiene un tipo de material por ID
		 */
		public function obtenerPorId($id)
		{
			$result = $this->consultar("SELECT * FROM tipo_material WHERE tipo_material_id = ?", [$id]);
			return count($result) > 0 ? $result[0] : null;
		}
	}
?>