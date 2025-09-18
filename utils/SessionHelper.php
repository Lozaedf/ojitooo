<?php 

	/**
	 * Helper para manejo de sesiones
	 */
	class SessionHelper
	{
		/**
		 * Verifica si el usuario está logueado
		 */
		public static function isLoggedIn()
		{
			return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
		}

		/**
		 * Obtiene el ID del usuario actual
		 */
		public static function getUserId()
		{
			return self::isLoggedIn() ? $_SESSION['usuario_id'] : null;
		}

		/**
		 * Obtiene el username del usuario actual
		 */
		public static function getUsername()
		{
			return self::isLoggedIn() ? $_SESSION['username'] : null;
		}

		/**
		 * Obtiene el email del usuario actual
		 */
		public static function getUserEmail()
		{
			return self::isLoggedIn() ? $_SESSION['email'] : null;
		}

		/**
		 * Requiere que el usuario esté autenticado
		 */
		public static function requireAuth($redirect = '?slug=login')
		{
			if (!self::isLoggedIn()) {
				header("Location: " . $redirect);
				exit;
			}
		}

		/**
		 * Requiere que el usuario NO esté autenticado
		 */
		public static function requireGuest($redirect = '?slug=catalogo')
		{
			if (self::isLoggedIn()) {
				header("Location: " . $redirect);
				exit;
			}
		}
	}
?>