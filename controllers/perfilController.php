<?php 

	// Verificar autenticación
	if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
		header("Location: ?slug=login");
		exit;
	}

	$error_perfil = "";
	$success_perfil = "";

	$usuarios = new Usuarios();
	$user_data = $usuarios->obtenerPorId($_SESSION['usuario_id']);

	if (!$user_data) {
		session_destroy();
		header("Location: ?slug=login");
		exit;
	}

	// Actualizar perfil
	if (isset($_POST['update_profile'])) {
		$response = $usuarios->actualizarPerfil($_SESSION['usuario_id'], $_POST);
		
		if ($response["errno"] == 200) {
			$success_perfil = $response["error"];
			$user_data = $usuarios->obtenerPorId($_SESSION['usuario_id']); // Recargar datos
		} else {
			$error_perfil = $response["error"];
		}
	}

			// Cambiar contraseña
	if (isset($_POST['change_password'])) {
		if ($_POST['new_password'] !== $_POST['confirm_password']) {
			$error_perfil = "Las contraseñas no coinciden";
		} else {
			$response = $usuarios->cambiarPassword($_SESSION['usuario_id'], $_POST);
			
			if ($response["errno"] == 200) {
				$success_perfil = $response["error"];
			} else {
				$error_perfil = $response["error"];
			}
		}
	}

	/* IMPRIMO LA VISTA */
	$tpl = new Palta("perfil");

	$tpl->assign([
		"APP_SECTION" => "Mi Perfil",
		"ERROR_PERFIL" => $error_perfil,
		"SUCCESS_PERFIL" => $success_perfil,
		"USER_DATA" => json_encode($user_data),
		"USER_NAME" => $_SESSION['username'],
		"USER_EMAIL" => $_SESSION['email'],
		"USER_ID" => $_SESSION['usuario_id']
	]);

	$tpl->printToScreen();
?>