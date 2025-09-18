<?php 

	/**
	 * Configuración de rutas de la aplicación
	 */
	return [
		'default' => 'catalogo',
		'auth_required' => ['perfil', 'publicar'],
		'guest_only' => ['login', 'register'],
		'available_routes' => [
			'catalogo',
			'foro', 
			'publicar',
			'perfil',
			'login',
			'register', 
			'logout',
			'error'
		]
	];
?>