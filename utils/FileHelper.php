<?php 

	/**
	 * Helper para manejo de archivos
	 */
	class FileHelper
	{
		/**
		 * Sube un archivo de imagen
		 */
		public static function uploadImage($file, $upload_dir = 'uploads/', $max_size = 5242880)
		{
			// Verificar errores
			if ($file['error'] !== UPLOAD_ERR_OK) {
				return ['success' => false, 'error' => 'Error al subir el archivo'];
			}

			// Crear directorio si no existe
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}

			// Validar tamaño
			if ($file['size'] > $max_size) {
				return ['success' => false, 'error' => 'Archivo demasiado grande'];
			}

			// Validar extensión
			if (!ValidationHelper::validateFileExtension($file['name'])) {
				return ['success' => false, 'error' => 'Tipo de archivo no permitido'];
			}

			// Generar nombre único
			$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
			$filename = uniqid('img_') . '.' . $extension;
			$upload_path = $upload_dir . $filename;

			// Mover archivo
			if (move_uploaded_file($file['tmp_name'], $upload_path)) {
				return ['success' => true, 'filename' => $filename, 'path' => $upload_path];
			}

			return ['success' => false, 'error' => 'Error al mover el archivo'];
		}

		/**
		 * Elimina un archivo
		 */
		public static function deleteFile($filepath)
		{
			if (file_exists($filepath)) {
				return unlink($filepath);
			}
			return false;
		}
	}
?>