<?php

if (!class_exists('Md5HashAPI')) {
	class Md5HashAPI extends hashAPI {
		
		function __construct() {
			parent::setInfo(array('function'=>'md5', 'author'=>'Simon Roberts', 'description'=>'MD5 Checksum'));
			parent::setVariables(array('number'=>0, 'defines'=>array()));
		}

	}
}

?>