<?php

App::import('Debugger'); 
App::import('Lib', 'Thumbs.Functions');

class ThumbComponent {

	/**
	 * The mime types that are allowed for images
	 */
	var $allowed_mime_types = array(
		'image/jpeg',
		'image/pjpeg',
		'image/gif',
		'image/png'
	);

	function startup(&$controller) {

		$this->controller = &$controller;
		
    }

	/**
	 * This is the method that actually does the thumbnail generation by setting up
	 * the parameters and calling phpThumb
	 *
	 * @return bool Success?
	 * @author Nate Constant
	 **/
	function generateThumb($baseDir, $filename, $size = array(), $storage_folder = null) {
		// Make sure we have the name of the uploaded file and that the Model is specified
		if(empty($baseDir) || empty($filename)){
			return false;
		}
		if (empty($size)) {
			$height = 100;
			$width = 100;
		} else {
			$height = $size['height'];
			$width = $size['width'];
		}

		// verify that the size is greater than 0 ( emtpy file uploaded )
		if(filesize($baseDir . $filename === 0)) {
			
			Debugger::log('File is empty or doesn\'t exist');
			return false;
			
		}

		// verify that our file is one of the valid mime types
//		if(!in_array($this->file['type'],$this->allowed_mime_types)){
//			$this->addError('Invalid File type: '.$this->file['type']);
//			return false;
//		}

		// verify that the filesystem is writable, if not add an error to the object
		// dont fail if not and let phpThumb try anyway
		if (!is_writable($storage_folder)) {
			
			if (!mkdir($storage_folder, 0777, true)) {
				
				Debugger::log($storage_folder . ' not writable');
				return false;
				
			}
			
		}

		// Load phpThumb
		App::import('Vendor', 'phpThumb', array('file' => 'phpThumb/phpthumb.class.php'));
		$phpThumb = new phpThumb();

		$phpThumb->setSourceFilename($baseDir . $filename);
		$phpThumb->setParameter('q', 90);
		$phpThumb->setParameter('w', $width);
		$phpThumb->setParameter('h', $height);
		$phpThumb->setParameter('zc', 1);

		if ($phpThumb->generateThumbnail()) {
			
			if (!$phpThumb->RenderToFile($storage_folder . DS . $filename . '-' . $height . 'x' . $width . getFileExtension($filename))) {
				
				Debugger::log('Could not render to file');
				return false;
				
			}
			
		} else {
			
			Debugger::log('Could not generate thumbnail');
			return false;
			
		}
		
		return true;
		
	}

}
?>