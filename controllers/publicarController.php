<?php 

	// Verificar autenticación
	if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
		header("Location: ?slug=login");
		exit;
	}

	$error_publicar = "";
	$success_publicar = "";

	$desperdicios = new Desperdicios();
	$tipoMaterial = new TipoMaterial();
	$fabricas = new Fabricas();

	// Obtener datos para el formulario
	$tipos_material = $tipoMaterial->obtenerTodos();
	$lista_fabricas = $fabricas->obtenerTodas();

	// Procesar publicación
	if (isset($_POST['btn_publicar'])) {
		$response = $desperdicios->publicar($_POST, $_FILES['foto_material'] ?? null);

		if ($response["errno"] == 201) {
			$success_publicar = $response["error"];
		} else {
			$error_publicar = $response["error"];
		}
	}

	/* IMPRIMO LA VISTA */
	$tpl = new Palta("publicar");

	$tpl->assign([
		"APP_SECTION" => "Publicar",
		"ERROR_PUBLICAR" => $error_publicar,
		"SUCCESS_PUBLICAR" => $success_publicar,
		"TIPOS_MATERIAL" => json_encode($tipos_material),
		"FABRICAS" => json_encode($lista_fabricas),
		"USER_NAME" => $_SESSION['username']
	]);

	$tpl->printToScreen();
?>