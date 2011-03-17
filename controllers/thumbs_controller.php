<?php
/**
 * undocumented class
 *
 * @package thumbs
 * @author Rui Cruz
 */
class ThumbsController extends AppController {
	
	var $uses = array();
	
	var $components = array(
		'Thumbs.Thumb'
	);
	
	/**
	 * Generates thumbnail on the fly without cache
	 *
	 * @param string $folder 
	 * @param string $file 
	 * @param int $width 
	 * @param int $height 
	 * @return void
	 * @author Rui Cruz
	 */
	function thumbnail($folder, $file, $width = 100, $height = 50) {
	
		$this->autoRender = false;		
		$this->Thumb->generateThumb($folder, $file, array('width' => $width, 'height' => $height), 'OUTPUT');
		
	}
	
}

?>