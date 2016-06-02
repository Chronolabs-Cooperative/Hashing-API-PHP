<?php

if (!class_exists('Sha1HashAPI')) {
	class Sha1HashAPI extends hashAPI {
		
		function __construct() {
			parent::setInfo(array('function'=>'sha1', 'author'=>'Simon Roberts', 'description'=>'SHA1 Checksum'));
			parent::setVariables(array('number'=>0, 'defines'=>array()));
		}

	}
}

?>