<?php 

	/**
	 * Modelo Fabricas
	 * Maneja las fábricas registradas
	 */
	class Fabricas extends DBAbstract
	{
		public $fabrica_id, $nombre, $direccion, $telefono;
		
		function __construct()
		{
			parent::__construct();
		}

		/**
		 * Obtiene todas las fábricas
		 */
		public function obtenerTodas()
		{
			return $this->consultar("SELECT * FROM fabricas ORDER BY nombre");
		}

		/**
		 * Obtiene una fábrica por ID
		 */
		public function obtenerPorId($id)
		{
			$result = $this->consultar("SELECT * FROM fabricas WHERE fabrica_id = ?", [$id]);
			return count($result) > 0 ? $result[0] : null;
		}
	}
?>