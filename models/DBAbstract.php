<?php 

	/**
	 * Clase abstracta para manejo de base de datos
	 */
	class DBAbstract
	{
		private $db;
		
		function __construct()
		{
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($this->db->connect_error) {
				die("Error de conexión: " . $this->db->connect_error);
			}
			$this->db->set_charset("utf8");
		}

		/**
		 * Ejecuta consultas SELECT
		 */
		public function consultar($sql, $params = [])
		{
			$stmt = $this->db->prepare($sql);
			if ($params) {
				$types = str_repeat('s', count($params));
				$stmt->bind_param($types, ...$params);
			}
			$stmt->execute();
			$result = $stmt->get_result();
			return $result->fetch_all(MYSQLI_ASSOC);
		}

		/**
		 * Ejecuta consultas INSERT, UPDATE, DELETE
		 */
		public function ejecutar($sql, $params = [])
		{
			$stmt = $this->db->prepare($sql);
			if ($params) {
				$types = str_repeat('s', count($params));
				$stmt->bind_param($types, ...$params);
			}
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		}

		/**
		 * Obtiene el último ID insertado
		 */
		public function getLastInsertId()
		{
			return $this->db->insert_id;
		}

		/**
		 * Cerrar conexión
		 */
		public function cerrarConexion()
		{
			$this->db->close();
		}
	}
?>