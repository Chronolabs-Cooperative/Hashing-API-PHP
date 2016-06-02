<?php
// $Id: qcp64.class.php 1.6.4 2008-08-15 13:40:00 (final) wishcraft $
//  ------------------------------------------------------------------------ //
//                    CLORA - Chronolabs Australia                           //
//                         Copyright (c) 2008                                //
//                   <http://www.chronolabs.org.au/>                         //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the SDPL Source Directive Public Licence           //
//  as published by Chronolabs Australia; either version 2 of the License,   //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Chronolab Australia        //
//  Chronolabs International PO BOX 699, DULWICH HILL, NSW, 2203, Australia  //
//  ------------------------------------------------------------------------ //

if (!class_exists('qcp64'))
{

	
	class qcp64
	{
		var $base;
		var $enum;
		var $seed;
		var $crc;
			
		function __construct($data, $seed, $len=29)
		{
			$this->seed = $seed;
			$this->length = $len;
			$this->base = new qcp64_base((int)$seed);
			$this->enum = new qcp64_enumerator($this->base);
			
			if (!empty($data))
			{
				for ($i=1; $i<strlen($data); $i++)
				{
					$enum_calc = $this->enum->enum_calc(substr($data,$i,1),$enum_calc);
				}		
				$qcp64_crc = new qcp64_leaver($enum_calc, $this->base, $this->length);	
				$qcp64_crc->reset_leaver();				
				$this->crc = $qcp64_crc->crc;			
			}
			
		}
			
		function calc($data)
		{
			for ($i=1; $i<strlen($data); $i++)
			{
				$enum_calc = $this->enum->enum_calc(substr($data,$i,1),$enum_calc);
			}		
			$qcp64_crc = new qcp64_leaver($enum_calc, $this->base, $this->length);	
			$qcp64_crc->reset_leaver();
			return $qcp64_crc->crc;
		}
		
	}
}				

require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR .  'qcp64.base.php');
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR .  'qcp64.enumerator.php');
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR .  'qcp64.leaver.php');		
		
		
