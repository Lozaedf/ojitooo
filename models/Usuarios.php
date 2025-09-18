<?php 
	
	/**
	 * Modelo Usuario
	 * Maneja todas las operaciones relacionadas con usuarios
	 */
	class Usuarios extends DBAbstract
	{
		public $usuario_id, $username, $email, $password, $fecha_registro;
		
		function __construct()
		{
			parent::__construct();
			$this->usuario_id = "";
			$this->username = "";
			$this->email = "";
			$this->password = "";
			$this->fecha_registro = "";
		}

		/**
		 * Obtiene la cantidad total de usuarios
		 */
		public function getCant()
		{
			$result = $this->consultar("SELECT COUNT(*) as total FROM usuarios");
			return $result[0]['total'];
		}

		/**
		 * Valida el login de usuario
		 */
		public function login($form)
		{
			$this->email = trim($form['email']);
			$this->password = $form['password'];

			if (empty($this->email)) {
				return ["errno" => 404, "error" => "Falta email"];
			}

			if (empty($this->password)) {
				return ["errno" => 404, "error" => "Falta contraseña"];
			}

			$usuarios = $this->consultar("SELECT * FROM usuarios WHERE email = ?", [$this->email]);

			if (count($usuarios) == 0) {
				return ["errno" => 400, "error" => "Usuario no registrado"];
			}

			$usuario = $usuarios[0];
			if (!password_verify($this->password, $usuario["password"])) {
				return ["errno" => 401, "error" => "Contraseña incorrecta"];
			}

			// Guardar datos en sesión
			$_SESSION['usuario_id'] = $usuario['usuario_id'];
			$_SESSION['username'] = $usuario['username'];
			$_SESSION['email'] = $usuario['email'];
			$_SESSION['logged_in'] = true;

			return ["errno" => 202, "error" => "Acceso válido", "usuario" => $usuario];
		}

		/**
		 * Registra un nuevo usuario
		 */
		public function registrar($datos)
		{
			$this->username = trim($datos['username']);
			$this->email = trim($datos['email']);
			$this->password = $datos['password'];

			// Validaciones
			if (empty($this->username) || empty($this->email) || empty($this->password)) {
				return ["errno" => 400, "error" => "Todos los campos son obligatorios"];
			}

			if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
				return ["errno" => 400, "error" => "Email inválido"];
			}

			if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->username)) {
				return ["errno" => 400, "error" => "Nombre de usuario inválido"];
			}

			if (strlen($this->password) < 6) {
				return ["errno" => 400, "error" => "La contraseña debe tener al menos 6 caracteres"];
			}

			// Verificar si ya existe
			$existentes = $this->consultar("SELECT COUNT(*) as total FROM usuarios WHERE username = ? OR email = ?", 
				[$this->username, $this->email]);

			if ($existentes[0]['total'] > 0) {
				return ["errno" => 400, "error" => "Usuario o email ya existe"];
			}

			// Hashear contraseña
			$hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

			// Insertar usuario
			$resultado = $this->ejecutar(
				"INSERT INTO usuarios (username, email, password, fecha_registro) VALUES (?, ?, ?, NOW())",
				[$this->username, $this->email, $hashedPassword]
			);

			if ($resultado) {
				$usuario_id = $this->getLastInsertId();
				
				// Crear sesión automáticamente
				$_SESSION['usuario_id'] = $usuario_id;
				$_SESSION['username'] = $this->username;
				$_SESSION['email'] = $this->email;
				$_SESSION['logged_in'] = true;

				return ["errno" => 201, "error" => "Usuario registrado exitosamente"];
			}

			return ["errno" => 500, "error" => "Error al registrar usuario"];
		}

		/**
		 * Obtiene datos de un usuario por ID
		 */
		public function obtenerPorId($id)
		{
			$usuarios = $this->consultar("SELECT * FROM usuarios WHERE usuario_id = ?", [$id]);
			return count($usuarios) > 0 ? $usuarios[0] : null;
		}

		/**
		 * Actualiza el perfil del usuario
		 */
		public function actualizarPerfil($id, $datos)
		{
			$username = trim($datos['username']);
			$email = trim($datos['email']);

			if (empty($username) || empty($email)) {
				return ["errno" => 400, "error" => "Username y email son obligatorios"];
			}

			// Verificar que no estén en uso por otro usuario
			$existentes = $this->consultar(
				"SELECT COUNT(*) as total FROM usuarios WHERE (username = ? OR email = ?) AND usuario_id != ?",
				[$username, $email, $id]
			);

			if ($existentes[0]['total'] > 0) {
				return ["errno" => 400, "error" => "Username o email ya están en uso"];
			}

			$resultado = $this->ejecutar(
				"UPDATE usuarios SET username = ?, email = ? WHERE usuario_id = ?",
				[$username, $email, $id]
			);

			if ($resultado) {
				$_SESSION['username'] = $username;
				$_SESSION['email'] = $email;
				return ["errno" => 200, "error" => "Perfil actualizado correctamente"];
			}

			return ["errno" => 500, "error" => "Error al actualizar perfil"];
		}

		/**
		 * Cambia la contraseña del usuario
		 */
		public function cambiarPassword($id, $datos)
		{
			$current_password = $datos['current_password'];
			$new_password = $datos['new_password'];

			if (empty($current_password) || empty($new_password)) {
				return ["errno" => 400, "error" => "Contraseñas requeridas"];
			}

			if (strlen($new_password) < 6) {
				return ["errno" => 400, "error" => "Nueva contraseña muy corta"];
			}

			// Obtener contraseña actual
			$usuario = $this->obtenerPorId($id);
			if (!$usuario || !password_verify($current_password, $usuario['password'])) {
				return ["errno" => 400, "error" => "Contraseña actual incorrecta"];
			}

			// Actualizar contraseña
			$hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
			$resultado = $this->ejecutar(
				"UPDATE usuarios SET password = ? WHERE usuario_id = ?",
				[$hashedPassword, $id]
			);

			return $resultado ? 
				["errno" => 200, "error" => "Contraseña actualizada"] :
				["errno" => 500, "error" => "Error al actualizar contraseña"];
		}
	}
?>