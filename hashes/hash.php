<?php

if (!class_exists('hashAPI')) {
	class hashAPI {
		
		var $data = array();
		
		public function setInfo($info) {
			$this->data['info'] = $info;
		}
		
		public function getInfo() {
			return $this->data['info'];
		}

		public function setVariables($variables) {
			$this->data['vars'] = $variables;
		}
		
		public function getVariables() {
			return $this->data['vars'];
		}
		
		public function calc($data) {
			$ret = array();
			$ret['seconds']['start'] = microtime(true);
			$func = $this->data['info']['function'];
			$number = isset($this->data['vars']['number'])?$this->data['vars']['number']:0;
			if (!empty($func) && function_exists($func) && $number == 0) {
				$ret['hash'] = $func($data);
			} elseif (!empty($func) && function_exists($func) && $number > 0) {
				$vars = array();
				foreach($this->data['vars']['defines'] as $var => $data) {
					if (!isset($_GET[$data['variable']]) && !isset($_POST[$data['variable']])) {
						$vars[$var] = $data['default'];
					} else {
						$vars[$var] = (!isset($_GET[$data['variable']])?$_POST[$data['variable']]:$_GET[$data['variable']]);
					}
				}
				$ret['hash'] = $func($data, $vars);
			}
			$ret['seconds']['end'] = microtime(true);
			$ret['seconds']['took'] = $ret['seconds']['end'] - $ret['seconds']['start'];
			return $ret; 
		}
	}	
}