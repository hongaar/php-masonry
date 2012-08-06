<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Jesus M. Castagnetto <jmcastagnetto@php.net>                |
// +----------------------------------------------------------------------+
//
// $Id: CompactedTuple.php 304045 2010-10-05 00:16:53Z clockwerx $
//
			
class Math_CompactedTuple {

	var $data;

	function Math_CompactedTuple ($arg) 
	{
		if (is_array($arg)) {
			$this->data = $this->_genCompactedArray($arg);
		} elseif (is_object($arg) && get_class($arg) == "math_tuple") {
			$this->data = $this->_genCompacterArray($arg->getData());
		} else {
			$msg = "Incorrect parameter for Math_CompactedTuple constructor. ".
					"Expecting an unidimensional array or a Math_Tuple object,". 
					" got '$arg'\n";
			PEAR::raiseError($msg);
		}
		return true;
	}

	function getSize() {
		return count($this->_genUnCompactedArray($this->data));
	}

	function getCompactedSize() {
		return count($this->data);
	}

	function getCompactedData() {
		return $this->data;
	}

	function getData() {
		return $this->_genUnCompactedArray($this->data);
	}

	function addElement($value) {
		$this->data[$value]++;
	}
	
	function delElement($value) {
		if (in_array($value, array_keys($this->data))) {
			$this->data[$value]--;
			if ($this->data[$value] == 0)
				unset ($this->data[$value]);
			return true;
		}
		return PEAR::raiseError("value does not exist in compacted tuple");
	}

	function hasElement($value) {
		return in_array($value, array_keys($this->data));
	}

	function _genCompactedArray($arr) {
		if (function_exists("array_count_values")) {
			return array_count_values($arr);
		} else {
			$out = array();
			foreach ($arr as $val)
				$out[$val]++;
			return $out;
		}
	}

	function _genUnCompactedArray($arr) {
		$out = array();
		foreach ($arr as $val=>$count)
			for($i=0; $i < $count; $i++)
				$out[] = $val;
		return $out;
	}
}

?>
