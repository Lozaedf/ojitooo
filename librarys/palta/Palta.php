<?php 

	/**
	 * Sistema de templates Palta - Versión mejorada para RecicNet
	 */
	class Palta
	{
		private $tpl_name;
		private $buffer_tpl;
		private $variables = [];
		
		function __construct($name_view)
		{
			// Cargar la vista principal
			$view_path = 'views/'.$name_view.'View.tpl.php';
			
			if (!file_exists($view_path)) {
				throw new Exception("Vista no encontrada: " . $view_path);
			}

			$this->buffer_tpl = file_get_contents($view_path);

			// Cargar componentes
			$this->loadComponent('head');
			$this->loadComponent('footer');
			$this->loadComponent('sidebar');

			// Asignar variables del entorno por defecto
			$this->assign([
				"APP_NAME" => APP_NAME,
				"APP_DESCRIPTION" => APP_DESCRIPTION,
				"APP_AUTHOR" => APP_AUTHOR,
				"COLOR_FONDO_PRINCIPAL" => COLOR_FONDO_PRINCIPAL,
				"COLOR_TEXTO_PRINCIPAL" => COLOR_TEXTO_PRINCIPAL,
				"COLOR_TEXTO_SECUNDARIO" => COLOR_TEXTO_SECUNDARIO,
				"COLOR_ACENTO_PRINCIPAL" => COLOR_ACENTO_PRINCIPAL,
				"COLOR_ACENTO_SECUNDARIO" => COLOR_ACENTO_SECUNDARIO,
				"COLOR_BOTON_PRINCIPAL_TEXTO" => COLOR_BOTON_PRINCIPAL_TEXTO,
				"COLOR_BOTON_SECUNDARIO_TEXTO" => COLOR_BOTON_SECUNDARIO_TEXTO,
				"COLOR_HEADER_FONDO" => COLOR_HEADER_FONDO,
				"COLOR_FOOTER_FONDO" => COLOR_FOOTER_FONDO,
				"COLOR_FOOTER_TEXTO" => COLOR_FOOTER_TEXTO,
			]);
		}

		/**
		 * Carga un componente y lo reemplaza en el template
		 */
		private function loadComponent($component_name)
		{
			$component_path = 'views/components/'.$component_name.'Component.tpl.php';
			
			if (file_exists($component_path)) {
				$component_content = file_get_contents($component_path);
				$this->buffer_tpl = str_replace("@component({$component_name})", $component_content, $this->buffer_tpl);
			}
		}

		/**
		 * Asigna variables al template
		 */
		public function assign($array_assoc)
		{
			foreach ($array_assoc as $key => $value) {
				$this->variables[$key] = $value;
				
				// Reemplazar en el buffer
				if (is_string($value) || is_numeric($value)) {
					$this->buffer_tpl = str_replace("{{ ".$key." }}", $value, $this->buffer_tpl);
				}
			}
		}

		/**
		 * Obtiene una variable asignada
		 */
		public function getVariable($key)
		{
			return isset($this->variables[$key]) ? $this->variables[$key] : null;
		}

		/**
		 * Renderiza y muestra el template
		 */
		public function printToScreen()
		{
			// Procesar variables de sesión si existen
			if (session_status() === PHP_SESSION_ACTIVE) {
				$session_vars = [
					"SESSION_USER_ID" => $_SESSION['usuario_id'] ?? '',
					"SESSION_USERNAME" => $_SESSION['username'] ?? '',
					"SESSION_EMAIL" => $_SESSION['email'] ?? '',
					"SESSION_LOGGED_IN" => isset($_SESSION['logged_in']) ? ($_SESSION['logged_in'] ? 'true' : 'false') : 'false'
				];
				
				$this->assign($session_vars);
			}

			// Limpiar variables no reemplazadas (opcional)
			$this->buffer_tpl = preg_replace('/\{\{\s*[A-Z_]+\s*\}\}/', '', $this->buffer_tpl);

			echo $this->buffer_tpl;
		}

		/**
		 * Renderiza el template como string sin mostrarlo
		 */
		public function render()
		{
			return $this->buffer_tpl;
		}
	}
?>