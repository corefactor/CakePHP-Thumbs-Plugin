<?php

App::import('Debugger'); 
App::import('Lib', 'Thumbs.Functions');

/**
 * undocumented class
 *
 * @package thumbs
 * @author Rui Cruz
 */
class ThumbComponent extends Object {

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

		$baseDir .= DS;
		
		if (empty($size)) {
			$height = 100;
			$width = 100;
		} else {
			$height = $size['height'];
			$width = $size['width'];
		}

		if (!file_exists($baseDir . $filename)) {
			
			Debugger::log($baseDir . $filename . ' not found');
			throw new exception('Unable to find ' . $baseDir . $filename);
			return false;
			
		}
		// verify that our file is one of the valid mime types
		// if(!in_array($this->file['type'],$this->allowed_mime_types)){
		// 	$this->addError('Invalid File type: '.$this->file['type']);
		// 	return false;
		// }

		// verify that the filesystem is writable, if not add an error to the object
		
		if ($storage_folder != 'OUTPUT') {
		
			if (!is_writable($storage_folder)) {
			
				if (!mkdir($storage_folder, 0777, true)) {
				
					Debugger::log($storage_folder . ' not writable');
					return false;
				
				}
			
			}
			
		}

		// Load phpThumb
		if (!App::import('Vendor', 'Thumb.phpthumb', array('file' => 'phpthumb/phpthumb.class.php'))) {
			throw new exception('Unable to load phpThumb from Vendors');
			return false;
		}
		
		$phpThumb = new phpThumb();

		$phpThumb->setSourceFilename($baseDir . $filename);
				
		$phpThumb->setParameter('q', 90);
		$phpThumb->setParameter('w', $width);
		$phpThumb->setParameter('h', $height);
		$phpThumb->setParameter('zc', 1);
		
		if ($phpThumb->GenerateThumbnail()) {
			
			if ($storage_folder == 'OUTPUT') {
				
				$phpThumb->OutputThumbnail();
				
			} elseif (!$phpThumb->RenderToFile($storage_folder . DS . $filename . '-' . $height . 'x' . $width . getFileExtension($filename))) {
				
				Debugger::log('Could not render to file: ' . $storage_folder . DS . $filename);
				return false;
				
			}
			
		} else {
			
			$phpThumb->phpThumbDebug();
			Debugger::log('Could not generate thumbnail: ' . $baseDir . $filename);
			return false;
			
		}
		
		return true;
		
	}

}

?>