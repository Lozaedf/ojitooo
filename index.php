<?php 

	session_start();

	/****
	 * Router principal de RecicNet
	 * Maneja el enrutamiento y carga de controladores con validaciones
	 ****/

	// Cargar configuraciones
	include 'env.php';

	// Cargar librerías
	include 'librarys/palta/Palta.php';

	// Cargar utilidades
	include 'utils/SessionHelper.php';
	include 'utils/ValidationHelper.php';
	include 'utils/FileHelper.php';

	// Cargar modelos
	include 'models/DBAbstract.php';
	include 'models/Usuarios.php';
	include 'models/Desperdicios.php';
	include 'models/TipoMaterial.php';
	include 'models/Fabricas.php';

	// Cargar configuración de rutas
	$routes_config = include 'config/routes.php';

	// Obtener la sección solicitada
	$section = isset($_GET["slug"]) ? $_GET['slug'] : $routes_config['default'];

	// Verificar que la ruta sea válida
	if (!in_array($section, $routes_config['available_routes'])) {
		$section = "error";
	}

	// Verificar autenticación requerida
	if (in_array($section, $routes_config['auth_required']) && !SessionHelper::isLoggedIn()) {
		header("Location: ?slug=login");
		exit;
	}

	// Verificar rutas solo para invitados
	if (in_array($section, $routes_config['guest_only']) && SessionHelper::isLoggedIn()) {
		header("Location: ?slug=" . $routes_config['default']);
		exit;
	}

	// Verificar que existe el controlador
	if (!file_exists('controllers/'.$section.'Controller.php')) {
		$section = "error";
	}

	// Incluir el controlador
	include 'controllers/'.$section.'Controller.php';
?>