<?php 

	$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
	$username = $is_logged_in ? $_SESSION['username'] : '';
	$user_type = $is_logged_in ? 'user' : 'guest';

	// Aquí podrías agregar lógica para cargar mensajes del foro desde la BD
	// Por ahora mantenemos la funcionalidad existente del foro simulado

	/* IMPRIMO LA VISTA */
	$tpl = new Palta("foro");

	$tpl->assign([
		"APP_SECTION" => "Foro",
		"IS_LOGGED_IN" => $is_logged_in ? 'true' : 'false',
		"USER_NAME" => $username,
		"USER_TYPE" => $user_type
	]);

	$tpl->printToScreen();
?>