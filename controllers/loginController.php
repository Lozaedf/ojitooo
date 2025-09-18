<?php 

	$error_login = "";
	$success_login = "";

	// Si ya está logueado, redirigir
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
		header("Location: ?slug=catalogo");
		exit;
	}

	// Procesar login
	if (isset($_POST['btn_ingresar'])) {
		$usuario = new Usuarios();
		$response = $usuario->login($_POST);

		if ($response["errno"] == 202) {
			header("Location: ?slug=catalogo");
			exit;
		}

		$error_login = $response["error"];
	}

	// Manejo de peticiones AJAX
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['btn_ingresar'])) {
		header('Content-Type: application/json');
		
		$input = json_decode(file_get_contents('php://input'), true);
		if ($input) {
			$usuario = new Usuarios();
			$response = $usuario->login($input);
			
			if ($response["errno"] == 202) {
				echo json_encode([
					'success' => true, 
					'message' => 'Inicio de sesión exitoso',
					'user' => [
						'username' => $_SESSION['username'],
						'email' => $_SESSION['email']
					]
				]);
			} else {
				echo json_encode(['success' => false, 'message' => $response['error']]);
			}
			exit;
		}
	}

	/* IMPRIMO LA VISTA */
	$tpl = new Palta("login");

	$tpl->assign([
		"APP_SECTION" => "Iniciar Sesión",
		"ERROR_LOGIN" => $error_login,
		"SUCCESS_LOGIN" => $success_login
	]);

	$tpl->printToScreen();
?>