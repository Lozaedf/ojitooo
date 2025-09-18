<?php 

	/* IMPRIMO LA VISTA */
	$tpl = new Palta("error");

	$tpl->assign([
		"APP_SECTION" => "Error 404",
		"ERROR_TITLE" => "Página no encontrada",
		"ERROR_MESSAGE" => "El recurso solicitado no existe o ha sido movido."
	]);

	$tpl->printToScreen();
?>