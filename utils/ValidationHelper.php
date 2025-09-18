<?php 

	/**
	 * Helper para validaciones
	 */
	class ValidationHelper
	{
		/**
		 * Valida un email
		 */
		public static function validateEmail($email)
		{
			return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
		}

		/**
		 * Valida un username (solo letras, números y guión bajo)
		 */
		public static function validateUsername($username)
		{
			return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
		}

		/**
		 * Valida longitud de contraseña
		 */
		public static function validatePassword($password, $min_length = 6)
		{
			return strlen($password) >= $min_length;
		}

		/**
		 * Sanitiza una cadena para output HTML
		 */
		public static function sanitizeOutput($string)
		{
			return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		}

		/**
		 * Valida que un número esté en un rango
		 */
		public static function validateNumberRange($number, $min, $max)
		{
			return is_numeric($number) && $number >= $min && $number <= $max;
		}

		/**
		 * Valida extensiones de archivo permitidas
		 */
		public static function validateFileExtension($filename, $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'])
		{
			$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			return in_array($extension, $allowed_extensions);
		}
	}
?>