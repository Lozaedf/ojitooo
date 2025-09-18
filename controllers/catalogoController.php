<?php 

	/* LÓGICA DE NEGOCIO */
	$desperdicios = new Desperdicios();
	$tipoMaterial = new TipoMaterial();

	$lista_desperdicios = $desperdicios->obtenerTodos();
	$tipos_material = $tipoMaterial->obtenerTodos();

	$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
	$filtro_peligrosidad = isset($_GET['peligrosidad']) ? $_GET['peligrosidad'] : '';

	// Aplicar filtros si existen
	if (!empty($filtro_tipo)) {
		$lista_desperdicios = $desperdicios->obtenerPorTipo($filtro_tipo);
	}

	/* IMPRIMO LA VISTA */
	$tpl = new Palta("catalogo");

	$tpl->assign([
		"APP_SECTION" => "Catálogo",
		"LISTA_DESPERDICIOS" => json_encode($lista_desperdicios),
		"TIPOS_MATERIAL" => json_encode($tipos_material),
		"FILTRO_TIPO" => $filtro_tipo,
		"FILTRO_PELIGROSIDAD" => $filtro_peligrosidad
	]);

	$tpl->printToScreen();
?>