<?php
/**
 * XOOPS checksum handler
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.5.5
 * @author          Simon Roberts (AKA wishcraft) http://www.chronolabs.com.au/
 * @version         $Id: xoopscrc.php 8066 2012-09-08 12:19:00Z wishcraft $
 */

include_once dirname(__FILE__).'/qcp64.class.php';


if (!class_exists('Qcp64HashAPI')) {
	class Qcp64HashAPI extends hashAPI {

		/**
		 * 3rd Party class/function to use for generation of checksum
		 *
		 */
		var $_class = 'qcp64';

		/**
		 * method name for calculation of checksum
		 *
		 */
		var $_method = 'calc';

		/**
		 * generate seed for calculation of checksum
		 *
		 */
		var $_selfseed = true;

		/**
		 * default seed when not provided calculation of checksum
		 * min = 0, max = 254
		 */
		var $_defaultseed = "$";
		
		/**
		 * generate seed for calculation of checksum
		 * 
		 */
		var $_length = 29;


		function __construct() {
			parent::setInfo(array('function'=>'getQcp64', 'author'=>'Simon Roberts', 'description'=>'QCP64 Hashinfo/Checksum'));
			parent::setVariables(array('selfseed'=>$this->_selfseed, 'length' => $this->_length, 'defines' => array(), 'class' => __CLASS__, 'static' => false));
		} 

		static public function getInstance($single = false, $class = __CLASS__)
		{
			if ($single==true) {
				static $_object;
				if (!is_a($_object, $class))
					$_object = new $class();
				return $_object;
			}
			return new $class();
		}
		
		public function calc($data, $options) {
			if (!isset($options['seed']) && !isset($options['selfseed']) || $options['selfseed'] = false) {
				if(is_string($this->_defaultseed))
					$options['seed'] = ord(substr($this->_defaultseed, 0, 1));
				elseif(is_numeric($this->_defaultseed) && $this->_defaultseed >= 0 && $this->_defaultseed <= 254 )
					$options['seed'] = $this->_defaultseed;
			} elseif (isset($options['selfseed'])) {
				$options['seed'] = ord(substr($data, strlen($data)/2, 1));
			}
			if (!isset($options['length'])) {
					$options['length'] = $this->_length;
			}
			if (!isset($options['seed']) && empty($options['seed']) && $options['seed'] < 0 && $options['seed'] > 254)
				die("Seed Incorrect or Missing for Function: " . __FUNCTION__ . ' in class: ' . __CLASS__);
			if (!isset($options['length']) && empty($options['length']) && $options['length'] < 2)
				die("CRC Output Length Incorrect or Missing for Function: " . __FUNCTION__ . ' in class: ' . __CLASS__);
			set_time_limit(120);
			$crc = new $this->_class($data, $options['seed'], $options['length']);
			return $crc->crc;
		}
	}
}

/**
 * function calc
 * For Calculating an Checksum from Parent Class
 *
 * @param string $data
 * @param array $options
 */
if (!function_exists('getQcp64'))
{
	function getQcp64($data = NULL, $options = array())
	{
		if (isset($options['class']) && class_exists($options['class']))
			$_class = $options['class'];
		else
			$_class = 'Qcp64HashAPI';
		if (isset($options['static']))
		{
			$_hasher = $_class::getInstance($options['static'], $_class);
		} else {
			$_hasher = $_class::getInstance(false, $_class);
		}
		return $_hasher->calc($data, $options);
	}
}

?>