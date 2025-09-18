<?php 

	$error_register = "";
	$success_register = "";

	// Si ya está logueado, redirigir
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
		header("Location: ?slug=catalogo");
		exit;
	}

	// Procesar registro
	if (isset($_POST['btn_registrar'])) {
		$usuario = new Usuarios();
		$response = $usuario->registrar($_POST);

		if ($response["errno"] == 201) {
			header("Location: ?slug=catalogo");
			exit;
		}

		$error_register = $response["error"];
	}

	// Manejo de peticiones AJAX
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['btn_registrar'])) {
		header('Content-Type: application/json');
		
		$input = json_decode(file_get_contents('php://input'), true);
		if ($input) {
			$usuario = new Usuarios();
			$response = $usuario->registrar($input);
			
			if ($response["errno"] == 201) {
				echo json_encode([
					'success' => true, 
					'message' => 'Usuario registrado exitosamente',
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
	$tpl = new Palta("register");

	$tpl->assign([
		"APP_SECTION" => "Registro",
		"ERROR_REGISTER" => $error_register,
		"SUCCESS_REGISTER" => $success_register
	]);

	$tpl->printToScreen();
?>