<?php

App::import('Lib', 'Thumbs.Functions');

class ThumbHelper extends AppHelper {
	
	var $helpers = array('Html');	
	
	
	/**
	 * Find out what is the folder structure for the specified identifier
	 *
	 * @param string $identifier 
	 * @return string
	 * @author Rui Cruz
	 */
	public function getStorage($identifier) {
		
		$storage_folder = getStorageFolder($identifier);
		
		$storage_folder = str_replace(WWW_ROOT, '', $storage_folder);
		
		return $storage_folder;
		
	}
	
	/**
	 * Helper function for the Html::image
	 *
	 * @param string $filename 
	 * @param string $root_folder
	 * @param string $identifier 
	 * @param array $options 
	 * @return string
	 * @author Rui Cruz
	 */
	public function image($filename, $root_folder, $identifier = null, $options = null) {
		
		if (is_null($identifier)) {
			
			$identifier = $filename;
			
		}
		
		return $this->Html->image(DS . $root_folder . DS . $this->getStorage($identifier) . $filename, $options);
		
	}
	
	public function thumbnail($filename, $root_folder, $options = null) {
		
		return $this->Html->image(array('plugin' => 'thumbs', 'controller' => 'thumbs', 'action' => 'view', $root_folder, $filename));
		
	}
	
}

?>