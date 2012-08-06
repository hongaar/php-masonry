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
// $Id: Tuple.php 304045 2010-10-05 00:16:53Z clockwerx $
//

/**
 * General Tuple class
 * A Tuple represents a general unidimensional list of n numeric elements
 * Originally this class was part of NumPHP (Numeric PHP package)
 *
 * @author	Jesus M. Castagnetto <jmcastagnetto@php.net>
 * @version	1.0
 * @access	public
 * @package	Math_Vector
 */
class Math_Tuple {

	/**
	 * array of numeric elements
	 *
	 * @var		array
	 * @access	private
	 */
	var $data = null;
	
	/**
	 * Constructor of Math_Tuple
	 *
	 * @param	array	$data	array of numbers
	 * @access	public
	 * @return	object	Math_Tuple (or PEAR_Error on error)
	 */
	function Math_Tuple ($data) /*{{{*/
	{
		if (is_array($data) || !is_array($data[0])) {
			$this->data = $data;
		} else {
			new PEAR_Error("An unidimensional array is needed to initialize a Tuple",
								null, PEAR_ERROR_DIE);
		}
	}/*}}}*/

	/**
	 * Squeezes out holes in the tuple sequence
	 *
	 * @access	public
	 * @return	void
	 */
	function squeezeHoles ()/*{{{*/
	{
		$this->data = explode(":", implode(":",$this->data));
	}/*}}}*/

	/**
	 * Returns the size (number of elements) in the tuple
	 *
	 * @access	public
	 * @return	integer
	 */
	function getSize () /*{{{*/
	{
		return count($this->data);
	}/*}}}*/

	/**
	 * Sets the value of an element
	 *
	 * @access	public
	 * @param	integer	$elindex	element index
	 * @param	numeric	$elvalue	element value
	 * @return	mixed	true if successful, PEAR_Error object otherwise
	 */
	function setElement ($elindex, $elvalue) /*{{{*/
	{
		if ($elindex >= $this->getSize()) {
			return PEAR::raiseError("Wrong index: $elindex for element: $elvalue");
		}
		$this->data[$elindex] = $elvalue;
		return true;
	}/*}}}*/

	/**
	 * Appends an element to the tuple
	 *
	 * @access	public
	 * @param	numeric	$elvalue	element value
	 * @return	mixed	index of appended element on success, PEAR_Error object otherwise
	 */
	function addElement ($elvalue) /*{{{*/
	{
		if (!is_numeric($elvalue)) {
			return PEAR::raiseError("Error, a numeric value is needed. You used: $elvalue");
		}
		$this->data[$this->getSize()] = $elvalue;
		return ($this->getSize() - 1);
	}/*}}}*/

	/**
	 * Remove an element from the tuple
	 *
	 * @access public
	 * @param	integer $elindex	element index
	 * @return	mixed	true on success, PEAR_Error object otherwise
	 */
	function delElement ($elindex) /*{{{*/
	{
		if ($elindex >= $this->getSize()) {
			return PEAR::raiseError("Wrong index: $elindex, element not deleted");
		}
		unset($this->data[$elindex]);
		$this->squeezeHoles();
		return true;
	}/*}}}*/

	/**
	 * Returns the value of an element in the tuple
	 *
	 * @access	public
	 * @param	integer	$elindex	element index
	 * @return	mixed	numeric on success, PEAR_Error otherwise
	 */
	function getElement($elindex) /*{{{*/
	{
		if ($elindex >= $this->getSize()) {
			return PEAR::raiseError("Wrong index: $elindex, Tuple size is: ".$this->getSize());
		}
		return $this->data[$elindex];
	}/*}}}*/

	/**
	 * Returns an array with all the elements of the tuple
	 *
	 * @access	public
	 * @return	$array
	 */
	function getData () /*{{{*/
	{
		$this->squeezeHoles();
		return $this->data;
	}/*}}}*/
	
	/**
	 * Returns the minimum value of the tuple
	 *
	 * @access	public
	 * @return	numeric
	 */
	function getMin () /*{{{*/
	{
		return min($this->getData());
	}/*}}}*/
	
	/**
	 * Returns the maximum value of the tuple
	 *
	 * @access	public
	 * @return	numeric
	 */
	function getMax () /*{{{*/
	{
		return max($this->getData());
	}/*}}}*/

	/**
	 * Returns an array of the minimum and maximum values of the tuple
	 *
	 * @access	public
	 * @return	array of the minimum and maximum values
	 */
	function getMinMax () /*{{{*/
	{
		return array ($this->getMin(), $this->getMax());
	}/*}}}*/
	
	/**
	 * Gets the position of the given value in the tuple
	 *
	 * @access	public
	 * @param	numeric	$val	value for which the index is requested
	 * @return	integer
	 */
	function getValueIndex ($val) /*{{{*/
	{
		for ($i=0; $i < $this->getSize(); $i++)
			if ($this->data[$i] == $val)
				return $i;
		return false;
	}/*}}}*/

	/**
	 * Gets the position of the minimum value in the tuple
	 *
	 * @access	public
	 * @return	integer
	 */
	function getMinIndex () /*{{{*/
	{
		return $this->getValueIndex($this->getMin());
	}/*}}}*/

	/**
	 * Gets the position of the maximum value in the tuple
	 *
	 * @access	public
	 * @return	integer
	 */
	function getMaxIndex () /*{{{*/
	{
		return $this->getValueIndex($this->getMax());
	}/*}}}*/

	/**
	 * Gets an array of the positions of the minimum and maximum values in the tuple
	 *
	 * @access	public
	 * @return	array of integers indexes
	 */
	function getMinMaxIndex () /*{{{*/
	{
		return array($this->getMinIndex(), $this->getMaxIndex());
	}/*}}}*/

	/**
	 * Checks if the tuple is a a Zero tuple
	 *
	 * @access	public
	 * @return	boolean
	 */
	function isZero () /*{{{*/
	{
		for ($i=0; $i < $this->getSize(); $i++)
			if ($this->data[$i] != 0)
				return false;
		return true;
	}/*}}}*/
	
	/**
	 * Returns an string representation of the tuple
	 *
	 * @access	public
	 * @return	string
	 */
	function toString () /*{{{*/
	{
		return "{ ".implode(", ",$this->data)." }";
	}/*}}}*/

	/**
	 * Returns an HTML representation of the tuple
	 *
	 * @access	public
	 * @return	string
	 */
	function toHTML() /*{{{*/
	{
		$out = "<table border>\n\t<caption align=\"top\"><b>Vector</b></caption>\n";
		$out .= "\t<tr align=\"center\">\n\t\t<th>i</th><th>value</th>\n\t</tr>\n";
		for ($i=0; $i < $this->getSize(); $i++) {
			$out .= "\t<tr align=\"center\">\n\t\t<th>".$i."</th>";
			$out .= "<td bgcolor=\"#dddddd\">".$this->data[$i]."</td>\n\t</tr>\n";
		}
		return $out."\n</table>\n";
	}/*}}}*/
	
} /* end of Tuple class */


?>
