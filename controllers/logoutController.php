<?php 

	// Procesar logout
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Destruir todas las variables de sesión
		$_SESSION = array();
		
		// Borrar la cookie de sesión
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		
		// Destruir la sesión
		session_destroy();
		
		// Respuesta para AJAX
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			header('Content-Type: application/json');
			echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente']);
		} else {
			header("Location: ?slug=catalogo");
		}
		exit;
	}

	// Si no es POST, redirigir
	header("Location: ?slug=catalogo");
	exit;
?>