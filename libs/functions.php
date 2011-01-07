<?php


/**
 * Defines a distribuited file storage structure
 *
 * @param string $identifier 
 * @return string
 * @author Rui Cruz
 */
function getStorageFolder($identifier) {
	
	$storage_folder = str_split(md5($identifier), 8);
	$storage_folder = implode(DS, $storage_folder) . DS;
	
	return $storage_folder;
	
}

/**
 * Return the file extension from a file 
 *
 * @param string $filename 
 * @param bool $with_dot 
 * @return void
 * @author Rui Cruz
 */
function getFileExtension($filename, $with_dot = true) {
	
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	
	if ($with_dot === true) {
	
		return '.' . $ext;
		
	} else {
		
		return $ext;
		
	}
	
}

?>