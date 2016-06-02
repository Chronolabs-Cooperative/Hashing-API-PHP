<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'hash.php';

if (!class_exists('hashesAPI')) {
	class hashesAPI {
		
		var $hashes = array();
		
		function __construct() {
			$this->hashes = $this->getHashesArray();
		}
		
		function __call($method, $variables) {
			if (!isset($variables[0])) {
				return array('error'=>'Neither $_GET["data"] or $_POST["data"] is set there is no data to checksum at the moment');
			}
			if (!in_array($method, array_keys($this->hashes))) {
				return array('error'=>'The hashing algoritm selected is not valid you may choose from the options of: '. implode(', ', array_keys($this->hashes)));
			}	
			return $this->hashes[$method]['class']->calc($variables[0]);
		}
		
		private function getHashesArray() 
		{
			static $hashes = array();
			if (empty($hashes) && count($hashes)==0) {
				foreach($this->getDirListAsArray(dirname(__FILE__)) as $dir) {
					if (file_exists($filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $dir . '.php')) {
						if (!class_exists($class_name = ucfirst(strtolower($dir)) . 'HashAPI')) {
							include_once($filename);
							if (class_exists($class_name)) {
								$hashes[$dir]['class'] = new $class_name();
								$hashes[$dir]['info'] = $hashes[$dir]['class']->getInfo();
							}
						}
					}
				}
			}
			return $hashes;
		}
		
		/**
		 * gets list of name of directories inside a directory
		 */
		private function getDirListAsArray($dirname)
		{
			$ignored = array('cvs' , '_darcs');
			$list = array();
            if (substr($dirname, - 1) != '/') {
                $dirname .= '/';
            }
            if ($handle = opendir($dirname)) {
                while ($file = readdir($handle)) {
                    if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
                        continue;
                    if (is_dir($dirname . $file)) {
                        $list[$file] = $file;
                    }
                }
                closedir($handle);
                asort($list);
                reset($list);
            }
            return $list;
        }
	}
}